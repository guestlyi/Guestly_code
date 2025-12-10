@extends(template().'layouts.app')
@section('title',trans('Affiliate Register'))
@section('content')
{{--    <section class="booking-signin py-5">--}}
{{--        <div class="bg-layer" style="background: url({{ getFile($singleContent->content->media->image_one->driver, $singleContent->content->media->image_one->path) }});"></div>--}}
{{--        <div class="signin-image d-none d-lg-block position-relative">--}}
{{--            <img class="position-absolute" src="{{ getFile($singleContent->content->media->image_two->driver, $singleContent->content->media->image_two->path) }}" alt="image">--}}
{{--        </div>--}}
{{--        <div class="container">--}}
{{--            <div class="row d-flex justify-content-center align-items-center">--}}
{{--                <div class="col-lg-7">--}}
{{--                    <div class="sign-in-container">--}}
{{--                        <div class="sign-in-container-inner">--}}
{{--                            <div class="sign-in-logo">--}}
{{--                                <a href="{{ url('/') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo)  }}" alt="logo"></a>--}}
{{--                            </div>--}}
{{--                            <div class="sign-in-title">--}}
{{--                                <h3>{!! $singleContent->description->title ?? '' !!}</h3>--}}
{{--                                <p>{!! $singleContent->description->sub_title ?? '' !!}</p>--}}
{{--                            </div>--}}
{{--                            <div class="booking-signin-form">--}}
{{--                                <form action="{{ route('affiliate.register.submit') }}" method="POST">--}}
{{--                                    @csrf--}}

