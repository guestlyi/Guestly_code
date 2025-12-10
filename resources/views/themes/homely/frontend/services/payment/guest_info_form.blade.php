@extends(template() . 'layouts.app')
@section('title',trans('Payment - Guest info'))
@section('content')
    <div class="booking-container container">
        <div class="row">
            <div id="traveller-info-section" class="form-section mt-4">
                <h3 class="mb-3">@lang('Guest Information')</h3>
                <form id="traveller-info-form" action="{{ route('user.booking.userInfo.update', $booking->uid) }}" enctype="multipart/form-data">
                    @php
                        $adults = (int) ($booking->information['adults'] ?? 0);
                        $children = (int) ($booking->information['children'] ?? 0);
                    @endphp

                    @for ($i = 1; $i <= $adults; $i++)
                        @php
                            $initialGuest = $i-1;
                        @endphp
                        <div class="traveller-box mb-4 p-4 border rounded shadow-sm">
                            <div class="row">
                                <h5 class="mb-3">@lang('Adult Guest') {{ $i }}</h5>
                                <div class="col-md-4">
                                    <label class="form-label">@lang('Select From List')</label>
                                    <select name="adults[{{ $i }}][select_list]" class="cmn-input adult-guest-selector" data-index="{{ $i }}" required>
                                        <option value="new" selected>@lang('New User')</option>
                                        @if(isset($adultRelatives))
                                            @foreach($adultRelatives as $adultGuest)
                                                <option value="{{ $adultGuest['serial'] }}" data-info="{{ htmlentities(json_encode($adultGuest), ENT_QUOTES, 'UTF-8') }}">
                                                    {{ $adultGuest['firstname'].' '. $adultGuest['lastname'] }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3">
                                <input type="hidden" name="adults[{{ $i }}][type]" class="cmn-input" id="adultGuestType{{ $i }}" value="new">

                                <div class="col-md-6">
                                    <label class="form-label">@lang('First Name')</label>
                                    <input type="text"
                                           name="adults[{{ $i }}][first_name]"
                                           class="cmn-input"
                                           id="adultFirstname{{ $i }}"
                                           placeholder="@lang('e.g. John')"
                                           value="{{ old("adults[$i][first_name]", $booking->user_info['adult'][$initialGuest]['firstname'] ?? '') }}"
                                           required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">@lang('Last Name')</label>
                                    <input type="text" name="adults[{{ $i }}][last_name]" class="cmn-input" id="adultLastname{{ $i }}" placeholder="@lang('e.g. Doe')" value="{{ old("adults[$i][last_name]", $booking->user_info['adult'][$initialGuest]['lastname'] ?? '') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="adultGender{{ $i }}">@lang('Gender')</label>
                                    <div class="selectAdultGender">
                                        <select id="adultGender{{ $i }}" name="adults[{{ $i }}][gender]" class="cmn-input" required>
                                            <option value="" disabled {{ old("adults[$i][gender]", $booking->user_info['adult'][$initialGuest]['gender'] ?? '') == '' ? 'selected' : '' }}>@lang('Select one')</option>
                                            <option value="male" {{ old("adults[$i][gender]", $booking->user_info['adult'][$initialGuest]['gender'] ?? '') == 'male' ? 'selected' : '' }}>@lang('Male')</option>
                                            <option value="female" {{ old("adults[$i][gender]", $booking->user_info['adult'][$initialGuest]['gender'] ?? '') == 'female' ? 'selected' : '' }}>@lang('Female')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 age-input-wrapper">
                                    <label class="form-label">@lang('Date of Birth')</label>
                                    <input type="text" name="adults[{{ $i }}][birth_date]"
                                           class="cmn-input dob-adult age-input" id="adultBirth{{ $i }}"
                                           value="{{ old("adults[$i][birth_date]", $booking->user_info['adult'][$initialGuest]['birth_date'] ?? '') }}"
                                           placeholder="YYYY-MM-DD" required>
                                    <span class="age-inside-input"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label" for="adultCountry{{ $i }}">@lang('Country')</label>
                                    <div class="selectCountry">
                                        <select name="adults[{{ $i }}][country]" class="cmn-input country-select" id="adultCountry{{ $i }}" required>
                                            @foreach($countries as $country)
                                                <option value="{{ $country['name'] }}"  {{ old("adults[$i][country]", $booking->user_info['adult'][$initialGuest]['country'] ?? '') == $country['name'] ? 'selected' : '' }}>
                                                    {{ $country['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="adultEmail{{ $i }}">@lang('Email')</label>
                                    <input type="email" name="adults[{{ $i }}][email]" class="cmn-input" id="adultEmail{{ $i }}" value="{{ old("adults[$i][email]", $booking->user_info['adult'][$initialGuest]['email'] ?? '') }}" placeholder="you@example.com" required>
                                </div>
                                <div class="col-md-6 phone-number-container">
                                    <label class="form-label" for="adultPhone{{ $i }}">@lang('Phone Number')</label>
                                    <div class="phone-input-group">
                                        <div class="phone-code-wrapper">
                                            <select name="adults[{{ $i }}][phone_code]" class="phone-code-select" id="phoneCode{{ $i }}">
                                                @foreach(config('country') as $country)
                                                    <option
                                                        value="{{ $country['phone_code'] }}"
                                                        data-phone_code="{{ $country['phone_code'] }}"
                                                        data-phone_length='@json($country["phoneLength"])'
                                                        {{ old("adults[$i][phone_code]", $booking->user_info['adult'][$initialGuest]['phone_code'] ?? '') == $country['phone_code'] ? 'selected' : '' }}
                                                    >
                                                        {{ $country['phone_code'].' ( '.$country['name'].' )' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <input type="tel" name="adults[{{ $i }}][phone]" class="phone-input" id="adultPhone{{ $i }}" placeholder="123456789" value="{{ old("adults[$i][phone]", $booking->user_info['adult'][$initialGuest]['phone'] ?? '') }}" required>
                                    </div>
                                    <small class="phone-hint">@lang('Enter 9-digit phone number')</small>
                                </div>

                                <div class="col-md-12 guestInfoCheck">
                                    <div class="form-check mt-3">
                                        <input type="hidden" name="adults[{{ $i }}][save_for_future]" value="0">
                                        <input class="form-check-input" type="checkbox" name="adults[{{ $i }}][save_for_future]" id="saveForFutureAdult{{ $i }}" value="1">
                                        <label class="form-check-label2" for="saveForFutureAdult{{ $i }}">
                                            @lang('Save this information for future use')
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor

                    @for ($i = 1; $i <= $children; $i++)
                        @php
                            $initialchild = $i-1;
                        @endphp
                        <div class="traveller-box mb-4 p-4 border rounded bg-light">
                            <div class="row">
                                <h5 class="mb-3">@lang('Child Guest') {{ $i }}</h5>
                                <div class="col-md-4">
                                    <label class="form-label">@lang('Select From List')</label>
                                    <select name="children[{{ $i }}][select_list]" class="cmn-input child-guest-selector" data-index="{{ $i }}" required>
                                        <option value="new" selected>@lang('New User')</option>
                                        @if(isset($childRelatives))
                                            @foreach($childRelatives as $childGuest)
                                                <option value="{{ $childGuest['serial'] }}" data-info="{{ htmlentities(json_encode($childGuest), ENT_QUOTES, 'UTF-8') }}">{{ $childGuest['firstname'].' '. $childGuest['lastname'] }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="row g-3">
                                <input type="hidden" name="children[{{ $i }}][type]" class="cmn-input" id="childGuestType{{ $i }}" value="new">

                                <div class="col-md-6">
                                    <label class="form-label">@lang('First Name')</label>
                                    <input type="text" name="children[{{ $i }}][first_name]" id="childFirstname{{ $i }}" class="cmn-input" value="{{ old("children[$i][first_name]", $booking->user_info['children'][$initialchild]['firstname'] ?? '') }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">@lang('Last Name')</label>
                                    <input type="text" name="children[{{ $i }}][last_name]" class="cmn-input" id="childLastname{{ $i }}"  value="{{ old("children[$i][last_name]", $booking->user_info['children'][$initialchild]['lastname'] ?? '') }}" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">@lang('Gender')</label>
                                    <div class="selectChildGender">
                                        <select name="children[{{ $i }}][gender]" class="cmn-input" id="childGender{{ $i }}" required>
                                            <option value="" disabled {{ old("children[$i][gender]", $booking->user_info['children'][$initialchild]['gender'] ?? '') == '' ? 'selected' : '' }}>@lang('Select one')</option>
                                            <option value="male" {{ old("children[$i][gender]", $booking->user_info['children'][$initialchild]['gender'] ?? '') == 'male' ? 'selected' : '' }}>@lang('Male')</option>
                                            <option value="female" {{ old("children[$i][gender]", $booking->user_info['children'][$initialchild]['gender'] ?? '') == 'female' ? 'selected' : '' }}>@lang('Female')</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 age-input-wrapper">
                                    <label class="form-label">@lang('Child Date of Birth')</label>
                                    <input type="text" name="children[{{ $i }}][birth_date]" id="childBirth{{ $i }}"
                                           class="cmn-input dob-child age-input"
                                           placeholder="YYYY-MM-DD" value="{{ old("children[$i][birth_date]", $booking->user_info['children'][$initialchild]['birth_date'] ?? '') }}" required>
                                    <span class="age-inside-input"></span>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">@lang('Country')</label>
                                    <div class="selectChildCountry">
                                        <select name="children[{{ $i }}][country]" id="childCountry{{ $i }}" class="cmn-input" required>
                                            @foreach($countries as $country)
                                                <option value="{{ $country['name'] }}" {{ old("children[$i][country]", $booking->user_info['children'][$initialchild]['country'] ?? '') == $country['name'] ? 'selected' : '' }}>{{ $country['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 guestInfoCheck">
                                    <div class="form-check mt-3">
                                        <input type="hidden" name="children[{{ $i }}][save_for_future]" value="0">
                                        <input class="form-check-input" type="checkbox" name="children[{{ $i }}][save_for_future]" id="saveForFutureChildren{{ $i }}" value="1">
                                        <label class="form-check-label2" for="saveForFutureChildren{{ $i }}">
                                            @lang('Save this information for future use')
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endfor
                    <button type="submit" class="btn-1">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                @lang('Continue to Payment')
                            </div>
                            <div class="hover-text btn-single">
                                @lang('Continue to Payment')
                            </div>
                        </div>
                    </button>
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
        .guestInfoCheck .form-check{
            display: flex;
            align-items: center;
            justify-content: start;
            gap: 10px;
            margin-left: 24px;
        }
        .nice-select.open .list{
            max-height: 285px;
            overflow: auto;
        }
        .age-input-wrapper {
            position: relative;
        }

        .age-input {
            padding-right: 60px;
        }

        .age-inside-input {
            position: absolute;
            right: 20px;
            top: 55%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #666;
            font-size: 0.9em;
            user-select: none;
            font-weight: 600;
            white-space: nowrap;
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

        /* NiceSelect Overrides */
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
        const countriesData = @json(config('country'));

        const bookingRedirectRoute = @json(route('user.booking.payment.info', ['uid' => 'REPLACE_UID']));
        const csrfToken = '{{ csrf_token() }}';
        const countries = @json($countries);

        $('#traveller-info-form').on('submit', function (e) {
            e.preventDefault();

            Notiflix.Loading.standard('Processing...');
            const formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-CSRF-TOKEN': csrfToken },
                success: function (response) {
                    Notiflix.Loading.remove();

                    if (response.status === 'success') {
                        Notiflix.Notify.success('Guest Info Saved Successfully successfully!');
                        window.location.href = bookingRedirectRoute.replace('REPLACE_UID', response.booking.uid);
                    } else {
                        Notiflix.Notify.failure(response.message || 'Something went wrong!');
                    }
                },
                error: function (xhr) {
                    Notiflix.Loading.remove();

                    if (xhr.status === 401) {
                        return window.location.href = "{{ route('login') }}";
                    }

                    const errors = xhr.responseJSON?.errors
                        ? Object.values(xhr.responseJSON.errors).flat().join('\n')
                        : 'Something went wrong.';
                    Notiflix.Notify.failure(errors);
                }
            });
        });
        $(document).ready(function () {
            $('select.cmn-input').niceSelect();
            $('.phone-code-select').niceSelect();
        });

        document.addEventListener('DOMContentLoaded', function () {
            $('select.adult-guest-selector').on('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const index = this.getAttribute('data-index');
                let data = selectedOption.getAttribute('data-info');
                let guestType = this.value;
                if (!data || this.value === 'new') {
                    clearGuestFields(index, guestType);
                    return;
                }

                function htmlDecode(input) {
                    const e = document.createElement('textarea');
                    e.innerHTML = input;
                    return e.value;
                }

                const decodedData = htmlDecode(data);
                const guest = JSON.parse(decodedData);

                fillGuestFields(index, guest, guestType);
            });

            function fillGuestFields(index, guest, guestType) {
                document.getElementById(`adultFirstname${index}`).value = guest.firstname ?? '';
                document.getElementById(`adultLastname${index}`).value = guest.lastname ?? '';
                document.getElementById(`adultBirth${index}`).value = guest.birth_date ?? '';
                document.getElementById(`adultGender${index}`).value = guest.gender ?? '';
                document.getElementById(`adultEmail${index}`).value = guest.email ?? '';
                document.getElementById(`adultPhone${index}`).value = guest.phone ?? '';
                document.getElementById(`adultGuestType${index}`).value = guestType ?? '';

                if (guest.phone_code) {
                    const phoneCodeSelect = document.getElementById(`phoneCode${index}`);
                    if (phoneCodeSelect) {
                        const guestCode = guest.phone_code.toString().trim();

                        Array.from(phoneCodeSelect.options).forEach(opt => {
                            opt.selected = opt.value.toString().trim() === guestCode;
                        });

                        phoneCodeSelect.value = guestCode;
                    }
                }

                const country = guest.country?.trim() ?? '';
                const wrapper = document.querySelector(`#adultCountry${index}`).closest('.selectCountry');

                if (wrapper) {
                    let newSelectHtml = `<select name="adults[${index}][country]" class="cmn-input country-select" id="adultCountry${index}" required>`;
                    countries.forEach(c => {
                        const isSelected = c.name.trim().toLowerCase() === country.toLowerCase() ? 'selected' : '';
                        const phoneLength = Array.isArray(c.phoneLength) ? c.phoneLength.join(',') : c.phoneLength;
                        newSelectHtml += `<option value="${c.name}"
                              data-phone-code="${c.phone_code}"
                              data-phone-length="${phoneLength}"
                              ${isSelected}>
                              ${c.name}
                          </option>`;
                    });
                    newSelectHtml += `</select>`;
                    wrapper.innerHTML = newSelectHtml;
                }

                const adultGenderWrapper = document.querySelector(`#adultGender${index}`)?.closest('.selectAdultGender');
                if (adultGenderWrapper) {
                    let newSelectAdultHtml = `<select name="adults[${index}][gender]" class="cmn-input" id="adultGender${index}" required>`;
                    const maleSelected = guest.gender?.toLowerCase() === 'male' ? 'selected' : '';
                    const femaleSelected = guest.gender?.toLowerCase() === 'female' ? 'selected' : '';
                    newSelectAdultHtml += `<option value="male" ${maleSelected}>Male</option>`;
                    newSelectAdultHtml += `<option value="female" ${femaleSelected}>Female</option>`;
                    newSelectAdultHtml += `</select>`;
                    adultGenderWrapper.innerHTML = newSelectAdultHtml;
                }
            }


            function clearGuestFields(index, guestType) {
                document.getElementById(`adultFirstname${index}`).value = '';
                document.getElementById(`adultLastname${index}`).value = '';
                document.getElementById(`adultBirth${index}`).value = '';
                document.getElementById(`adultGender${index}`).selectedIndex = -1;
                document.getElementById(`adultEmail${index}`).value = '';
                document.getElementById(`adultPhone${index}`).value = '';
                document.getElementById(`adultCountry${index}`).value = '';
                document.getElementById(`adultGuestType${index}`).value = guestType;
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            $('select.child-guest-selector').on('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                const index = this.getAttribute('data-index');
                let data = selectedOption.getAttribute('data-info');
                let guestType = this.value;

                if (!data || this.value === 'new') {
                    clearGuestFields(index, guestType);
                    return;
                }

                function htmlDecode(input) {
                    const e = document.createElement('textarea');
                    e.innerHTML = input;
                    return e.value;
                }

                const decodedData = htmlDecode(data);
                const guest = JSON.parse(decodedData);

                fillGuestFields(index, guest, guestType);
            });

            function fillGuestFields(index, guest, guestType) {
                document.getElementById(`childFirstname${index}`).value = guest.firstname ?? '';
                document.getElementById(`childLastname${index}`).value = guest.lastname ?? '';
                document.getElementById(`childBirth${index}`).value = guest.birth_date ?? '';
                document.getElementById(`childGender${index}`).value = guest.gender ?? '';
                document.getElementById(`childGuestType${index}`).value = guestType ?? '';

                const country = guest.country?.trim() ?? '';
                const wrapper = document.querySelector(`#childCountry${index}`).closest('.selectChildCountry');

                if (wrapper) {
                    let newSelectHtml = `<select name="children[${index}][country]" class="cmn-input" id="childCountry${index}" required>`;

                    countries.forEach(c => {
                        const isSelected = c.name.trim().toLowerCase() === country.toLowerCase() ? 'selected' : '';
                        newSelectHtml += `<option value="${c.name}" ${isSelected}>${c.name}</option>`;
                    });

                    newSelectHtml += `</select>`;
                    wrapper.innerHTML = newSelectHtml;
                }

                const genderWrapper = document.querySelector(`#childGender${index}`)?.closest('.selectChildGender');

                if (genderWrapper) {
                    let newSelectChildHtml = `<select name="children[${index}][gender]" class="cmn-input" id="childGender${index}" required>`;

                    const maleSelected = guest.gender?.toLowerCase() === 'male' ? 'selected' : '';
                    const femaleSelected = guest.gender?.toLowerCase() === 'female' ? 'selected' : '';

                    newSelectChildHtml += `<option value="male" ${maleSelected}>Male</option>`;
                    newSelectChildHtml += `<option value="female" ${femaleSelected}>Female</option>`;
                    newSelectChildHtml += `</select>`;

                    genderWrapper.innerHTML = newSelectChildHtml;
                }
            }

            function clearGuestFields(index, guestType) {
                document.getElementById(`childFirstname${index}`).value = '';
                document.getElementById(`childLastname${index}`).value = '';
                document.getElementById(`childBirth${index}`).value = '';
                document.getElementById(`childGender${index}`).selectedIndex = -1;
                document.getElementById(`childCountry${index}`).value = '';
                document.getElementById(`childGuestType${index}`).value = guestType;
            }
        });

        function calculateAge(dateString) {
            const today = new Date();
            const dob = new Date(dateString);
            let age = today.getFullYear() - dob.getFullYear();
            const m = today.getMonth() - dob.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }
            return age;
        }

        flatpickr('.dob-adult', {
            dateFormat: 'Y-m-d',
            maxDate: new Date(new Date().setFullYear(new Date().getFullYear() - 12)),
            onChange: function(selectedDates, dateStr, instance) {
                const age = calculateAge(dateStr);
                const wrapper = instance._input.parentNode;
                const ageSpan = wrapper.querySelector('.age-inside-input');
                if (age >= 12) {
                    ageSpan.textContent = `${age} yrs`;
                } else {
                    ageSpan.textContent = '';
                    Notiflix.Notify.failure('Adult must be at least 12 years old.');
                    instance.clear();
                }
            }
        });

        flatpickr('.dob-child', {
            dateFormat: 'Y-m-d',
            maxDate: new Date(),
            minDate: new Date(new Date().setFullYear(new Date().getFullYear() - 11, new Date().getMonth(), new Date().getDate() + 1)),
            onChange: function(selectedDates, dateStr, instance) {
                const age = calculateAge(dateStr);
                const wrapper = instance._input.parentNode;
                const ageSpan = wrapper.querySelector('.age-inside-input');
                if (age >= 0 && age <= 11) {
                    ageSpan.textContent = `${age} yrs`;
                } else {
                    ageSpan.textContent = '';
                    Notiflix.Notify.failure('Child must be 11 years old or younger.');
                    instance.clear();
                }
            }
        });

        document.addEventListener("DOMContentLoaded", function () {
            const phoneContainers = document.querySelectorAll(".phone-number-container");

            phoneContainers.forEach((container, index) => {
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
