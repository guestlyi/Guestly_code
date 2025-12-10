@extends(template().'layouts.affiliate')
@section('title',trans('Personal Information'))
@section('content')
    <section class="profile">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-xl-3">
                    <div class="profile-name">
                        <div class="creat-profile-content">
                            <div class="profile-name-latter">
                                <img id="profilePreview" src="{{ getFile(auth()->user()->image_driver, auth()->user()->image) }}" alt="{{ auth()->user()->firstname.' '.auth()->user()->lastname }}">
                            </div>
                        </div>
                        <div class="profile-name-content">
                            <h3>{{ auth()->user()->firstname.' '.auth()->user()->lastname }}</h3>
                            <h6>@lang('Affiliate')</h6>
                        </div>
                        @php
                            use Carbon\Carbon;

                            $createdAt = Carbon::parse(auth()->user()->created_at);
                            $now = Carbon::now();
                            $diffInSeconds = $createdAt->diffInSeconds($now);
                            $diffInMinutes = $createdAt->diffInMinutes($now);
                            $diffInHours = $createdAt->diffInHours($now);
                            $diffInDays = $createdAt->diffInDays($now);
                            $diffInMonths = round($diffInDays / 30.44, 1);
                            $diffInYears = round($diffInDays / 365.25, 1);

                            if ($diffInSeconds < 60) {
                                $value = $diffInSeconds;
                                $label = 'Second';
                            } elseif ($diffInMinutes < 60) {
                                $value = $diffInMinutes;
                                $label = 'Minute';
                            } elseif ($diffInHours < 24) {
                                $value = $diffInHours;
                                $label = 'Hour';
                            } elseif ($diffInDays < 30) {
                                $value = $diffInDays;
                                $label = 'Day';
                            } elseif ($diffInDays < 365) {
                                $value = $diffInMonths;
                                $label = 'Month';
                            } else {
                                $value = $diffInYears;
                                $label = 'Year';
                            }

                            $value = is_float($value) ? number_format($value, 1) : $value;
                        @endphp

                        <div class="profile-name-bag">
                            <h5>{{ $value }}</h5>
                            <p><span>{{ $label }}{{ $value != 1 ? 's' : '' }} on</span><br> {{ basicControl()->site_title }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-xl-8 offset-xl-1">
                    <div class="creat-profile">
                        <h4 class="creat-profile-title">@lang('Change Password')</h4>
                        <p>@lang("To keep your account secure, first enter your current password. Then choose a strong new password and confirm it to complete the change.")</p>
                        <div class="booking-signin-form">
                            <form action="{{ route('affiliate.profile.update.password') }}" method="POST">
                                @csrf

                                <div class="row g-4 mt-4">
                                    <div class="col-lg-12">
                                        <div class="password-wrapper">
                                            <input type="password" name="current_password" id="current_password" class="form-control" placeholder="@lang('Current Password')">
                                            <button type="button" class="toggle-password" data-target="#current_password">
                                                <i class="fa-regular fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="password-wrapper">
                                            <input type="password" name="new_password" id="new_password" class="form-control" placeholder="@lang('New Password')">
                                            <button type="button" class="toggle-password" data-target="#new_password">
                                                <i class="fa-regular fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="password-wrapper">
                                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="@lang('Confirm Password')">
                                            <button type="button" class="toggle-password" data-target="#confirm_password">
                                                <i class="fa-regular fa-eye"></i>
                                            </button>
                                        </div>
                                        <div id="passwordError" class="error-text d-none">
                                            @lang('Passwords do not match.')
                                        </div>
                                        <div id="passwordSuccess" class="success-text d-none">
                                            @lang('Passwords match.')
                                        </div>
                                    </div>

                                    <div class="col-lg-12 mt-3">
                                        <button type="submit" class="btn-1">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    @lang('Update Password')
                                                </div>
                                                <div class="hover-text btn-single">
                                                    @lang('Update Password')
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    <style>
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
        .error-text {
            color: red;
            font-size: 0.85rem;
            margin-top: 4px;
        }
        .success-text {
            color: green;
            font-size: 0.85rem;
            margin-top: 4px;
        }
    </style>
@endpush
@push('script')
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                @foreach ($errors->all() as $error)
                Notiflix.Notify.failure(@json($error));
                @endforeach
            });
        </script>
    @endif
    <script>
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

        const newPassword = document.getElementById('new_password');
        const confirmPassword = document.getElementById('confirm_password');
        const passwordError = document.getElementById('passwordError');
        const passwordSuccess = document.getElementById('passwordSuccess');
        const form = document.getElementById('passwordForm');

        function validatePasswords() {
            if (newPassword.value && confirmPassword.value) {
                if (newPassword.value !== confirmPassword.value) {
                    passwordError.classList.remove('d-none');
                    passwordSuccess.classList.add('d-none');
                    return false;
                } else {
                    passwordError.classList.add('d-none');
                    passwordSuccess.classList.remove('d-none');
                    return true;
                }
            } else {
                passwordError.classList.add('d-none');
                passwordSuccess.classList.add('d-none');
                return false;
            }
        }

        newPassword.addEventListener('input', validatePasswords);
        confirmPassword.addEventListener('input', validatePasswords);

        form.addEventListener('submit', function (e) {
            if (!validatePasswords()) {
                e.preventDefault();
            }
        });
    </script>
@endpush
