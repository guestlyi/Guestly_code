@extends(template().'layouts.app')
@section('title',trans('Affiliate Login'))
@section('content')
    <section class="sign-in">
        <div class="bg-layer" style="background: url({{ asset(template(true).'img/signin-bg.png') }});"></div>
        <div class="container">
            <div class="sign-in-container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="sign-in-image text-end">
                            <img src="{{ getFile($singleContent->content->media->image_two->driver, $singleContent->content->media->image_two->path) }}"
                                alt="image">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="sign-in-form-container">
                            <div class="sign-in-logo pb-3">
                                <a href="{{ route('page','/') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="{{ basicControl()->site_title.' logo' }}"></a>
                            </div>
                            <div class="sign-in-title">
                                <h3 class="mb_15">{{ $singleContent->description->title ?? '' }}</h3>
                                <p>{{ $singleContent->description->sub_title ?? '' }}</p>
                            </div>
                            <div class="sign-in-form">
                                <form action="{{ route('affiliate.login.submit') }}" method="post">
                                    @csrf

                                    <div class="radio-inputs w-100">
                                        <label class="radio">
                                            <input value="0" name="account_type" type="radio"/>
                                            <a href="{{ route('login') }}" class="name">@lang('User')</a>
                                        </label>
                                        <label class="radio">
                                            <input value="1" name="account_type" type="radio" checked/>
                                            <a href="{{ route('affiliate.login') }}" class="name">@lang('Affiliate')</a>
                                        </label>
                                    </div>


                                    <div class="form-group">
                                        <label>@lang('Username')</label>
                                        <input type="text" name="username" class="form-control" placeholder="Enter your username..." value="{{ old('username', request()->username) }}">
                                        @error('username')
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
                                        <div class="captcha-box mb-4">
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
                                            <label>{{ $singleContent->description->remember_me_text ?? '' }}</label>
                                        </div>
                                        <div class="rember-password">
                                            <a href="{{ route('affiliate.password.request') }}">@lang('Forget Password?')</a>
                                        </div>
                                    </div>
                                    <div class="sign-in-btn">
                                        <button type="submit" class="btn-1">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    {{ $singleContent->description->button_name ?? '' }}
                                                </div>
                                                <div class="hover-text btn-single">
                                                    {{ $singleContent->description->button_name ?? '' }}
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </form>
                                <div class="media-login">
                                    <div class="signup-account">
                                        <p>@lang("Don’t have an account") ? <a href="{{ route('affiliate.register') }}">@lang('Sign Up')</a></p>
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
@endpush
