<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="loginModalLabel">{{ $logInContent['single']['description']->modal_title ?? ''  }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="login-modal-container">
                    <div class="login-modal-icon">
                        <a href="{{ route('page','/') }}"><img src="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" alt="icon"></a>
                    </div>
                    <h4>{{ $logInContent['single']['description']->title ?? ''  }}</h4>
                    <div class="sign-in-form">
                        <form action="{{ route('login') }}" method="post">
                            @csrf

                            <div class="sign-in-form-group">
                                <input type="text" name="username" class="sign-in-input" placeholder="Enter your username...">
                                @error('username')
                                <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>
                            <div class="sign-in-form-group">
                                <div class="password-box">
                                    <input type="password" name="password" class="sign-in-input password" placeholder="Password...">
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
                                        <div class="g-recaptcha @error('g-recaptcha-response') is-invalid @enderror" data-sitekey="{{ env('GOOGLE_RECAPTCHA_SITE_KEY') }}"></div>
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
                                               name="captcha" id="login_captcha" autocomplete="off"
                                               placeholder="Enter Captcha" required>
                                        <img src="{{ route('captcha').'?rand='. rand() }}" id="captcha_image_login" alt="captcha">
                                        <a href="javascript:refreshCaptcha('captcha_image_login');" class="refresh-btn">
                                            <i class="far fa-refresh text-primary"></i>
                                        </a>
                                    </div>
                                    @error('captcha')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <div class="row remberCheck">
                                <div class="col-12">
                                    <div class="remember d-flex flex-row align-items-center justify-content-between">
                                        <div class="form-check d-flex flex-row align-items-center gap-2">
                                            <input class="form-check-input" type="checkbox" name="remember" id="agreeCheck">
                                            <label for="agreeCheck" class="form-check-label">
                                                @lang($logInContent['single']['description']->remember_me_text ?? '')
                                            </label>
                                        </div>
                                        <div class="rember-password">
                                            <a href="{{ route('password.request') }}">@lang('Forget Password')?</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sign-in-btn">
                                <button type="submit" class="btn-1">{{ $logInContent['single']['description']->button_name ?? ''  }}</button>
                            </div>
                        </form>
                        @if(config('socialite.google_status') || config('socialite.facebook_status') || config('socialite.github_status'))
                            <div class="media-login">
                                <div class="media-login-border"><h5>{{ $logInContent['single']['description']->border_text ?? ''  }}</h5></div>
                                <ul>
                                    @if(config('socialite.facebook_status'))
                                        <li><a href="{{route('socialiteLogin','facebook')}}"><img src="{{ asset(template(true).'img/icons/facebook.png') }}" alt="icon"></a></li>
                                    @endif
                                    @if(config('socialite.google_status'))
                                        <li><a href="{{route('socialiteLogin','google')}}"><img src="{{ asset(template(true).'img/icons/google.png') }}" alt="icon"></a></li>
                                    @endif
                                    @if(config('socialite.github_status'))
                                        <li><a href="{{route('socialiteLogin','github')}}"><img class="socialGitImage" src="{{ asset(template(true).'img/icons/github.png') }}" alt="icon"></a></li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="signinModal" tabindex="-1" aria-labelledby="signinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="signinModalLabel">{{ $signInContent['single']['description']->modal_title ?? '' }}</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="signin-modal-container">
                    <div class="login-modal-icon">
                        <a href="{{ route('page','/') }}"><img src="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" alt="icon"></a>
                    </div>
                    <h4>{{ $signInContent['single']['description']->title ?? '' }}</h4>
                    <div class="sign-in-form">
                        <form action="{{ route('register') }}" method="post">
                            @csrf
                            <div class="sign-in-form-name">
                                <div class="sign-in-form-group">
                                    <label for="firstname" class="form-label">@lang('Firstname')</label>
                                    <input type="text" id="firstname" name="firstname" class="sign-in-input" placeholder="@lang('Enter your first name...')">
                                    @error('firstname')
                                    <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="sign-in-form-group">
                                    <label for="lastname" class="form-label">@lang('Lastname')</label>
                                    <input type="text" id="lastname" name="lastname" class="sign-in-input" placeholder="@lang('Enter your last name...')">
                                    @error('lastname')
                                    <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="sign-in-form-name">
                                <div class="sign-in-form-group">
                                    <label for="username" class="form-label">@lang('Username')</label>
                                    <input type="text" id="username" name="username" class="sign-in-input" placeholder="@lang('Enter your username...')">
                                    @error('username')
                                    <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="sign-in-form-group">
                                    <label for="email" class="form-label">@lang('Email')</label>
                                    <input type="email" id="email" name="email" class="sign-in-input" placeholder="@lang('Enter your email...')">
                                    @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="sign-in-form-name">
                                <div class="sign-in-form-group register-form w-100">
                                    <label for="telephone" class="form-label">@lang('Phone')</label>
                                    <input type="tel" id="telephone" class="sign-in-input" name="phone" placeholder="Phone...">
                                    <input type="hidden" id="phone_code" name="phone_code">
                                </div>
                            </div>
                            <div class="sign-in-form-name">
                                <div class="sign-in-form-group">
                                    <label>@lang('Password')</label>
                                    <div class="password-box">
                                        <input type="password" id="password" name="password" class="sign-in-input password" placeholder="@lang('Password...')">
                                        <i class="password-icon fa-regular fa-eye"></i>
                                    </div>
                                    @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="sign-in-form-group">
                                    <label>@lang('Confirm Password')</label>
                                    <div class="password-box">
                                        <input type="password" id="password_confirmation" name="password_confirmation" class="sign-in-input password" placeholder="@lang('Confirm Password')">
                                        <i class="password-icon fa-regular fa-eye"></i>
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
                                    <label class="form-label" for="captcha">@lang('Captcha Code')</label>
                                    <div class="captcha-inner">
                                        <input type="text"
                                               class="sign-in-input @error('captcha') is-invalid @enderror"
                                               name="captcha" id="register_captcha" autocomplete="off"
                                               placeholder="Enter Captcha" required>
                                        <img src="{{ route('captcha').'?rand='. rand() }}" id="captcha_image_register" alt="captcha">
                                        <a href="javascript:refreshCaptcha('captcha_image_register');" class="refresh-btn">
                                            <i class="far fa-refresh text-primary"></i>
                                        </a>
                                    </div>
                                    @error('captcha')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endif

                            <div class="row remberCheck">
                                <div class="col-md-6">
                                    <div class="remember">
                                        <div class="form-check d-flex flex-row align-items-center gap-2">
                                            <input class="form-check-input" type="checkbox" name="remember" id="agreeCheck">
                                            <label for="agreeCheck" class="form-check-label">
                                                @lang($signInContent['single']['description']->agree_terms_text ?? '')
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="sign-in-btn">
                                <button type="submit" id="submit-btn" class="btn-1">
                                    {{ $signInContent['single']['description']->button_name ?? 'Sign In' }}
                                </button>
                            </div>
                        </form>

                        @if(config('socialite.google_status') || config('socialite.facebook_status') || config('socialite.github_status'))
                            <div class="media-login">
                                <div class="media-login-border"><h5>{{ $signInContent['single']['description']->border_text ?? ''  }}</h5></div>
                                <ul>
                                    @if(config('socialite.facebook_status'))
                                        <li><a href="{{route('socialiteLogin','facebook')}}"><img src="{{ asset(template(true).'img/icons/facebook.png') }}" alt="icon"></a></li>
                                    @endif
                                    @if(config('socialite.google_status'))
                                        <li><a href="{{route('socialiteLogin','google')}}"><img src="{{ asset(template(true).'img/icons/google.png') }}" alt="icon"></a></li>
                                    @endif
                                    @if(config('socialite.github_status'))
                                        <li><a href="{{route('socialiteLogin','github')}}"><img class="socialGitImage" src="{{ asset(template(true).'img/icons/github.png') }}" alt="icon"></a></li>
                                    @endif
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .iti.iti--allow-dropdown.iti--separate-dial-code.iti--show-flags{
            width: 100%;
        }
        .remberCheck{
            padding: 18px 0 0 26px;
        }
    </style>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        function refreshCaptcha(imageId) {
            let img = document.getElementById(imageId);
            if (img) {
                img.src = img.src.split("?")[0] + "?rand=" + Math.random() * 1000;
            }
        }
        document.addEventListener("DOMContentLoaded", function () {
            const input = document.querySelector("#telephone");
            const intCountry = '{{ basicControl()->country }}';

            if (input) {
                const iti = window.intlTelInput(input, {
                    initialCountry: intCountry,
                    separateDialCode: true,
                });

                function updatePhoneCode() {
                    const countryData = iti.getSelectedCountryData();
                    document.querySelector("#phone_code").value = countryData.dialCode;
                }

                updatePhoneCode();

                input.addEventListener("countrychange", updatePhoneCode);
            }
        });
        document.addEventListener("DOMContentLoaded", function () {
            const password = document.getElementById("password");
            const confirmPassword = document.getElementById("password_confirmation");
            const statusText = document.getElementById("password-match-status");

            function checkPasswordMatch() {
                const passValue = password.value.trim();
                const confirmPassValue = confirmPassword.value.trim();

                if (confirmPassValue === "") {
                    statusText.textContent = "";
                    return;
                }

                if (passValue !== "" && passValue === confirmPassValue) {
                    statusText.textContent = "Passwords match";
                    statusText.classList.remove("text-danger");
                    statusText.classList.add("text-success");
                } else {
                    statusText.textContent = "Passwords do not match";
                    statusText.classList.remove("text-success");
                    statusText.classList.add("text-danger");
                }
            }

            password.addEventListener("input", checkPasswordMatch);
            confirmPassword.addEventListener("input", checkPasswordMatch);
        });
    </script>
</div>
