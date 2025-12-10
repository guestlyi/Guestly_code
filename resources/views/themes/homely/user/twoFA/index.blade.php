@extends(template().'layouts.user')
@section('title',__('Two Step Security'))

@section('content')
    <section class="two-factor-auth">
        <div class="container mb-5">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('Two Step Security')</li>
                    </ul>
                    <h4>@lang('Two Step Security')</h4>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="auth-card card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="card-title">
                                <i class="fas fa-shield-alt me-2"></i>
                                @lang("Two Factor Authenticator")
                            </h3>

                            <button class="btn-3" data-bs-toggle="modal" data-bs-target="#regenerateModal">
                                <div class="btn-wrapper">
                                    <div class="main-text btn-single">
                                        @lang("Regenerate")
                                    </div>
                                    <div class="hover-text btn-single">
                                        @lang("Regenerate")
                                    </div>
                                </div>
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="auth-code-box">
                                <label class="form-label">@lang('Secret Code')</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" value="{{$secret}}" id="secretCode" readonly>
                                    <button id="copyBtn" onclick="copySecretCode()" class="btn btn-primary">
                                        <i class="fas fa-copy"></i> @lang('Copy')
                                    </button>
                                </div>
                            </div>

                            <div class="qr-code-container text-center my-4">
                                <div class="qr-code-wrapper">
                                    <img class="qr-code-img" src="{{$qrCodeUrl}}">
                                </div>
                                <p class="text-muted mt-2">@lang('Scan this QR code with your authenticator app')</p>
                            </div>

                            <div class="d-grid gap-2">
                                @if(auth()->user()->two_fa == 1)
                                    <button class="btn-1" data-bs-toggle="modal" data-bs-target="#disableModal">
                                        <div class="btn-wrapper">
                                            <div class="main-text btn-single">
                                                <i class="fas fa-lock-open me-2"></i> @lang('Disable Two Factor Authenticator')
                                            </div>
                                            <div class="hover-text btn-single">
                                                <i class="fas fa-lock-open me-2"></i> @lang('Disable Two Factor Authenticator')
                                            </div>
                                        </div>
                                    </button>
                                @else
                                    <button class="btn-1" data-bs-toggle="modal" data-bs-target="#enableModal">
                                        <div class="btn-wrapper">
                                            <div class="main-text btn-single">
                                                <i class="fas fa-lock me-2"></i> @lang('Enable Two Factor Authenticator')
                                            </div>
                                            <div class="hover-text btn-single">
                                                <i class="fas fa-lock me-2"></i> @lang('Enable Two Factor Authenticator')
                                            </div>
                                        </div>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Instructions Card -->
                <div class="col-lg-6">
                    <div class="info-card card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle me-2"></i>
                                @lang('Setup Instructions')
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="step">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h5>@lang('Install Authenticator App')</h5>
                                    <p>@lang('Download and install Google Authenticator or similar app from your app store.')</p>
                                    <a class="btn btn-dark mt-2" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2&hl=en" target="_blank">
                                        <i class="fab fa-google-play me-2"></i> @lang('Get on Google Play')
                                    </a>
                                    <a class="btn btn-dark mt-2" href="https://apps.apple.com/us/app/google-authenticator/id388497605" target="_blank">
                                        <i class="fab fa-apple me-2"></i> @lang('Download on App Store')
                                    </a>
                                </div>
                            </div>

                            <div class="step">
                                <div class="step-number">2</div>
                                <div class="step-content">
                                    <h5>@lang('Add Your Account')</h5>
                                    <p>@lang('Open the app and either:')</p>
                                    <ul class="list-unstyled">
                                        <li><i class="fas fa-qrcode me-2"></i> @lang('Scan the QR code')</li>
                                        <li class="mt-2"><i class="fas fa-key me-2"></i> @lang('Or enter the secret key manually')</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="step">
                                <div class="step-number">3</div>
                                <div class="step-content">
                                    <h5>@lang('Verify and Complete')</h5>
                                    <p>@lang('Enter the 6-digit code generated by the app to complete setup.')</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enable Modal -->
    <div class="modal fade user-modal" id="enableModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">@lang('Verify Your OTP')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{route('user.twoStepEnable')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="input-box col-md-12">
                            <input type="hidden" name="key" value="{{$secret}}">
                            <input type="text" class="form-control" name="code"
                                   placeholder="@lang('Enter Google Authenticator Code')" autocomplete="off">
                        </div>
                    </div>
                    <div class="modal-footer bx-shadow-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Verify')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Disable Modal -->
    <div class="modal fade" id="disableModal" tabindex="-1" aria-labelledby="disableModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="planModalLabel">@lang('Verify Your OTP to Disable')</h4>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="{{route('user.twoStepDisable')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-4">
                            <div class="input-box col-12">
                                <input type="text" class="form-control" name="password" placeholder="@lang('Enter Your Password')" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bx-shadow-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('Verify')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="regenerateModal" tabindex="-1" aria-labelledby="regenerateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="regenerateModalLabel">@lang('Re-generate Confirmation')</h4>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="{{route('user.twoStepRegenerate')}}" method="POST">
                    @csrf
                    <div class="modal-body">
                        @lang("Are you want to Re-generate Authenticator?")
                    </div>
                    <div class="modal-footer bx-shadow-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('No')</button>
                        <button class="btn btn-primary" type="submit">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <style>
        .two-factor-auth {
            padding: 2rem 0;
        }

        .breadcrumb-nav {
            margin-bottom: 2rem;
        }

        .page-title {
            font-weight: 600;
            color: #2c3e50;
            margin-top: 1rem;
        }

        .auth-card, .info-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            height: 100%;
        }

        .card-header {
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 1.5rem;
        }

        .card-title {
            font-weight: 600;
            margin: 0;
            font-size: 1.1rem;
        }

        .info-card  .card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 24px;

        }
        .step {
            display: flex;
            gap: 1rem;
        }

        .step-number {
            width: 32px;
            height: 32px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            flex-shrink: 0;
        }

        .step-content h5 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .btn-custom {
            transition: all 0.2s;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
        }

        .auth-code-box label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }
        @media only screen and (max-width: 767px){
            .auth-card .card-header{
                flex-direction: column;
                align-items: flex-start !important;
            }
            .auth-card .btn-1 {
                line-height: 18px;
            }
            .step {
                flex-direction: column;
            }
            .info-card .card-body {
                gap: 38px;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        function copySecretCode() {
            var copyText = document.getElementById("secretCode");

            copyText.select();
            copyText.setSelectionRange(0, 99999);

            navigator.clipboard.writeText(copyText.value)
                .then(() => {
                    Notiflix.Notify.Success(`@lang('Copied:') ${copyText.value}`);
                })
                .catch(err => {
                    try {
                        document.execCommand("copy");
                        Notiflix.Notify.Success(`@lang('Copied:') ${copyText.value}`);
                    } catch (e) {
                        Notiflix.Notify.Failure(`@lang('Failed to copy text')`);
                        console.error('Failed to copy text: ', err);
                    }
                });
        }
    </script>
@endpush

