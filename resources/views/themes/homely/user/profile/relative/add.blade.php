@extends(template().'layouts.user')
@section('title',trans('Relative Add'))
@section('content')
    <div class="row align-items-center justify-content-center">
        <div class="col-md-8">
            <div class="contact-form">
                <div class="header-area d-flex justify-content-between align-items-center">
                    <h5>@lang('Add Relative Information')</h5>
                    <a type="button" href="{{ route('user.relatives') }}" class="btn-3 other_btn">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                <i class="fas fa-arrow-left pe-1"></i>@lang('Back')
                            </div>
                            <div class="hover-text btn-single">
                                <i class="fas fa-arrow-left pe-1"></i>@lang('Back')
                            </div>
                        </div>
                    </a>
                </div>

                <form action="{{ route('user.relative.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="type">@lang('Relative Type')</label>
                            <select name="type" class="cmn-input" id="relativeType" required>
                                <option value="adult" {{ old('type') == 'adult' ? 'selected' : '' }}>@lang('Adult')</option>
                                <option value="children" {{ old('type') == 'children' ? 'selected' : '' }}>@lang('Children')</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label" for="firstname">@lang('Firstname')</label>
                            <input type="text" class="cmn-input" name="firstname" id="firstname" placeholder="@lang('e.g. John')" value="{{ old('firstname') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="lastname">@lang('Lastname')</label>
                            <input type="text" class="cmn-input" name="lastname" id="lastname" placeholder="@lang('e.g. Doe')" value="{{ old('lastname') }}" required>
                        </div>
                        <div class="col-md-6 email-phone-group {{ old('type') == 'children' ? 'd-none' : 'd-block' }}">
                            <label class="form-label" for="email">@lang('Email')</label>
                            <input type="email" class="cmn-input" name="email" id="email" placeholder="@lang('e.g. demo@example.com')" value="{{ old('email') }}" required>
                        </div>

                        <div class="col-md-6 phone-number-container email-phone-group {{ old('type') == 'children' ? 'd-none' : 'd-block' }}">
                            <label class="form-label" for="phone">@lang('Phone Number')</label>
                            <div class="phone-input-group">
                                <div class="phone-code-wrapper">
                                    <select name="phone_code" class="phone-code-select" id="phoneCode">
                                        @foreach(config('country') as $country)
                                            <option value="{{ $country['phone_code'] }}"
                                                data-phone_length='@json($country["phoneLength"])'
                                                {{ old('phone_code') == $country['phone_code'] ? 'selected' : '' }}
                                            >
                                                {{ $country['phone_code'].' ( '.$country['name'].' )' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <input
                                    type="tel"
                                    name="phone"
                                    class="phone-input"
                                    id="phone"
                                    placeholder="@lang('123456789')"
                                    value="{{ old('phone') }}"
                                    required
                                >
                            </div>
                            <small class="phone-hint">@lang('Enter 9-digit phone number')</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="country">@lang('Country')</label>
                            <select name="country" id="country" class="cmn-input" required>
                                @foreach($countries as $country)
                                    <option value="{{ $country->name }}" {{ old('country') == $country->name ? 'selected' : '' }}>{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="gender">@lang('Gender')</label>
                            <select name="gender" class="cmn-input" id="gender" required>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>@lang('Male')</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>@lang('Female')</option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label">@lang('Upload Photo')</label>

                            <div class="image-upload-wrapper" onclick="document.getElementById('photoInput').click()">
                                <input type="file" name="photo" id="photoInput" accept="image/*" class="d-none" onchange="previewImage(this, 'photoPreview')">

                                <div id="photoPreview" class="image-preview">
                                    <i class="far fa-cloud-upload upload-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="birth_date">@lang('Date Of Birth')</label>
                            <input type="text" class="cmn-input" name="birth_date" id="birth_date" placeholder="@lang('e.g. 2022-07-12')" value="{{ old('birth_date') }}" required>
                        </div>
                    </div>
                    <div class="submit-btn-area">
                        <button type="submit" class="btn-1">
                            <div class="btn-wrapper">
                                <div class="main-text btn-single">
                                    @lang('Update')
                                </div>
                                <div class="hover-text btn-single">
                                    @lang('Update')
                                </div>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset(template(true) . "css/flatpickr.min.css") }}"/>
    <style>
        .nice-select {
            height: 59px !important;
            line-height: 22px !important;
            width: 100% !important;
        }
        .nice-select.open .list{
            max-height: 185px;
            overflow: auto;
        }
        .contact-form {
            margin: 45px 90px 90px 90px !important;
        }

        .image-upload-wrapper {
            border: 2px dashed #ced4da;
            border-radius: 8px;
            height: 180px;
            position: relative;
            overflow: hidden;
            background-color: #f8f9fa;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.2s ease-in-out;
        }

        .image-upload-wrapper:hover {
            background-color: #e9ecef;
        }

        .image-preview {
            width: 26%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
        }

        .upload-icon {
            font-size: 2rem;
            color: #6c757d;
        }
        .phone-number-container {
            margin-bottom: 1rem;
        }

        .phone-input-group {
            display: flex;
            align-items: center;
            border: 1px solid #ced4da;
            border-radius: 6px;
            transition: all 0.3s ease;
            height: 59px;
        }

        .phone-input-group:focus-within {
            border-color: #86b7fe;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }

        .phone-code-wrapper {
            position: relative;
            min-width: 100px;
            height: 100%;
            border-right: 1px solid #ced4da;
        }

        .phone-code-select {
            width: 100%;
            height: 100%;
            border: none;
            background-color: transparent;
            padding: 0 15px;
            appearance: none;
            cursor: pointer;
        }

        .phone-input {
            flex: 1;
            border: none;
            height: 100%;
            padding: 0 15px;
            background-color: transparent;
        }

        .phone-input:focus {
            outline: none;
        }

        .phone-hint {
            display: block;
            margin-top: 0.5rem;
            font-size: 0.85rem;
            color: #6c757d;
        }

        .phone-code-wrapper .nice-select {
            width: 100% !important;
            height: 57px !important;
            line-height: 57px !important;
            border: none !important;
            background-color: transparent !important;
        }

        .phone-code-wrapper .nice-select .current {
            display: block;
            padding: 0 15px;
        }

        .phone-code-wrapper .nice-select .list {
            width: auto;
            min-width: 100%;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset(template(true).'js/flatpickr.min.js') }}"></script>
    <script>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                Notiflix.Notify.failure(@json($error), {
                    timeout: 5000,
                    position: 'right-bottom',
                });
            @endforeach
        @endif
            $(document).ready(function () {
                $('select.cmn-input').niceSelect();
                $('#phoneCode').niceSelect();

                flatpickr("#birth_date", {
                    dateFormat: "Y-m-d",
                    maxDate: "today",
                    defaultDate: "{{ old('birth_date') }}"
                });

                const emailPhoneFields = $('.email-phone-group');

                function toggleEmailPhoneFields(selectedType) {
                    emailPhoneFields.each(function () {
                        const inputs = $(this).find('input');
                        if (selectedType === 'adult') {
                            $(this).removeClass('d-none').addClass('d-block');
                            inputs.prop('required', true);
                        } else {
                            $(this).removeClass('d-block').addClass('d-none');
                            inputs.prop('required', false);
                        }
                    });
                }

                toggleEmailPhoneFields($('#relativeType').val());

                $('#relativeType').on('change', function () {
                    const selectedVal = $(this).val();
                    toggleEmailPhoneFields(selectedVal);
                });
            });
        function previewImage(input, previewId) {
            const previewDiv = document.getElementById(previewId);
            if (!previewDiv) {
                console.warn(`Element with id "${previewId}" not found.`);
                return;
            }

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    previewDiv.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: 100%; max-height: 200px;" />`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        document.addEventListener("DOMContentLoaded", function () {
            const phoneContainers = document.querySelectorAll(".phone-number-container");

            phoneContainers.forEach((container) => {
                const phoneSelect = container.querySelector(".phone-code-select");
                const phoneInput = container.querySelector(".phone-input");
                const phoneHint = container.querySelector(".phone-hint");

                let allowedLengths = [];

                const updateAllowedLengths = () => {
                    const selectedOption = phoneSelect.options[phoneSelect.selectedIndex];
                    let lengthData = selectedOption.dataset.phone_length;

                    try {
                        let parsed = JSON.parse(lengthData);
                        if (Array.isArray(parsed)) {
                            allowedLengths = parsed.map(Number);
                        } else {
                            allowedLengths = [Number(parsed)];
                        }
                    } catch (e) {
                        allowedLengths = [];
                    }

                    updateHint();
                };

                const updateHint = () => {
                    if (allowedLengths.length) {
                        const formatted = allowedLengths.join(' or ');
                        phoneHint.innerText = `Enter ${formatted}-digit phone number`;
                    } else {
                        phoneHint.innerText = `Enter phone number`;
                    }
                };

                const enforceMaxLength = () => {
                    if (!allowedLengths.length) return;

                    const maxLength = Math.max(...allowedLengths);
                    if (phoneInput.value.length > maxLength) {
                        phoneInput.value = phoneInput.value.slice(0, maxLength);
                    }
                };

                updateAllowedLengths();
                enforceMaxLength();

                phoneSelect.addEventListener("change", () => {
                    updateAllowedLengths();
                    enforceMaxLength();
                });

                phoneInput.addEventListener("input", () => {
                    enforceMaxLength();
                });
            });
        });
    </script>
@endpush
