<?php

namespace App\Http\Controllers\Affiliate\Auth;

use App\Http\Controllers\Controller;
use App\Models\Affiliate;
use App\Models\BasicControl;
use App\Models\Content;
use App\Models\ContentDetails;
use App\Rules\PhoneLength;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;


class RegisterController extends Controller
{
    use RedirectsUsers;

    protected $maxAttempts = 3;
    protected $decayMinutes = 5;
    protected $redirectTo = '/affiliate/dashboard';
    public function showRegistrationForm()
    {

        $basicControl = BasicControl::first();
        if ($basicControl->affiliate_registration != 1 && $basicControl->affiliate_status != 1){
            return back()->with('warning', 'Affiliate Registration Has Been Disabled.');
        }

        if ($basicControl->affiliate_registration == 0) {
            return redirect('/')->with('warning', 'Affiliate Registration Has Been Disabled.');
        }
        $contentDetails = ContentDetails::with('content')
            ->whereIn('content_id', Content::where('name', 'affiliate_register')->pluck('id'))
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


        return view(template() . 'affiliate.auth.register', $data);
    }

    protected function validator(array $data)
    {
        $basicControl = basicControl();
        $phoneLength = 15;
        foreach (config('country') as $country) {
            if ($country['phone_code'] == $data['phone_code']) {
                $phoneLength = $country['phoneLength'];
                break;
            }
        }

        if ($basicControl->strong_password == 0) {
            $rules['password'] = ['required', 'min:6', 'confirmed'];
        } else {
            $rules['password'] = ["required", 'confirmed',
                Password::min(6)->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised()];
        }

        if (basicControl()->reCaptcha_status_registration) {
            $rules['g-recaptcha-response'] = ['sometimes', 'required'];
        }

        $rules['firstname'] = ['required', 'string', 'max:91'];
        $rules['lastname'] = ['required', 'string', 'max:91'];
        $rules['username'] = ['required', 'alpha_dash', 'min:5', 'unique:users,username'];
        $rules['email'] = ['required', 'string', 'email', 'max:255',  'unique:users,email'];
        $rules['phone'] = ['required', 'string', 'unique:users,phone', new PhoneLength($data['phone_code'])];
        $rules['phone_code'] = ['required', 'string', 'max:15'];
        return Validator::make($data, $rules, [
            'firstname.required' => 'First Name Field is required',
            'lastname.required' => 'Last Name Field is required',
            'g-recaptcha-response.required' => 'The reCAPTCHA field is required.',
        ]);
    }

    protected function create(array $data)
    {
        return Affiliate::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_code' => $data['phone_code'],
            'phone' => $data['phone'],
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $affiliate = $this->create($request->all());

        if ($response = $this->registered($request, $affiliate)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    protected function registered(Request $request, $affiliate)
    {
        $affiliate->last_login = Carbon::now();
        $affiliate->last_seen = Carbon::now();
        $affiliate->save();

        Auth::guard('affiliate')->login($affiliate);

        return redirect($this->redirectPath());
    }


}
