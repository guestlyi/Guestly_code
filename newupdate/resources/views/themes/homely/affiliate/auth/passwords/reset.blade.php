@extends(template().'layouts.app')
@section('title',trans('Password Reset'))
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
                    <div class="col-lg-6 d-flex align-items-center">
                        <div class="sign-in-form-container">
                            <div class="sign-in-logo">
                                <a href="{{ url('/') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="logo"></a>
                            </div>
                            <div class="sign-in-title">
                                <h3>@lang('Enter Email')</h3>
                                <p>@lang('If the provided email is associated with an account, a password reset link will be sent to it').</p>
                            </div>
                            <div class="sign-in-form">
                                <form action="{{ route('affiliate.password.reset.update') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <div class="row g-4">
                                        <div class="col-lg-12">
                                            <label for="email" class="col-md-4 col-form-label">{{ __('E-Mail Address') }}</label>
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-lg-12 mt-0">
                                            <label for="password" class="col-md-4 col-form-label">{{ __('Password') }}</label>
                                            <div class="password-wrapper">
                                                <input type="password" name="password" id="new_password" class="form-control" placeholder="@lang('New Password')">
                                                <button type="button" class="toggle-password" data-target="#new_password">
                                                    <i class="fa-regular fa-eye"></i>
                                                </button>
                                            </div>

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="col-lg-12 mt-0">
                                            <label for="password-confirm" class="col-md-4 col-form-label">{{ __('Confirm Password') }}</label>
                                            <div class="password-wrapper">
                                                <input type="password" name="password_confirmation" id="confirm_password" class="form-control" placeholder="@lang('Confirm Password')">
                                                <button type="button" class="toggle-password" data-target="#confirm_password">
                                                    <i class="fa-regular fa-eye"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sign-in-btn">
                                        <button type="submit" class="btn-1">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    @lang('Send Password Reset Link')
                                                </div>
                                                <div class="hover-text btn-single">
                                                    @lang('Send Password Reset Link')
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
@include(template().'auth.style')
@push('style')
    <style>
        .booking-signin .sign-in-btn button {
            margin-top: 10px !important;
            width: 100%;
            padding: 20px 16px;
            text-transform: capitalize;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            font-size: 18px;
        }
        .password-wrapper {
            position: relative;
        }
        .password-wrapper input {
            padding-right: 40px;
        }
        .password-wrapper .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
            color: #888;
        }
        .password-wrapper .toggle-password:focus {
            outline: none;
        }
    </style>
@endpush

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
        });
    </script>
@endpush
