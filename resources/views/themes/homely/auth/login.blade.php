@extends(template().'layouts.app')
@section('title',trans('Login'))
@section('content')
<style>
    .iti--hide {
        display: none !important;
    }
</style>

    @php
        $logInContent = logInContent();
        $socialData = getSocialData();

        $media = $logInContent['single']->content->media;
        $imagePath = $media->image->path ?? null;
        $imageDriver = $media->image->driver ?? null;
    @endphp
    <section class="sign-in">
        <div class="bg-layer"
             style="background: url({{ asset(template(true).'img/signin-bg.png') }});"></div>
        <div class="container">
            <div class="sign-in-container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="sign-in-image text-end">
                            <img
                                src="{{ getFile($imageDriver, $imagePath) }}"
                                alt="image">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="sign-in-form-container">
                            <div class="sign-in-logo pb-3">
                                <a href="{{ route('page','/') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="{{ basicControl()->site_title.' logo' }}"></a>
                            </div>
                            <div class="sign-in-title">
                                <h3 class="mb_15">{{ $logInContent['single']['description']->title ?? ''  }}{{ ' '.basicControl()->site_title }}</h3>
                                <p>{{ $logInContent['single']['description']->sub_title ?? '' }}</p>
                            </div>
                            <div class="sign-in-form">
                                <form action="{{ route('login') }}" method="post">
                                    @csrf

                                    <div class="radio-inputs w-100">
                                        <label class="radio">
                                            <input value="0" name="account_type" type="radio" checked />
                                            <a href="{{ route('login') }}" class="name">@lang('User')</a>
                                        </label>
                                        <label class="radio">
                                            <input value="1" name="account_type" type="radio" />
                                            <a href="{{ route('affiliate.login') }}" class="name">@lang('Affiliate')</a>
                                        </label>
                                    </div>


                                    <div class="form-group">
                                        <label>@lang('Mobile/Email')</label>
                                    
                                        <input type="hidden" name="phone_code" id="phoneCode">
                                        <input type="hidden" name="country_code" id="countryCode">
                                        <input type="hidden" name="country_name" id="countryName">
                                    
                                        <input type="text" id="telephone" class="form-control" name="username"
                                               placeholder="Enter mobile number or email">
                                        
                                        @error('username')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        @error('phone')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        @error('email')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>



                                    <div class="form-group">
                                        <label>@lang('Password')</label>
                                        <div class="password-box">
                                            <input type="password" name="password" class="form-control password" placeholder="Password..." value="{{ old('username', request()->password) }}">
                                            <i class="password-icon fa-regular fa-eye"></i>
                                        </div>
                                        @error('password')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>

                                    @if($basicControl->google_recaptcha == 1 && $basicControl->google_recaptcha_login == 1)
                                        <div class="captcha-box mb-4 mt-4">
                                            <div class="captcha-inner">
                                                <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror"
                                                     data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>
                                            </div>
                                            @error('g-recaptcha-response')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endif

                                    @if($basicControl->manual_recaptcha == 1 && $basicControl->manual_recaptcha_login == 1)
                                        <div class="captcha-box mb-4 mt-4">
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

                                    <div class="rember">
                                        <div class="form-check">
                                            <input class="form-check-input" name="remember" type="checkbox">
                                            <label>@lang($logInContent['single']['description']->remember_me_text ?? '')</label>
                                        </div>
                                        <div class="rember-password">
                                            <a href="{{ route('password.request') }}">@lang('Forget Password?')</a>
                                        </div>
                                    </div>
                                    <div class="sign-in-btn">
                                        <button type="submit" class="btn-1">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    {{ $logInContent['single']['description']->button_name ?? ''  }}
                                                </div>
                                                <div class="hover-text btn-single">
                                                    {{ $logInContent['single']['description']->button_name ?? ''  }}
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </form>
                                <div class="media-login">
                                    @if(config('socialite.google_status') || config('socialite.facebook_status') || config('socialite.github_status'))
                                    <div class="media-login-border"><h5>OR</h5></div>
                                        <ul>
                                            @if(config('socialite.facebook_status'))
                                                <li><a href="{{ route('socialiteLogin','facebook') }}"><img src="{{ asset(template(true).'img/icons/facebook.png') }}" alt="icon"/></a></li>
                                            @endif
                                            @if(config('socialite.google_status'))
                                                <li><a href="{{ route('socialiteLogin','google') }}"><img src="{{ asset(template(true).'img/icons/google.png') }}" alt="icon"/></a></li>
                                            @endif
                                            @if(config('socialite.instagram_status'))
                                                <li><a href="{{ route('socialiteLogin','instagram') }}"><img src="{{ asset(template(true).'img/icons/instagram.png') }}" alt="icon"/></a></li>
                                            @endif

                                        </ul>
                                    @endif
                                    <div class="signup-account">
                                        <p>@lang("Don’t have an account") ? <a href="{{ route('register') }}">@lang('Sign Up')</a></p>
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
        function refreshCaptcha() {
            let img = document.images['captcha_image'];
            img.src = img.src.substring(
                0, img.src.lastIndexOf("?")
            ) + "?rand=" + Math.random() * 1000;
        }
        
        
    </script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const input = document.querySelector("#telephone");
    const phoneCode = document.querySelector("#phoneCode");
    const countryCode = document.querySelector("#countryCode");
    const countryName = document.querySelector("#countryName");

    let iti = null;

    function enableTelInput() {
        if (!iti) {
            iti = window.intlTelInput(input, {
                initialCountry: "in",
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
            });
        }
    }

    function disableTelInput() {
        if (iti) {
            iti.destroy();
            iti = null;
        }
        phoneCode.value = "";
        countryCode.value = "";
        countryName.value = "";
    }

    function updateTelData() {
        if (iti) {
            const data = iti.getSelectedCountryData();
            phoneCode.value = "+" + data.dialCode;
            countryCode.value = data.iso2;
            countryName.value = data.name;
        }
    }

    input.addEventListener("input", function () {
        const value = input.value.trim();

        if (value === "") {
            // empty input → reset everything
            disableTelInput();
            return;
        }

        // If input contains ANY non-digit → treat as email
        if (/[^0-9]/.test(value)) {
            disableTelInput();
        } else {
            // If input is only numbers → treat as phone
            enableTelInput();
            updateTelData();
        }
    });

    // Update country data whenever user changes country manually
    input.addEventListener("countrychange", function () {
        updateTelData();
    });

});
</script>


@endpush

