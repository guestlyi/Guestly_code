@extends(template().'layouts.user')
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
                </div>
                @php
                    $vendorInfo = auth()->user()->vendorInfo;
                @endphp
                <div class="col-lg-9 col-xl-8 offset-xl-1">
                    <div class="creat-profile">
                        <h4 class="creat-profile-title">@lang('General Information')</h4>
                        <p>@lang('Contact info and personal details can be edited. If this info was used to verify your identity')</p>
                        <div class="creat-profile-list">
                            <ul>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#locationModal" data-type="Country" data-label="Country" id="selectCountry">
                                        <i class="far fa-flag"></i>
                                        @lang("Country")
                                        @if(!empty(auth()->user()->country))
                                            : {{ auth()->user()->country }}
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#locationModal" data-type="State" data-label="State" id="selectState">
                                        <i class="far fa-flag-usa"></i>
                                        @lang('State')
                                        @if(!empty(auth()->user()->state))
                                            : {{ auth()->user()->state }}
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#locationModal" data-type="City" data-label="City" id="selectCity">
                                        <i class="far fa-city"></i>
                                        @lang('City')
                                        @if(!empty(auth()->user()->city))
                                            : {{ auth()->user()->city }}
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
                                            : {{ $userLanguage->name }}
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicBasicModal" data-type="zip_code" data-label="Zip Code">
                                        <i class="far fa-mailbox"></i>
                                        @lang('Zip Code')
                                        @if(!empty(auth()->user()->zip_code))
                                            : {{ auth()->user()->zip_code }}
                                        @endif
                                    </a>
                                </li>
                                <li>
                                    <a href="#0" data-bs-toggle="modal" data-bs-target="#timeZoneModal" data-type="timeZone" data-label="Time Zone">
                                        <i class="fa-regular fa-calendar-check"></i>
                                        @lang('Time Zone')
                                        @if(!empty(auth()->user()->time_zone))
                                            : {{ auth()->user()->time_zone }}
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
                                            : {{ auth()->user()->phone_code . auth()->user()->phone }}
                                        @endif
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    @if(isset($vendorInfo))
                        <div class="creat-profile mt-4">
                            <h4 class="creat-profile-title">@lang('Host Special')</h4>
                            <p>@lang('This is host special feature for any host.')</p>
                            <div class="creat-profile-list">
                                <ul>
                                    <li>
                                        <a href="#0" data-bs-toggle="modal" data-bs-target="#placeModal" id="selectedPlaceLink">
                                            <i class="fa-light fa-globe-stand"></i>
                                            @lang("My Favourite Place")
                                            @if(!empty($vendorInfo?->want_to_go))
                                                : {{ $vendorInfo->want_to_go }} <i class="fa-light fa-chevron-right ms-1"></i>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-type="my_work" data-label="My work" data-icon="briefcase">
                                            <i class="fa-light fa-briefcase"></i>
                                            @lang('My work')
                                            @if(!empty($vendorInfo?->my_work))
                                                : {{ $vendorInfo->my_work }} <i class="fa-light fa-chevron-right ms-1"></i>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-type="music" data-label="My favorite song" data-icon="music">
                                            <i class="fa-light fa-music"></i>
                                            @lang('My favorite song')
                                            @if(!empty($vendorInfo?->music))
                                                : {{ $vendorInfo->music }} <i class="fa-light fa-chevron-right ms-1"></i>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-type="pets" data-label="Pets" data-icon="paw-simple">
                                            <i class="fa-light fa-paw-simple"></i>
                                            @lang('Pets')
                                            @if(!empty($vendorInfo?->pets))
                                                : {{ $vendorInfo->pets }} <i class="fa-light fa-chevron-right ms-1"></i>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-type="facebook" data-label="Facebook link" data-icon="facebook">
                                            <i class="fa-brands fa-facebook"></i>
                                            @lang('Facebook link')
                                            @if(!empty($vendorInfo?->facebook))
                                                : {{ $vendorInfo->facebook }} <i class="fa-light fa-chevron-right ms-1"></i>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-type="twitter" data-label="Twitter link" data-icon="twitter">
                                            <i class="fa-brands fa-twitter"></i>
                                            @lang('Twitter link')
                                            @if(!empty($vendorInfo?->twitter))
                                                : {{ $vendorInfo->twitter }} <i class="fa-light fa-chevron-right ms-1"></i>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-type="linkedin" data-label="LinkedIn link" data-icon="linkedin">
                                            <i class="fa-brands fa-linkedin"></i>
                                            @lang('LinkedIn link')
                                            @if(!empty($vendorInfo?->linkedin))
                                                : {{ $vendorInfo->linkedin }} <i class="fa-light fa-chevron-right ms-1"></i>
                                            @endif
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#0" data-bs-toggle="modal" data-bs-target="#dynamicModal" data-type="instagram" data-label="Instagram link" data-icon="instagram">
                                            <i class="fa-brands fa-instagram"></i>
                                            @lang('Instagram link')
                                            @if(!empty($vendorInfo?->instagram))
                                                : {{ $vendorInfo->instagram }} <i class="fa-light fa-chevron-right ms-1"></i>
                                            @endif
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="intro mt_40">
                            <h4 class="creat-profile-title">@lang('About You')</h4>
                            <div class="intro-container">
                                <p>{{ $vendorInfo->intro }}</p>
                                <a href="#0" data-bs-toggle="modal" data-bs-target="#introModal">@lang('Edit intro')</a>
                            </div>
                        </div>
                        <div class="next-destination mt_40">
                            <h4 class="creat-profile-title">@lang('Add Your Skills')</h4>
                            <p>@lang('Share some interesting thing about you.')</p>
                            <div class="interest-list">
                                <ul id="skillsList">
                                    @if(!empty($vendorInfo?->skills))
                                        @foreach($vendorInfo->skills as $skill)
                                            <li class="skill-item" data-skill="{{ $skill }}">
                                                {{ $skill }}
                                                <span class="remove-skill" style="cursor: pointer; margin-left: 8px;">
                                                    <i class="fa-light fa-xmark"></i>
                                                </span>
                                            </li>
                                        @endforeach
                                    @endif
                                    <li class="skill-plus">
                                        <a href="#0" class="add-skill-btn"><i class="fa-light fa-plus"></i></a>
                                    </li>
                                </ul>
                            </div>
                            <button type="button" id="saveSkillsBtn" class="btn btn-primary mt-3">@lang('Update Skills')</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    @include(template().'user.profile.partials.profile_modals')
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
    </style>
@endpush

@include(template().'user.profile.partials.profile_script')
