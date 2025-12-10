@extends(template().'layouts.affiliate')
@section('title',trans('Affiliate Kyc'))
@section('content')
    <section class="listing">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area w-100 d-flex align-items-center justify-content-between">
                    <div>
                        <ul>
                            <li><a href="{{ route('affiliate.dashboard') }}">@lang('Dashboard')</a></li>
                            <li><i class="fa-light fa-chevron-right"></i></li>
                            <li>@lang('KYC')</li>
                        </ul>
                        <h4>@lang('Affiliate KYC')</h4>
                    </div>
                    @if (config('services.sumsub.status')) {
                        <a href="{{ route('affiliate.sumsub.kyc.check') }}" class="btn-3">
                            <div class="btn-wrapper">
                                <div class="main-text btn-single">
                                    @lang('Verify With Sumsub')
                                </div>
                                <div class="hover-text btn-single">
                                    @lang('Verify With Sumsub')
                                </div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>
            <div class="container my-4">
                <div class="card p-4">
                    @if($userKyc && $userKyc->status == 0)
                        <div class="kyc-card status-pending text-center p-4 rounded shadow-sm bg-light">
                            <i class="fa-solid fa-hourglass-half text-warning fs-1 mb-3"></i>
                            <h5 class="mb-2 text-warning fw-bold">@lang('Pending Verification')</h5>
                            <p class="mb-0 text-muted">
                                @lang('Your KYC') <span class="fw-semibold text-dark">{{ strtoupper($userKyc->kyc_type) }}</span> @lang('is pending verification').
                            </p>
                            <div class="progress mt-3" style="height: 5px;">
                                <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" style="width: 75%"></div>
                            </div>
                            <small class="text-muted d-block mt-2">
                                <i class="fa fa-circle-info me-1"></i>
                                @lang('Verification typically takes 24–48 hours').
                            </small>
                        </div>
                    @elseif($userKyc && $userKyc->status == 1)
                        <div class="kyc-card status-approved text-center p-4 rounded shadow bg-light border-start border-success border-4">
                            <i class="fa-solid fa-circle-check text-success fs-1 mb-3 animated-check"></i>
                            <h5 class="mb-2 text-success fw-bold">@lang('KYC Verified')</h5>
                            <p class="mb-1 text-muted">
                                @lang('Your KYC')
                                <span class="fw-semibold text-dark">{{ strtoupper($userKyc->kyc_type) }}</span>
                                @lang('has been successfully verified.')
                            </p>
                            <span class="badge bg-success-subtle text-success mt-2 px-3 py-2 rounded-pill">
                            <i class="fa fa-lock-check me-1"></i> @lang('Verified & Secure')
                        </span>
                        </div>
                    @else
                        @if($userKyc && $userKyc->status == 2)
                            <div class="alert alert-danger small">
                                <strong>@lang('Your previous KYC was rejected.')</strong><br>
                                @lang('Reason') — <span class="text-danger">{{ $userKyc->reason ?? '' }}</span>.<br>
                                @lang('Please submit valid KYC information.')
                            </div>
                        @endif
                        <form action="{{ route('affiliate.kyc.verification.submit') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="{{ $kyc->id }}">
                            <div class="row">
                                @foreach($kyc->input_form ?? [] as $value)
                                    @php $field = $value->field_name; @endphp

                                    @if($value->type === 'text' || $value->type === 'number')
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="{{ $field }}" class="form-label">{{ $value->field_label }}</label>
                                                <input type="{{ $value->type === 'number' ? 'number' : 'text' }}"
                                                       class="cmn-input"
                                                       name="{{ $field }}"
                                                       id="{{ $field }}"
                                                       placeholder="{{ $value->field_label }}">
                                                @error($field)
                                                <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @elseif($value->type === 'textarea')
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="{{ $field }}" class="form-label">{{ $value->field_label }}</label>
                                                <textarea name="{{ $field }}" id="{{ $field }}" class="cmn-input" rows="4" placeholder="{{ $value->field_label }}"></textarea>
                                                @error($field)
                                                <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                    @elseif($value->type === 'date')
                                        <div class="col-12">
                                            <div class="mb-3">
                                                <label for="{{ $field }}" class="form-label">{{ $value->field_label }}</label>
                                                <input type="date" name="{{ $field }}" id="{{ $field }}" class="cmn-input">
                                                @error($field)
                                                <div class="text-danger small">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                    @elseif($value->type === 'file')
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label class="form-label d-block">{{ $value->field_label }}</label>
                                                <label class="image-upload-box">
                                                    <div class="upload-content" id="upload_text_{{ $field }}">
                                                        <i class="fa fa-upload fa-2x mb-2 d-block text-muted"></i>
                                                        <span class="text-muted">@lang('Click to upload') {{ $value->field_label }}</span>
                                                    </div>
                                                    <input type="file" name="{{ $field }}" accept="image/*" onchange="previewImage(event, '{{ $field }}')" class="cmn-input">
                                                    <img id="preview_{{ $field }}">
                                                </label>
                                                @error($field)
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <div class="text-start">
                                <button type="submit" class="btn-1">
                                    <div class="btn-wrapper">
                                        <div class="main-text btn-single">
                                            @lang('Submit')
                                        </div>
                                        <div class="hover-text btn-single">
                                            @lang('Submit')
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .personal-info-title{
            margin-bottom: 0!important;
        }
        .card {
            border: none;
            border-radius: 10px;
        }

        .image-upload-box {
            position: relative;
            border: 2px dashed #ccc;
            border-radius: 10px;
            text-align: center;
            cursor: pointer;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .image-upload-box:hover {
            background-color: #f1f1f1;
            border-color: #aaa;
        }

        .image-upload-box input[type="file"] {
            display: none;
        }

        .image-upload-box .upload-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1;
            color: #999;
        }

        .image-upload-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
            border-radius: 10px;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset(template(true).'js/flatpickr.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            flatpickr(".flatpickr-date", {
                dateFormat: "Y-m-d",
                allowInput: true,
                maxDate: "today",
                disableMobile: true
            });
        });

        function previewImage(event, id) {
            const input = event.target;
            const preview = document.getElementById('preview_' + id);
            const uploadText = document.getElementById('upload_text_' + id);

            if (input.files && input.files[0]) {
                preview.src = URL.createObjectURL(input.files[0]);
                preview.style.display = 'block';
                if (uploadText) uploadText.style.display = 'none';
            }
        }
    </script>
@endpush
