<div class="card">
    <div class="card-header">
        <h2 class="card-title h4">@lang('Basic information')</h2>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.affiliate.profile.basic.update', $affiliate->id) }}" method="POST">
            @csrf

            <div class="row mb-4">
                <label for="firstNameLabel" class="col-sm-3 col-form-label form-label">@lang('Full name') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Displayed on public forums, such as Front."></i></label>

                <div class="col-sm-9">
                    <div class="input-group input-group-sm-vertical">
                        <input type="text" class="form-control" name="firstname" id="firstNameLabel" placeholder="Your first name" aria-label="Your first name" value="{{ old('firstname', $affiliate->firstname) }}">
                        <input type="text" class="form-control" name="lastname" id="lastNameLabel" placeholder="Your last name" aria-label="Your last name" value="{{ old('lastname', $affiliate->lastname) }}">
                    </div>
                </div>
            </div>
            <div class="row mb-4">
                <label for="usernameLabel" class="col-sm-3 col-form-label form-label">@lang('Username')</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="username" id="usernameLabel" placeholder="Username" aria-label="Username" value="{{ old('username', $affiliate->username) }}">
                </div>
            </div>
            @php
                $countries = config('country');
            @endphp
            <div class="row mb-4">
                <label for="phone" class="col-sm-3 col-form-label form-label">@lang('Phone')</label>

                <div class="col-sm-3">
                    <select class="form-select" id="countryCodeSelect" name="phone_code">
                        @foreach($countries as $country)
                            <option value="{{ $country['phone_code'] }}"
                                    data-flag="{{ asset($country['flag']) }}"
                                    data-length='@json($country['phoneLength'])'
                                    data-country-code="{{ $country['code'] }}"
                                    @if(old('country_code', $affiliate->phone_code) == $country['phone_code']) selected @endif>
                                {{ $country['phone_code'] }} ({{ $country['name'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-sm-6">
                    <input type="text" class="form-control" name="phone" id="phoneInput" placeholder="Phone Number" value="{{ old('phone', $affiliate->phone) }}">
                </div>
            </div>

            <div class="row mb-4">
                <label for="locationLabel" class="col-sm-3 col-form-label form-label">@lang('Location')</label>
                <input type="hidden" name="country_code" id="countryCodeHidden" value="{{ old('country_code', $affiliate->country_code) }}">
                <div class="col-sm-9">
                    <div class="tom-select-custom mb-4">
                        <select class="js-select form-select" id="locationLabel" name="country">
                            @foreach($countries as $country)
                                <option value="{{ $country['name'] }}"
                                        data-code="{{ $country['code'] }}"
                                        @if(old('country', $affiliate->country) == $country['name']) selected @endif>
                                    {{ $country['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <input type="text" class="form-control" name="city" id="cityLabel" placeholder="City" aria-label="City" value="{{ old('city', $affiliate->city) }}">
                    </div>

                    <input type="text" class="form-control" name="state" id="stateLabel" placeholder="State" aria-label="State" value="{{ old('state', $affiliate->state) }}">
                </div>
            </div>

            <div class="row mb-4">
                <label for="addressLine1Label" class="col-sm-3 col-form-label form-label">@lang('Address line 1')</label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="address_one" id="addressLine1Label" placeholder="Your address" aria-label="Your address" value="{{ old('address_one', $affiliate->address_one) }}">
                </div>
            </div>

            <div class="row mb-4">
                <label for="addressLine2Label" class="col-sm-3 col-form-label form-label">@lang('Address line 2') <span class="form-label-secondary">(@lang('Optional'))</span></label>

                <div class="col-sm-9">
                    <input type="text" class="form-control" name="address_two" id="addressLine2Label" placeholder="Your address" aria-label="Your address" value="{{ old('address_two', $affiliate->address_two) }}">
                </div>
            </div>

            <div class="row mb-4">
                <label for="zipCodeLabel" class="col-sm-3 col-form-label form-label">@lang('Zip code') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="You can find your code in a postal address."></i></label>

                <div class="col-sm-9">
                    <input type="text" class="js-input-mask form-control" name="zip_code" id="zipCodeLabel" placeholder="Your zip code" aria-label="Your zip code" value="{{ old('zip_code', $affiliate->zip_code) }}">
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
            </div>
        </form>
    </div>
</div>
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush
@if ($errors->any())
    @push('script')
        <script>
            @foreach ($errors->all() as $error)
            Notiflix.Notify.failure(@json($error));
            @endforeach
        </script>
    @endpush
@endif

@push('script')
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script>
        HSCore.components.HSTomSelect.init('#locationLabel', {
            maxOptions: 250,
            placeholder: 'Select country'
        });
        HSCore.components.HSTomSelect.init('#countryCodeSelect', {
            maxOptions: 250,
            placeholder: 'Select Code'
        });
        document.addEventListener('DOMContentLoaded', function () {
            const countrySelect = document.getElementById('countryCodeSelect');
            const phoneInput = document.getElementById('phoneInput');
            let maxLength = 9;

            function updateMaskAndLimit() {
                const selected = countrySelect.options[countrySelect.selectedIndex];
                const phoneLength = JSON.parse(selected.dataset.length);

                maxLength = Array.isArray(phoneLength)
                    ? Math.max(...phoneLength)
                    : phoneLength;

                const mask = '0'.repeat(maxLength);

                if (window.Inputmask) {
                    Inputmask({ mask: mask }).mask(phoneInput);
                }

                phoneInput.setAttribute('maxlength', maxLength);
            }

            phoneInput.addEventListener('input', function () {
                const val = this.value.replace(/\D/g, '');
                if (val.length > maxLength) {
                    this.value = val.slice(0, maxLength);
                    Notiflix.Notify.failure(`Phone number must be at most ${maxLength} digits.`);
                }
            });

            countrySelect.addEventListener('change', updateMaskAndLimit);

            updateMaskAndLimit();
        });

        document.addEventListener('DOMContentLoaded', function () {
            const countrySelect = document.getElementById('locationLabel');
            const countryCodeInput = document.getElementById('countryCodeHidden');

            countrySelect.addEventListener('change', function () {
                const selectedOption = countrySelect.options[countrySelect.selectedIndex];
                const selectedCode = selectedOption.getAttribute('data-code');
                countryCodeInput.value = selectedCode;
            });

            countrySelect.dispatchEvent(new Event('change'));
        });
    </script>
@endpush
