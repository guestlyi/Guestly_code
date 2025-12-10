@extends(template().'layouts.user')
@section('title',trans('Personal Information'))
@section('content')
    @php
        $contentInfo = getPersonalInfo();
    @endphp
    <section class="personal-info">
        <div class="container">
            <div class="personal-info-title">
                <ul>
                    <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                    <li><i class="fa-light fa-chevron-right"></i></li>
                    <li>@lang('Personal info')</li>
                </ul>
                <div class="personal-large-title">
                    <h4>@lang('Personal info')</h4>
                    <a type="button" class="btn-3 manageRelativeBtn" href="{{ route('user.relatives') }}">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                <i class="fas fa-users pe-2"></i>@lang('Saved Guests')
                            </div>
                            <div class="hover-text btn-single">
                                <i class="fas fa-users pe-2"></i>@lang('Saved Guests')
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="personal-info-left">
                        <div class="personal-info-list">
                            <ul class="personal-info-list">
                                <li class="personal-info-item">
                                    <div class="personal-info-list-content">
                                        <h6>@lang('Firstname')</h6>
                                        <p id="firstname-text" class="info-text">{{ auth()->user()->firstname }}</p>

                                        <div class="edit-box d-none" id="firstname-edit">
                                            <input type="text" class="edit-input" id="firstname-input" value="{{ auth()->user()->firstname }}">
                                            <div class="edit-actions">
                                                <button class="btn-save" onclick="saveField('firstname')">@lang('Save')</button>
                                                <button class="btn-cancel" onclick="cancelEdit('firstname')">@lang('Cancel')</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="personal-info-list-link">
                                        <a href="javascript:void(0);" onclick="toggleEdit('firstname')" id="firstname-edit-btn">@lang('Edit')</a>
                                    </div>
                                </li>

                                <li class="personal-info-item">
                                    <div class="personal-info-list-content">
                                        <h6>@lang('Lastname')</h6>
                                        <p id="lastname-text" class="info-text">{{ auth()->user()->lastname }}</p>

                                        <div class="edit-box d-none" id="lastname-edit">
                                            <input type="text" class="edit-input" id="lastname-input" value="{{ auth()->user()->lastname }}">
                                            <div class="edit-actions">
                                                <button class="btn-save" onclick="saveField('lastname')">@lang('Save')</button>
                                                <button class="btn-cancel" onclick="cancelEdit('lastname')">@lang('Cancel')</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="personal-info-list-link">
                                        <a href="javascript:void(0);" onclick="toggleEdit('lastname')" id="lastname-edit-btn">@lang('Edit')</a>
                                    </div>
                                </li>

                                <li class="personal-info-item">
                                    <div class="personal-info-list-content">
                                        <h6>@lang('Username')</h6>
                                        <p id="username-text" class="info-text">{{ auth()->user()->username }}</p>
                                    </div>
                                </li>

                                <li class="personal-info-item">
                                    <div class="personal-info-list-content">
                                        <h6>@lang('Email')</h6>
                                        <p id="email-text" class="info-text">{{ auth()->user()->email }}</p>

                                        <div class="edit-box d-none" id="email-edit">
                                            <input type="email" class="edit-input" id="email-input" value="{{ auth()->user()->email }}">
                                            <div class="edit-actions">
                                                <button class="btn-save" onclick="saveField('email')">@lang('Save')</button>
                                                <button class="btn-cancel" onclick="cancelEdit('email')">@lang('Cancel')</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="personal-info-list-link">
                                        <a href="javascript:void(0);" onclick="toggleEdit('email')" id="email-edit-btn">@lang('Edit')</a>
                                    </div>
                                </li>

                                <li class="personal-info-item">
                                    <div class="personal-info-list-content">
                                        <h6>@lang('Phone')</h6>
                                        <p id="phone-text" class="info-text">{{ auth()->user()->phone_code . auth()->user()->phone }}</p>

                                        <div class="edit-box d-none" id="phone-edit">
                                            <div class="edit-phone-group">
                                                <select class="edit-input select2" id="inline-phone-code" style="width: 100%;">
                                                    @foreach(config('country') as $country)
                                                        <option
                                                            value="{{ $country['phone_code'] }}"
                                                            data-length="{{ json_encode($country['phoneLength']) }}"
                                                            {{ $country['phone_code'] === auth()->user()->phone_code ? 'selected' : '' }}>
                                                            {{ $country['name'] }} ({{ $country['phone_code'] }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="text" class="edit-input mb-0" id="inline-phone-input" value="{{ auth()->user()->phone }}">
                                            </div>
                                            <div class="edit-actions">
                                                <button class="btn-save" onclick="savePhoneField('phone')">@lang('Save')</button>
                                                <button class="btn-cancel" onclick="cancelEdit('phone')">@lang('Cancel')</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="personal-info-list-link">
                                        <a href="javascript:void(0);" onclick="toggleEdit('phone')" id="phone-edit-btn">@lang('Edit')</a>
                                    </div>
                                </li>

                                <li class="personal-info-item">
                                    <div class="personal-info-list-content">
                                        <h6>@lang('Address Line 1')</h6>
                                        <p id="address_one-text" class="info-text">{{ auth()->user()->address_one }}</p>

                                        <div class="edit-box d-none" id="address_one-edit">
                                            <input type="text" class="edit-input" id="address_one-input" value="{{ auth()->user()->address_one }}">
                                            <div class="edit-actions">
                                                <button class="btn-save" onclick="saveField('address_one')">@lang('Save')</button>
                                                <button class="btn-cancel" onclick="cancelEdit('address_one')">@lang('Cancel')</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="personal-info-list-link">
                                        <a href="javascript:void(0);" onclick="toggleEdit('address_one')" id="address_one-edit-btn">@lang('Edit')</a>
                                    </div>
                                </li>

                                <li class="personal-info-item">
                                    <div class="personal-info-list-content">
                                        <h6>@lang('Address Line 2')</h6>
                                        <p id="address_two-text" class="info-text">{{ auth()->user()->address_two }}</p>

                                        <div class="edit-box d-none" id="address_two-edit">
                                            <input type="text" class="edit-input" id="address_two-input" value="{{ auth()->user()->address_two }}">
                                            <div class="edit-actions">
                                                <button class="btn-save" onclick="saveField('address_two')">@lang('Save')</button>
                                                <button class="btn-cancel" onclick="cancelEdit('address_two')">@lang('Cancel')</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="personal-info-list-link">
                                        <a href="javascript:void(0);" onclick="toggleEdit('address_two')" id="address_two-edit-btn">@lang('Edit')</a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1">
                    <div class="personal-info-right">
                        <div class="personal-info-sidebar">
                            @foreach($contentInfo['multiple'] ?? [] as $content)
                                <div class="personal-info-sidebar-content">
                                    <div class="icon">
                                        <img src="{{ getFile($content['media']->image->driver, $content['media']->image->path) }}" alt="@lang('icon')"/>
                                    </div>
                                    <h5>{{ $content['title'] ?? '' }}</h5>
                                    <p>{{ $content['sub_title'] ?? '' }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    <style>
        .btn-4{
            padding: 11px 20px;
        }
        .personal-info-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .personal-info-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 16px 0;
            border-bottom: 1px solid #eee;
        }

        .personal-info-list-content {
            flex: 1;
        }

        .personal-info-list-content h6 {
            font-weight: 600;
            color: #555;
            margin: 0;
        }
        .personal-info-list-content .edit-box input, .personal-info-list-content .edit-box .edit-phone-group{
            margin-bottom: 10px;
        }

        .info-text {
            color: #333;
            margin-top: 4px;
        }

        .edit-box {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            animation: fadeIn 0.3s ease;
        }

        .edit-input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            transition: 0.2s;
        }

        .edit-input:focus {
            border-color: #ff5a5f;
            box-shadow: 0 0 0 2px rgba(255, 90, 95, 0.15);
        }

        .edit-phone-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .edit-input {
            padding: 8px 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
            line-height: 1.4;
            height: 38px;
            box-sizing: border-box;
            outline: none;
        }

        select.edit-input {
            flex: 0 0 100px;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            appearance: none;
            padding-right: 40px;
            height: 38px; /* match input height */
            line-height: 1.4;
        }


        .edit-actions {
            display: flex;
            gap: 10px;
        }

        .btn-save {
            background: #ff5a5f;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-save:hover {
            background: #e04a4e;
        }

        .btn-cancel {
            background: #f5f5f5;
            color: #333;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-cancel:hover {
            background: #eaeaea;
        }

        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(-5px);}
            to {opacity: 1; transform: translateY(0);}
        }

        select.edit-input {
            flex: 0 0 100px;
            cursor: pointer;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236b7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 16px;
            appearance: none;
            padding-right: 40px;
        }
        .nice-select .option {
            line-height: 0px;
            min-height: 34px;
            padding-left: 10px;
            padding-right: 7px;
        }
        .nice-select .list{
            height: 200px;
            overflow: auto;
        }
    </style>
