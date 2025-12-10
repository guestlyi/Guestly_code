<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SumsubService;
use Illuminate\Http\Request;

class SumsubController extends Controller
{
    public function kycCheck()
    {
        return view(template() . 'affiliate.verification_center.sumSub.sumsub');
    }

    public function getToken()
    {
        $user = auth('affiliate')->user();
        $sumsub = new SumsubService();
        $tokenData = $sumsub->createAccessToken([
            'applicantIdentifiers' => [
                'email' => $user->email,
                'phone' => $user->phone,
            ],
            'userId' => $user->id,
            'ttlInSecs' => 600,
            'levelName' => config('services.sumsub.level_name'),
        ]);

        return response()->json($tokenData);
    }

    public function webhookRes(Request $request)
    {
        $rawPayload = file_get_contents('php://input');

        $algoHeader = $request->header('X-Payload-Digest-Alg');
        $digestHeader = $request->header('X-Payload-Digest');
        $secretKey = config('services.sumsub.secret_key');

        if (!$algoHeader || !$digestHeader) {
            return response()->json(['message' => 'Missing headers'], 400);
        }

        $algo = match($algoHeader) {
            'HMAC_SHA1_HEX' => 'sha1',
            'HMAC_SHA256_HEX' => 'sha256',
            'HMAC_SHA512_HEX' => 'sha512',
            default => null,
        };

        if (!$algo) {
            return response()->json(['message' => 'Unsupported algorithm'], 400);
        }

        $computedDigest = hash_hmac($algo, $rawPayload, $secretKey);

        if (!hash_equals($computedDigest, $digestHeader)) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payload = $request->all();

        $externalUserId = $payload['externalUserId'] ?? null;
        $reviewStatus = $payload['reviewStatus'] ?? null;
        $reviewResult = $payload['reviewResult']['reviewAnswer'] ?? null;

        if (!$externalUserId || !$reviewStatus) {
            return response()->json(['message' => 'Invalid webhook data'], 400);
        }

        $user = User::whereNot('identity_verify', 2)->find($externalUserId);
        if ($user) {
            if ($reviewStatus == 'pending') {
                $user->identity_verify = 1;
            }
            if ($reviewStatus == 'completed' && $reviewResult == 'GREEN') {
                $user->identity_verify = 2;
                $this->userSendMailNotify($user, 'approve');
            }
            if ($reviewStatus == 'completed' && $reviewResult == 'RED') {
                $user->identity_verify = 3;
                $this->userSendMailNotify($user, 'reject');
            }
            $user->save();
        }
        return response()->json(['message' => 'Webhook received'], 200);
    }

    public function userSendMailNotify($user, $type)
    {
        if ($type == 'approve') {
            $templateKey = 'KYC_APPROVED';
        } else {
            $templateKey = 'KYC_REJECTED';
        }
        $params = [
            'username' => $user->username ?? null
        ];
        $action = [
            "link" => "#",
            "icon" => "fa-light fa-address-book"
        ];
        $this->sendMailSms($user, $templateKey, $params);
        $this->userPushNotification($user, $templateKey, $params, $action);
        $this->userFirebasePushNotification($user, $templateKey, $params);
        return 0;
    }
}