{{--                                    <div class="row g-4">--}}
{{--                                        <div class="col-lg-12">--}}
{{--                                            <input type="text" name="firstname" class="form-control" placeholder="@lang('Firstname')" value="{{ old('firstname') }}">--}}
{{--                                            @error('firstname')--}}
{{--                                                <span class="invalid-feedback d-block" role="alert">--}}
{{--                                                    <strong>{{ $message }}</strong>--}}
{{--                                                </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                        <div class="col-lg-12">--}}
{{--                                            <input type="text" name="lastname" class="form-control" placeholder="@lang('Lastname')" value="{{ old('lastname') }}">--}}
{{--                                            @error('lastname')--}}
{{--                                                <span class="invalid-feedback d-block" role="alert">--}}
{{--                                                    <strong>{{ $message }}</strong>--}}
{{--                                                </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                        <div class="col-lg-12">--}}
{{--                                            <input type="text" name="username" class="form-control" placeholder="@lang('Username')" value="{{ old('username') }}">--}}
{{--                                            @error('username')--}}
{{--                                                <span class="invalid-feedback d-block" role="alert">--}}
{{--                                                    <strong>{{ $message }}</strong>--}}
{{--                                                </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                        <div class="col-lg-12">--}}
{{--                                            <input type="email" name="email" class="form-control" placeholder="@lang('Email')" value="{{ old('email') }}">--}}
{{--                                            @error('email')--}}
{{--                                                <span class="invalid-feedback d-block" role="alert">--}}
{{--                                                    <strong>{{ $message }}</strong>--}}
{{--                                                </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                        <div class="sign-in-form-name">--}}
{{--                                            <div class="sign-in-form-group w-100 mt-0 mb-4">--}}
{{--                                                <input type="tel" id="telephone2" class="form-control" name="phone" placeholder="Phone...">--}}
{{--                                                <input type="hidden" id="phone_code2" name="phone_code" value="{{ old('phone_code') }}">--}}
{{--                                                @error('phone')--}}
{{--                                                    <span class="invalid-feedback d-block" role="alert">--}}
{{--                                                        <strong>{{ $message }}</strong>--}}
{{--                                                    </span>--}}
{{--                                                @enderror--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        <div class="col-lg-6">--}}
{{--                                            <div class="password-box">--}}
{{--                                                <input type="password" name="password" class="form-control password" placeholder="@lang('Enter Password')" value="{{ old('password') }}">--}}
{{--                                                <i class="password-icon fa-regular fa-eye"></i>--}}
{{--                                            </div>--}}
{{--                                            @error('password')--}}
{{--                                                <span class="invalid-feedback d-block" role="alert">--}}
{{--                                                    <strong>{{ $message }}</strong>--}}
{{--                                                </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}
{{--                                        <div class="col-lg-6">--}}
{{--                                            <div class="password-box">--}}
{{--                                                <input type="password" name="password_confirmation" class="form-control password" placeholder="@lang('Confirm Password')..." value="{{ old('password_confirmation') }}">--}}
{{--                                                <i class="password-icon fa-regular fa-eye"></i>--}}
{{--                                            </div>--}}
{{--                                            @error('password_confirmation')--}}
{{--                                                <span class="invalid-feedback d-block" role="alert">--}}
{{--                                                    <strong>{{ $message }}</strong>--}}
{{--                                                </span>--}}
{{--                                            @enderror--}}
{{--                                        </div>--}}

{{--                                        @if($basicControl->google_recaptcha == 1 && $basicControl->google_recaptcha_register == 1)--}}
{{--                                            <div class="captcha-box mb-4 mt-4">--}}
{{--                                                <div class="captcha-inner">--}}
{{--                                                    <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror"--}}
{{--                                                         data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>--}}
{{--                                                </div>--}}
{{--                                                @error('g-recaptcha-response')--}}
{{--                                                <span class="invalid-feedback d-block">{{ $message }}</span>--}}
{{--                                                @enderror--}}
{{--                                            </div>--}}
{{--                                        @endif--}}

{{--                                        @if($basicControl->manual_recaptcha == 1 && $basicControl->manual_recaptcha_register == 1)--}}
{{--                                            <div class="captcha-box mb-4">--}}
{{--                                                <div class="captcha-inner">--}}
{{--                                                    <input type="text"--}}
{{--                                                           class="sign-in-input @error('captcha') is-invalid @enderror"--}}
{{--                                                           name="captcha" id="captcha" autocomplete="off"--}}
{{--                                                           placeholder="Enter Captcha" required>--}}
{{--                                                    <img src="{{ route('captcha').'?rand='. rand() }}" id="captcha_image" alt="captcha">--}}
{{--                                                    <a href="javascript:refreshCaptcha();" class="refresh-btn">--}}
{{--                                                        <i class="far fa-refresh text-primary"></i>--}}
{{--                                                    </a>--}}
{{--                                                </div>--}}
{{--                                                @error('captcha')--}}
{{--                                                <span class="invalid-feedback d-block">{{ $message }}</span>--}}
{{--                                                @enderror--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                    <div class="rember">--}}
{{--                                        <div class="form-check">--}}
{{--                                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">--}}
{{--                                            <label class="form-check-label" for="flexCheckDefault">--}}
{{--                                                {{ $singleContent->description->remember_me_text ?? '' }}--}}
{{--                                            </label>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="sign-in-btn">--}}
{{--                                        <button type="submit" class="btn-1">--}}
{{--                                            <div class="btn-wrapper">--}}
{{--                                                <div class="main-text btn-single">--}}
{{--                                                    {{ $singleContent->description->button_name ?? 'Create Free Account' }}--}}
{{--                                                </div>--}}
{{--                                                <div class="hover-text btn-single">--}}
{{--                                                    {{ $singleContent->description->button_name ?? 'Create Free Account' }}--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                </form>--}}
{{--                                <div class="media-login">--}}
{{--                                    <div class="signup-account">--}}
{{--                                        <p>@lang('Already have an account?') <a href="{{ route('affiliate.login') }}">@lang('Sign In')</a></p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

    <section class="sign-in">
        <div class="bg-layer" style="background: url({{ asset(template(true).'img/signin-bg.png') }});"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="sign-in-image text-end">
                        <img src="{{ getFile($singleContent->content->media->image_two->driver, $singleContent->content->media->image_two->path) }}" alt="image">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="sign-in-container">
                        <div class="sign-in-container-inner">
                            <div class="sign-in-logo pb-3">
                                <a href="{{ route('page','/') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="{{ basicControl()->site_title.' logo' }}"></a>
                            </div>
                            <div class="sign-in-title mb-0">
                                <h3 class="mb_15">{!! $singleContent->description->title ?? '' !!}</h3>
                                <p class="mb-2">{!! $singleContent->description->sub_title ?? '' !!}</p>
                            </div>
                            <div class="sign-in-form">
                                <form action="{{ route('affiliate.register.submit') }}" method="post" class="php-email-form">
                                    @csrf

                                    <div class="radio-inputs w-100">
                                        <label class="radio">
                                            <input value="0" name="account_type" type="radio" />
                                            <a href="{{ route('register') }}" class="name">@lang('User')</a>
                                        </label>
                                        <label class="radio">
                                            <input value="1" name="account_type" type="radio" checked/>
                                            <a href="{{ route('affiliate.register') }}" class="name">@lang('Affiliate')</a>
                                        </label>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>@lang('First Name')</label>
                                                <input type="text" id="firstname" name="firstname" value="{{ old('firstname') }}" class="form-control" placeholder="@lang('First Name')">
                                                @error('firstname')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>@lang('Last Name')</label>
                                                <input type="text" id="lastname" name="lastname" class="form-control" value="{{ old('lastname') }}" placeholder="@lang('Last Name')">
                                                @error('lastname')
                                                <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>

                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>@lang('User Name')</label>
                                                <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}" placeholder="@lang('Username')">
                                                @error('username')
                                                <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>@lang('Email')</label>
                                                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="@lang('Email Address')">
                                                @error('email')
                                                <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <div class="form-group">
                                                <label>@lang('Phone Number')</label>
                                                <input type="hidden" name="phone_code" id="phoneCode">
                                                <input type="hidden" name="country_code" id="countryCode">
                                                <input type="hidden" name="country" id="countryName">
                                                <input type="text" id="telephone" class="form-control" name="phone"
                                                       placeholder="Enter your phone number">

                                                @error('phone')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>@lang('Password')</label>
                                                <div class="password-box">
                                                    <input type="password" id="password" name="password" class="form-control password-input" value="{{ old('password') }}" placeholder="@lang('Password...')">
                                                    <i class="password-toggle fa-regular fa-eye"></i>
                                                </div>
                                                @error('password')
                                                <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label>@lang('Confirm Password')</label>
                                                <div class="password-box">
                                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control password-input" value="{{ old('password_confirmation') }}" placeholder="@lang('Confirm Password')">
                                                    <i class="password-toggle fa-regular fa-eye"></i>
                                                </div>
                                                <span id="password-match-status" class="text-danger"></span>
                                                @error('password_confirmation')
                                                <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>


                                        @if($basicControl->google_recaptcha == 1 && $basicControl->google_recaptcha_register == 1)
                                            <div class="captcha-box mb-4 mt-4">
                                                <label class="form-label" >@lang('Google Re-captcha')</label>
                                                <div class="captcha-inner">
                                                    <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror"
                                                         data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>
                                                </div>
                                                @error('g-recaptcha-response')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endif

                                        @if($basicControl->manual_recaptcha == 1 && $basicControl->manual_recaptcha_register == 1)
                                            <div class="captcha-box mb-4 mt-4">
                                                <label class="form-label"  for="captcha">@lang('Captcha Code')</label>
                                                <div class="captcha-inner">
                                                    <input type="text"
                                                           class="sign-in-input @error('captcha') is-invalid @enderror"
                                                           name="captcha" id="captcha" autocomplete="off"
                                                           placeholder="Enter Captcha" required>
                                                    <img src="{{ route('captcha').'?rand='. rand() }}" id="captcha_image" alt="captcha">
                                                    <a href="javascript:refreshCaptcha();" class="refresh-btn">
                                                        <i class="far fa-refresh text-primary"></i>
                                                    </a>
                                                </div>
                                                @error('captcha')
                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        @endif

                                    </div>

                                    <div class="sign-in-btn">
                                        <button type="submit" class="btn-1">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    {{ $singleContent->description->button_name ?? 'Create Free Account' }}
                                                </div>
                                                <div class="hover-text btn-single">
                                                    {{ $singleContent->description->button_name ?? 'Create Free Account' }}
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </form>
                                <div class="media-login">
                                    <div class="signup-account">
                                        <p>@lang('Have an account') ? <a href="{{ route('affiliate.login') }}">@lang('Sign In')</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@include(template().'auth.style')
@push('script')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        $(document).ready(function () {
            const passwordToggles = document.querySelectorAll('.password-toggle');

            passwordToggles.forEach(function(toggle) {
                toggle.addEventListener('click', function () {
                    const box = this.closest('.password-box');
                    const input = box ? box.querySelector('input[type="password"], input[type="text"]') : null;

                    if (!input) return;

                    if (input.type === 'password') {
                        input.type = 'text';
                        this.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        this.classList.remove('fa-eye-slash');
                    }
                });
            });
        });

        function refreshCaptcha() {
            let img = document.images['captcha_image'];
            img.src = img.src.substring(
                0, img.src.lastIndexOf("?")
            ) + "?rand=" + Math.random() * 1000;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const input = document.querySelector("#telephone");
            const iti = window.intlTelInput(input, {
                initialCountry: "bd",
                separateDialCode: true,
            });
            input.addEventListener("countrychange", updateCountryInfo);
            updateCountryInfo();

            function updateCountryInfo() {
                const selectedCountryData = iti.getSelectedCountryData();
                const phoneCode = '+' + selectedCountryData.dialCode;
                const countryCode = selectedCountryData.iso2;
                const countryName = selectedCountryData.name;
                $('#phoneCode').val(phoneCode);
                $('#countryCode').val(countryCode);
                $('#countryName').val(countryName);
            }

            const initialPhone = "";
            const initialPhoneCode = "";
            const initialCountryCode = "";
            const initialCountry = "";
            if (initialPhoneCode) {
                iti.setNumber(initialPhoneCode);
            }
            if (initialCountryCode) {
                iti.setNumber(initialCountryCode);
            }
            if (initialCountry) {
                iti.setNumber(initialCountry);
            }
            if (initialPhone) {
                iti.setNumber(initialPhone);
            }

        });
    </script>
@endpush
