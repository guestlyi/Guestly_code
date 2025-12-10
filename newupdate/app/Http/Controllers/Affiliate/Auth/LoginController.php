<?php

namespace App\Http\Controllers\Affiliate\Auth;

use App\Http\Controllers\Controller;
use App\Models\BasicControl;
use App\Models\Content;
use App\Models\ContentDetails;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    use RedirectsUsers, ThrottlesLogins, AuthenticatesUsers;

    protected $maxAttempts = 3; // Max login attempts
    protected $decayMinutes = 5; // Lockout time in minutes

    /**
     * Where to redirect affiliate users after login.
     */
    protected $redirectTo = RouteServiceProvider::AFFILIATE;

    /**
     * Show the affiliate login form.
     */
    public function showLoginForm(Request $request)
    {
        $basicControl = BasicControl::first();
        if ($basicControl->affiliate_registration != 1 && $basicControl->affiliate_status != 1){
            return back()->with('warning', 'Affiliate Registration Has Been Disabled.');
        }

        $data['siteKey'] = config('google.recaptcha_site_key');

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

        return view(template() . 'affiliate.auth.login', $data);
    }

    /**
     * Determine if login is using email or username.
     */
    public function username()
    {
        $login = request()->input('username');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $login]);
        return $field;
    }

    /**
     * Handle affiliate login.
     */
    public function login(Request $request)
    {
        $rules[$this->username()] = 'required';
        $rules['password'] = 'required';

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if (basicControl()->manual_recaptcha == 1 && basicControl()->manual_recaptcha_login == 1) {
            $rules['captcha'] = ['required',
                Rule::when((!empty($request->captcha) && strcasecmp(session()->get('captcha'), $_POST['captcha']) != 0), ['confirmed']),
            ];
        }

        if (basicControl()->google_recaptcha_login == 1 && basicControl()->google_recaptcha == 1) {
            GoogleRecaptchaService::responseRecaptcha($request['g-recaptcha-response']);
            $rules['g-recaptcha-response'] = 'sometimes|required';
        }

        $message['captcha.confirmed'] = "The captcha does not match.";
        $validator = Validator::make($request->all(), $rules, $message);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $credentials = [
            $this->username() => $request->input('username'),
            'password' => $request->input('password'),
            'status' => 1
        ];
;
        if (Auth::guard('affiliate')->attempt($credentials)) {
            $user = Auth::guard('affiliate')->user();

            if ($user->status == 0) {
                Auth::guard('affiliate')->logout();
                return redirect()->route('banned')->with('error', 'You are banned from this application. Please contact the system administrator.');
            }
            return $this->sendLoginResponse($request);
        }

        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * After successful login.
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, Auth::guard('affiliate')->user())) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }

    /**
     * Use the affiliate guard.
     */
    protected function guard()
    {
        return Auth::guard('affiliate');
    }

    /**
     * Logout affiliate user.
     */
    public function logout(Request $request)
    {
        Auth::guard('affiliate')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('affiliate.login')->with('success', 'Logged out successfully.');
    }

    /**
     * Post-authentication actions.
     */
    protected function authenticated(Request $request, $user)
    {
        $user->last_login = Carbon::now();
        $user->last_seen = Carbon::now();
        $user->save();
    }

}
