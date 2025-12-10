@extends(template().'layouts.app')
@section('title',trans('Reset Password'))
@section('content')
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
                            <h4>@lang('Reset Password')</h4>

                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="sign-in-form">
                                <form action="{{ route('password.update') }}" method="post">
                                    @csrf

                                    <input type="hidden" name="token" value="{{ $token }}">

                                    <div class="sign-in-form-group">
                                        <label for="email" class="col-md-4 col-form-label">{{ __('Email Address') }}</label>
                                        <input id="email" type="email" class="sign-in-input @error('email') is-invalid @enderror" name="email" value="{{ old('email', $email) }}" required autocomplete="off" placeholder="e.g. john@example.com" autofocus>

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="sign-in-form-group">
                                        <label for="password" class="col-md-4 col-form-label">{{ __('New Password') }}</label>
                                        <div class="password-box">
                                            <input type="password" id="password" name="password" class="sign-in-input password" value="{{ old('password') }}" placeholder="@lang('Password...')">
                                            <i class="password-icon fa-regular fa-eye"></i>
                                        </div>
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="sign-in-form-group">
                                        <label for="password_confirmation" class="col-md-4 col-form-label">{{ __('Confirm Password') }}</label>
                                        <div class="password-box">
                                            <input type="password" id="password_confirmation" name="password_confirmation" class="sign-in-input password" value="{{ old('password_confirmation') }}" placeholder="@lang('Confirm Password')">
                                            <i class="password-icon fa-regular fa-eye"></i>
                                        </div>
                                        @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="sign-in-btn">
                                        <button type="submit" class="btn-1">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    @lang('Reset Password')
                                                </div>
                                                <div class="hover-text btn-single">
                                                    @lang('Reset Password')
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function () {
                    const input = document.querySelector(this.dataset.target);
                    const icon = this.querySelector('i');

                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });
        })
    </script>
@endpush
