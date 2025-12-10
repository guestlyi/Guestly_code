<?php

namespace App\Http\Controllers\Affiliate\Auth;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\Content;
use App\Models\ContentDetails;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
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

        return view(template().'affiliate.auth.passwords.reset', $data)->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->withErrors(['email' => 'Invalid token or email. Please try the password reset link again.']);
        }

        $affiliate = Affiliate::where('email', $request->email)->first();

        if (!$affiliate) {
            return back()->withErrors(['email' => 'No affiliate found with this email.']);
        }

        $affiliate->password = Hash::make($request->password);
        $affiliate->setRememberToken(Str::random(60));
        $affiliate->save();

        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect(route('affiliate.login'))->with('status', 'Password reset successful. You can now log in.');
    }
}
