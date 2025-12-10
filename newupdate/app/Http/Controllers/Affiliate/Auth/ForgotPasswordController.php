<?php

namespace App\Http\Controllers\Affiliate\Auth;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\Content;
use App\Models\ContentDetails;
use App\Models\Page;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    use Notify;

    public function showLinkRequestForm()
    {
        $contentDetails = ContentDetails::with('content')
            ->whereIn('content_id', Content::where('name', 'affiliate_login')->pluck('id'))
            ->get();

        $data['singleContent'] = $contentDetails->filter(function ($item) {
            return $item->content->type === 'single';
        })->first();

        $data['multipleContents'] = $contentDetails->filter(function ($item) {
            return $item->content->type === 'multiple';
        })->values()->map(function ($item) {
            $descArray = is_string($item->description)
                ? json_decode($item->description, true)
                : (array) $item->description;

            return collect($descArray)->merge(['media' => $item->content->media]);
        });

        return view(template().'affiliate.auth.passwords.email', $data);;
    }

    public function submitForgetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:affiliates',
        ]);
        try {
            $token = Str::random(64);
            $resetData = DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now()
            ]);

            $userEmail = $request->email;
            $user = Affiliate::where('email', $userEmail)->first();

            $params = [
                'message' => '<a href="' . url('affiliate/password/reset', $token) . '?email=' . $userEmail . '" target="_blank">Click To Reset Password</a>'
            ];

            $this->mail($user, 'PASSWORD_RESET', $params);

            return back()->with('success', 'We have e-mailed your password reset link!');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
