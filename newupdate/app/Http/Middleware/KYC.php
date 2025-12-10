<?php

namespace App\Http\Middleware;

use App\Models\Kyc as KYCModel;
use App\Models\UserKyc;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class KYC
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (basicControl()->isKycMandatory == 0) {
            return $next($request);
        }

        if (auth('web')->check() && config('services.sumsub.status') && auth()->user()->identity_verify != 2) {
            return redirect()->route('user.verification.kyc')
                ->with('error', 'Please submit KYC information to access all the resources');
        }

        $kycTypes = KYCModel::where('status', 1)
            ->where('apply_for', '!=', 1)
            ->pluck('id');

        if ($kycTypes->isNotEmpty()) {
            $userKyc = UserKyc::where('user_id', Auth::id())
                ->whereIn('kyc_id', $kycTypes)
                ->latest()
                ->get()
                ->unique('kyc_id')
                ->values();

            $userKycIds   = $userKyc->pluck('kyc_id')->toArray();
            $missingKyc   = array_diff($kycTypes->toArray(), $userKycIds);

            if (!empty($missingKyc)) {
                return redirect()->route('user.verification.kyc')
                    ->with('error', 'Please submit KYC information to access all the resources');
            }

            $statuses = $userKyc->pluck('status');
            if (!$statuses->every(fn($status) => $status == 1)) {
                return redirect()->route('user.verification.kyc')
                    ->with('error', 'Your KYC is not approved yet');
            }
        }

        return $next($request);
    }


}
