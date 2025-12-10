@extends(template().'layouts.user')
@section('title',trans('Verification Form'))
@section('content')
    <section class="listing">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('KYC Verification')</li>
                    </ul>
                    <h4>@lang('KYC Verification')</h4>
                </div>
                <a href="{{ route('user.verification.kyc') }}" class="btn-4"><i class="fas fa-arrow-left pe-1"></i>@lang('Back')</a>
            </div>
        </div>
    </section>
    <div class="container" id="create_ticket">
        <div class="main row d-flex align-items-center justify-content-center">
            <div class="col-lg-11 col-sm-10">
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
                    <div class="search-bar">
                        <div class="card ">
                            <div class="card-header">
                                <h4 class="mb-2">{{ ucwords(str_replace('_', ' ', $kyc->name)) }}</h4>
                                @if($userKyc && $userKyc->status == 2)
                                    <div class="alert alert-danger mt-2 mb-0 p-3 small">
                                        <strong>@lang('Your previous KYC was rejected.')</strong><br>
                                        @lang('Reason') — <span class="text-danger">{{ $userKyc->reason ?? '' }}</span>.<br>
                                        @lang('Please submit valid KYC information.')
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <form action="{{ route('user.kyc.verification.submit') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-4 inner-row-kyc">
                                        <input type="hidden" name="type" value="{{ $kyc->id }}">
                                        @foreach($kyc->input_form as $k => $value)
                                            @if($value->type == "text")
                                                <div class="input-box col-md-12">
                                                    <label for="{{ $value->field_name }}" class="form-label">{{ $value->field_label }}</label>
                                                    <input type="text" class="cmn-input" name="{{ $value->field_name }}" id="{{ $value->field_name }}" placeholder="{{ $value->field_label }}" autocomplete="off"/>
                                                    @if($errors->has($value->field_name))
                                                        <div class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($value->type == "number")
                                                <div class="input-box col-md-12">
                                                    <label for="{{ $value->field_name }}" class="form-label">{{ $value->field_label }}</label>
                                                    <input type="text" class="cmn-input" name="{{ $value->field_name }}" id="{{ $value->field_name }}" placeholder="{{ $value->field_label }}" autocomplete="off"/>
                                                    @if($errors->has($value->field_name))
                                                        <div class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($value->type == "date")
                                                <div class="input-box col-md-12">
                                                    <label for="{{ $value->field_name }}" class="form-label">{{ $value->field_label }}</label>
                                                    <input type="text" class="cmn-input flatpickr-date" name="{{ $value->field_name }}" id="{{ $value->field_name }}" placeholder="{{ $value->field_label }}"/>
                                                    @if($errors->has($value->field_name))
                                                        <div class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($value->type == "textarea")
                                                <div class="input-box col-md-12">
                                                    <label for="{{ $value->field_name }}" class="form-label">{{ $value->field_label }}</label>
                                                    <textarea class="cmn-input" id="{{ $value->field_name }}" cols="30" rows="3" name="{{ $value->field_name }}"></textarea>
                                                    @if($errors->has($value->field_name))
                                                        <div class="error text-danger">@lang($errors->first($value->field_name)) </div>
                                                    @endif
                                                </div>
                                            @endif
                                            @if($value->type == "file")
                                                <div class="input-box col-12">
                                                    <label for="{{ $value->field_name }}" class="form-label">{{ $value->field_label }}</label>
                                                    <div class="custom-image-uploader" data-input-name="{{ $value->field_name }}">
                                                        <input type="file" accept="image/*" id="{{ $value->field_name }}" name="{{ $value->field_name }}" class="cmn-input file-input d-none">
                                                        <div class="upload-box">
                                                            <div class="upload-placeholder">
                                                                <i class="fa fa-upload"></i>
                                                                <p>@lang('Click to upload image')</p>
                                                            </div>
                                                            <img class="image-preview d-none" alt="Preview">
                                                        </div>
                                                        @if($errors->has($value->field_name))
                                                            <div class="error text-danger">@lang($errors->first($value->field_name))</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        <div class="input-box col-12">
                                            <button type="submit" class="btn-3">@lang('Submit')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@include(template().'user.verification_center.partials.form_style')
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

            document.querySelectorAll('.custom-image-uploader').forEach(function (uploader) {
                const input = uploader.querySelector('.file-input');
                const box = uploader.querySelector('.upload-box');
                const preview = uploader.querySelector('.image-preview');
                const placeholder = uploader.querySelector('.upload-placeholder');

                box.addEventListener('click', () => input.click());

                input.addEventListener('change', function () {
                    const file = this.files[0];
                    if (file && file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            preview.src = e.target.result;
                            preview.classList.remove('d-none');
                            placeholder.classList.add('d-none');
                        };
                        reader.readAsDataURL(file);
                    }
                });
            });
        });
    </script>
@endpush
