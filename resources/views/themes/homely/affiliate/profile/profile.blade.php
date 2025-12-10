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
                            <div class="creat-profile-input">
                                <div class="photo-input">
                                    <label for="fileUpload" class="btn-1"><i class="fa-light fa-camera-retro"></i></label>
                                    <input type="file" class="imageInput" id="fileUpload" accept="image/*" multiple="">
                                </div>
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
                        <div class="top-area d-flex align-items-center justify-content-between">
                            <div class="left-side-area">
                                <h4 class="creat-profile-title">@lang('General Information')</h4>
                                <p>@lang('Contact info and personal details can be edited. If this info was used to verify your identity')</p>
                            </div>
                            <div class="right-side-area">
                                <a href="{{ route('affiliate.profile.change.password') }}" class="btn-3">
                                    <div class="btn-wrapper">
                                        <div class="main-text btn-single">
                                            <i class="far fa-lock"></i> @lang('Change Password')
                                        </div>
                                        <div class="hover-text btn-single">
                                            <i class="far fa-lock"></i> @lang('Change Password')
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>

                        <div class="creat-profile-list">
                            <ul>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicBasicModal" data-type="Firstname" data-label="Firstname" id="firstname">
                                        <i class="far fa-user"></i>
                                        @lang("Firstname")
                                        @if(!empty(auth('affiliate')->user()->firstname))
                                            : <span class="value">{{ auth('affiliate')->user()->firstname }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicBasicModal" data-type="Lastname" data-label="Lastname" id="lastname">
                                        <i class="far fa-id-badge"></i>
                                        @lang("Lastname")
                                        @if(!empty(auth('affiliate')->user()->lastname))
                                            : <span class="value">{{ auth('affiliate')->user()->lastname }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicBasicModal" data-type="Username" data-label="Username" id="username">
                                        <i class="far fa-user-circle"></i>
                                        @lang("Username")
                                        @if(!empty(auth('affiliate')->user()->username))
                                            : <span class="value">{{ auth('affiliate')->user()->username }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicBasicModal" data-type="Email" data-label="Email" id="email">
                                        <i class="far fa-envelope"></i>
                                        @lang("Email")
                                        @if(!empty(auth('affiliate')->user()->email))
                                            : <span class="value">{{ auth('affiliate')->user()->email }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#locationModal" data-type="Country" data-label="Country" id="selectCountry">
                                        <i class="far fa-flag"></i>
                                        @lang("Country")
                                        @if(!empty(auth()->user()->country))
                                            : <span class="value">{{ auth()->user()->country }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#locationModal" data-type="State" data-label="State" id="selectState">
                                        <i class="far fa-flag-usa"></i>
                                        @lang('State')
                                        @if(!empty(auth()->user()->state))
                                            : <span class="value">{{ auth()->user()->state }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#locationModal" data-type="City" data-label="City" id="selectCity">
                                        <i class="far fa-city"></i>
                                        @lang('City')
                                        @if(!empty(auth()->user()->city))
                                            : <span class="value">{{ auth()->user()->city }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0"
                                       id="selectLanguageBtn"
                                       data-bs-toggle="modal"
                                       data-bs-target="#languageModal"
                                       data-type="language"
                                       data-label="Language">
                                        <i class="far fa-language"></i>
                                        @lang('Language')
                                        @if(isset($userLanguage))
                                            : <span class="value">{{ $userLanguage->name }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicBasicModal" data-type="zip_code" data-label="Zip Code">
                                        <i class="far fa-mailbox"></i>
                                        @lang('Zip Code')
                                        @if(!empty(auth()->user()->zip_code))
                                            : <span class="value">{{ auth()->user()->zip_code }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#timeZoneModal" data-type="timeZone" data-label="Time Zone">
                                        <i class="fa-regular fa-calendar-check"></i>
                                        @lang('Time Zone')
                                        @if(!empty(auth()->user()->time_zone))
                                            : <span class="value">{{ auth()->user()->time_zone }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0"
                                       data-bs-toggle="modal"
                                       data-bs-target="#phoneWithCodeModal"
                                       data-type="phone"
                                       data-label="@lang('Phone')"
                                       data-phone-code="{{ auth()->user()->phone_code }}"
                                       data-phone="{{ auth()->user()->phone }}"
                                    >
                                        <i class="fa-regular fa-phone"></i>
                                        @lang('Phone')
                                        @if(!empty(auth()->user()->phone))
                                            : <span class="value">{{ auth()->user()->phone_code . auth()->user()->phone }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                            <ul class="insideUl">
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicBasicModal" data-type="Address_one" data-label="address_one" id="Address_one">
                                        <i class="far fa-map-marker-alt"></i>
                                        @lang("Address One")
                                        @if(!empty(auth('affiliate')->user()->address_one))
                                            : <span class="value">{{ auth('affiliate')->user()->address_one }}</span>
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicBasicModal" data-type="Address_two" data-label="address_two" id="Address_two">
                                        <i class="far fa-building"></i>
                                        @lang("Address Two")
                                        @if(!empty(auth('affiliate')->user()->address_two))
                                            : <span class="value">{{ auth('affiliate')->user()->address_two }}</span>
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include(template().'affiliate.profile.partials.modals')
@endsection
@push('style')
    <style>
        .creat-profile-input {
            left: 55%;
            bottom: -11%;
        }
        .search-results-container {
            max-height: 300px;
            overflow-y: auto;
        }
        .skill-badge {
            background: #f1f1f1;
            border-radius: 20px;
            padding: 6px 12px;
            margin-right: 8px;
            display: inline-block;
        }
        .skill-input {
            border: none;
            border-bottom: 1px solid #ccc;
            outline: none;
            width: 150px;
        }
        .skill-item {
            display: inline-block;
            margin: 5px;
            padding: 5px 10px;
            background: #f0f0f0;
            border-radius: 20px;
        }
        .remove-skill i {
            color: red;
        }
        .search-results-container .list-group-item, .languageSelectDropdown .list-group-item {
            padding: 12px 17px !important;
        }
        .insideUl{
            display: flex;
            flex-direction: column;
            margin-top: 50px;
        }
        .insideUl li {
            max-width: 654px !important;
            position: relative;
            right: 158px;
        }
        .nice-select {
            height: 59px;
            line-height: 58px;
            margin-bottom: 18px;
        }
        .nice-select .list {
            width: 100% !important;
            overflow-y: auto !important;
            height: 200px;
        }
        #phoneWithCodeModal .modal-content, #timeZoneModal .modal-content{
            overflow: visible;
        }
    </style>
@endpush

@include(template().'affiliate.profile.partials.script')