@endpush

@push('script')
    <script>
        function toggleEdit(id) {
            const box = document.getElementById(`${id}-edit`);
            box.classList.toggle('d-none');
            box.classList.toggle('d-block');
        }

        function cancelEdit(id) {
            const box = document.getElementById(`${id}-edit`);
            box.classList.add('d-none');
            box.classList.remove('d-block');
        }

        function saveField(id) {
            const value = document.getElementById(`${id}-input`).value;
            const textEl = document.getElementById(`${id}-text`);

            axios.post("{{ route('user.personalInfo.update') }}", {
                type: id,
                value: value
            }, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(function(response) {
                    textEl.textContent = value || 'Not provided';
                    cancelEdit(id);

                    Notiflix.Notify.success(response.data.message || 'Profile Updated Successfully');
                })
                .catch(function(error) {
                    Notiflix.Notify.failure('Failed to save. Please try again.');
                    console.error(error);
                });
        }

        $(document).ready(function() {
            $('#inline-phone-code').niceSelect();

            const phoneInput = document.getElementById('inline-phone-input');
            const phoneCodeSelect = document.getElementById('inline-phone-code');

            function getValidLengths() {
                const selectedOption = phoneCodeSelect.options[phoneCodeSelect.selectedIndex];
                let validLengths = [];
                try {
                    const parsed = JSON.parse(selectedOption.getAttribute('data-length'));
                    if (Array.isArray(parsed)) {
                        validLengths = parsed.map(n => Number(n));
                    } else if (typeof parsed === 'number') {
                        validLengths = [parsed];
                    }
                } catch {
                    validLengths = [];
                }
                return validLengths;
            }

            phoneInput.addEventListener('input', function () {
                const phone = phoneInput.value.trim();
                const validLengths = getValidLengths();
                if (validLengths.length && phone.length > Math.max(...validLengths)) {
                    Notiflix.Notify.failure(`Phone number cannot exceed ${Math.max(...validLengths)} digits.`);
                }
            });
        });

        function savePhoneField(id) {
            const phone = document.getElementById('inline-phone-input').value.trim();
            const phoneCode = document.getElementById('inline-phone-code').value.trim();
            const textEl = document.getElementById(`${id}-text`);

            const selectedOption = document.querySelector('#inline-phone-code option:checked');
            let validLengths = [];

            try {
                const parsed = JSON.parse(selectedOption.getAttribute('data-length'));
                if (Array.isArray(parsed)) {
                    validLengths = parsed.map(n => Number(n));
                } else if (typeof parsed === 'number') {
                    validLengths = [parsed];
                }
            } catch {
                validLengths = [];
            }

            if (validLengths.length && !validLengths.includes(phone.length)) {
                let message = '';
                if (validLengths.length === 1) {
                    message = `${validLengths[0]} digits long.`;
                } else if (validLengths.length === 2) {
                    message = `${validLengths[0]} or ${validLengths[1]} digits long.`;
                } else {
                    const last = validLengths.pop();
                    message = `${validLengths.join(', ')} or ${last} digits long.`;
                }

                Notiflix.Notify.failure(`Phone number must be ${message}`);
                return;
            }

            axios.post("{{ route('user.basic.phone.update') }}", {
                type: id,
                phone: phone,
                phone_code: phoneCode
            }, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(function (response) {
                    if (response.data.success) {
                        textEl.textContent = phoneCode + phone;
                        cancelEdit(id);
                        Notiflix.Notify.success('Phone updated successfully');
                    } else {
                        Notiflix.Notify.failure('Failed to save. Please try again.');
                    }
                })
                .catch(function (error) {
                    Notiflix.Notify.failure('Request failed. Please try again.');
                    console.error(error);
                });
        }
    </script>
@endpush


