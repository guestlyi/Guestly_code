@extends(template().'layouts.user')
@section('title',trans('User Profile'))
@section('content')
    <section class="profile createProfile">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="profile-name">
                        <div class="creat-profile-content">
                            <div class="profile-name-latter">
                                <img id="profilePreview" src="{{ getFile(auth()->user()->image_driver, auth()->user()->image) }}" alt="{{ auth()->user()->firstname.' '.auth()->user()->lastname }}">
                            </div>
                            <div class="creat-profile-input">
                                <div class="photo-input">
                                    <label for="fileUpload" class="btn-1"><i class="fa-light fa-camera-retro"></i></label>
                                    <input type="file" class="imageInput" id="fileUpload" accept="image/*" multiple="">
                                </div>
                            </div>
                        </div>
                        <div class="profile-name-content">
                            <h3>{{ auth()->user()->firstname.' '.auth()->user()->lastname }}</h3>
                            <h6>{{ auth()->user()->role == 1 ? 'Host' : 'Guest' }}</h6>
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
                    <div class="personal-info-sidebar">
                        <div class="personal-info-sidebar-content">
                            <h5>{{ auth()->user()->firstname.' '.auth()->user()->lastname }} @lang('Confirmed information') </h5>
                            <p><i class="{{ auth()->user()->email_verification == 1 ? 'fa-regular fa-check' : 'fa-regular fa-times text-danger' }}"></i> @lang('Email Address')</p>
                        </div>
                        <div class="personal-info-sidebar-content">
                            <h5>@lang('Identity Verification')</h5>
                            <p>@lang('Verify your identity to increase account security and unlock all features. This helps keep your account safe and trusted.')</p>

                            @if($missing != false)
                                <a href="{{ route('user.verification.kyc') }}" class="btn-1 mt_20">@lang('Verify Now')</a>
                            @else
                                <span class="verified-badge">@lang('Verified')</span>
                            @endif
                        </div>
                        <div class="personal-info-sidebar-content">
                            <h5>@lang('Two-Factor Authentication')</h5>
                            <p>@lang('Add an extra layer of security to your account by enabling two-factor authentication. Protect your account even if your password is compromised.')</p>
                            <a href="{{ route('user.twostep.security') }}" class="btn-1 mt_20">@lang('Manage 2FA')</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 d-flex justify-content-center align-items-center contentProfile">
                    <div class="profile-container text-center">
                        <div class="profile-container-inner">
                            <h5>@lang("It's time to create your profile")</h5>
                            <p>@lang('Contact info and personal details can be edited. If this info was used to verify your identity,')</p>
                            <a href="{{ route('user.personalCreate') }}" class="btn-1 mt_20">@lang('Manage Profile')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    <style>
        .creat-profile-input .photo-input .btn-1 {
            padding: 5px 9px !important;
        }
        .verified-badge {
            display: inline-flex;
            align-items: center;
            padding: 10px 18px;
            background: linear-gradient(to right, #4CAF50, #2E7D32);
            color: white;
            font-weight: 700;
            font-size: 0.95rem;
            border-radius: 50px;
            position: relative;
            transition: all 0.3s ease;
            margin-top: 13px;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            overflow: hidden;
        }

        .verified-badge::before {
            content: '✓';
            display: inline-block;
            margin-right: 8px;
            font-size: 1.1rem;
            font-weight: bold;
            animation: pulse 2s infinite;
        }

        .verified-badge::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(
                to bottom right,
                rgba(255, 255, 255, 0.2),
                rgba(255, 255, 255, 0)
            );
            transform: rotate(30deg);
            transition: all 0.5s ease;
        }

        .verified-badge:hover {
            background: linear-gradient(to right, #43A047, #1B5E20);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        .verified-badge:hover::after {
            transform: rotate(30deg) translate(10%, 10%);
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    </style>
@endpush
@push('script')
    <script>
        $('#fileUpload').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                $('#profilePreview').attr('src', e.target.result);
            };
            reader.readAsDataURL(file);

            let formData = new FormData();
            formData.append('image', file);

            $.ajax({
                url: '{{ route("user.profile.update.image") }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    Notiflix.Loading.standard('Uploading...');
                },
                success: function(response) {
                    Notiflix.Loading.remove();
                    Notiflix.Notify.success(response.message);
                    if (response.image_url) {
                        $('#profilePreview').attr('src', response.image_url);
                    }
                },
                error: function(xhr) {
                    Notiflix.Loading.remove();
                    let errorMsg = 'Upload failed.';
                    if (xhr.responseJSON?.errors?.image) {
                        errorMsg = xhr.responseJSON.errors.image[0];
                    }
                    Notiflix.Notify.failure(errorMsg);
                }
            });
        });
    </script>
@endpush

