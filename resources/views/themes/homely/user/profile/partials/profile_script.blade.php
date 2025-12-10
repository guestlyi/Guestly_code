@push('script')
    <script>
        const currentLanguageId = {{ auth()->user()->language_id ?? 'null' }};

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

        $(document).ready(function() {
            let offset = 0;
            let query = '';
            let loading = false;

            function loadCities(reset = false) {
                if (loading) return;
                loading = true;

                $.ajax({
                    url: '{{ route('getCities') }}',
                    type: 'GET',
                    data: {
                        q: query,
                        offset: offset
                    },
                    success: function(response) {
                        if (reset) {
                            $('#searchResults').empty();
                            offset = 0;
                        }

                        if (response.length === 0 && offset === 0) {
                            $('#searchResults').append('<li class="list-group-item">@lang("No places found")</li>');
                        } else {
                            response.forEach(function(place) {
                                $('#searchResults').append(
                                    `<li class="list-group-item search-item cityData" data-id="${place.id}">
                                ${place.name}
                            </li>`
                                );
                            });

                            $('.load-more').remove();

                            if (response.length === 10) {
                                $('#searchResults').append(`
                                    <li class="list-group-item text-center load-more" style="cursor:pointer;">
                                        <span class="load-more-text">@lang("Load More")</span>
                                        <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                                    </li>
                                `);
                            }
                        }

                        $('#searchResults').find('.spinner-border').addClass('d-none');
                        $('#searchResults').find('.load-more-text').removeClass('d-none');

                        loading = false;
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        $('#searchResults').find('.spinner-border').addClass('d-none');
                        $('#searchResults').find('.load-more-text').removeClass('d-none');
                        loading = false;
                    }
                });
            }

            $('#placeSearchInput').on('keyup', function() {
                query = $(this).val().trim();
                offset = 0;

                if (query.length < 2) {
                    $('#searchResults').empty();
                    return;
                }

                loadCities(true);
            });

            $(document).on('click', '.load-more', function() {
                const $btn = $(this);
                $btn.find('.load-more-text').addClass('d-none');
                $btn.find('.spinner-border').removeClass('d-none');

                offset += 10;
                loadCities();
            });

            $(document).on('click', '.cityData', function() {
                const placeId = $(this).data('id');
                const placeName = $(this).text();

                $('#selectedPlaceLink').html(
                    `<i class="fa-light fa-globe-stand"></i> @lang("Where I've always wanted to go"): ${placeName}`
                );

                $('#placeModal').modal('hide');

                $.ajax({
                    url: '{{ route("user.profile.update") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        type: 'want_to_go',
                        value: placeName
                    },
                    success: function(response) {
                        Notiflix.Notify.success('Successfully saved your favorite place!');
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Notiflix.Notify.failure('Failed to save the place. Please try again.');
                    }
                });
            });

            $('#placeSearchInput').on('focus', function() {
                const selectedPlaceText = $('#placeModalLabel').text();
                if (selectedPlaceText) {
                    $('#placeSearchInput').attr('placeholder', selectedPlaceText);
                }
            });

            let currentAnchor = null;

            $('a[data-bs-toggle="modal"]').on('click', function () {
                currentAnchor = $(this);

                const label = currentAnchor.data('label');
                const type = currentAnchor.data('type');
                const placeholder = `Enter ${label.toLowerCase()}`;
                const currentText = currentAnchor.text().trim().replace(/^.*?:\s*/, '');

                $('#dynamicModalLabel').text(label);
                $('#dynamicInput').val(currentText);
                $('#dynamicInput').attr('placeholder', placeholder);
            });

            $('#dynamicSaveBtn').on('click', function () {
                const value = $('#dynamicInput').val().trim();
                const label = currentAnchor.data('label');
                const type = currentAnchor.data('type');
                const icon = currentAnchor.data('icon');

                if (!value) return;
                $.ajax({
                    url: '{{ route("user.profile.update") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        type: type,
                        value: value
                    },
                    success: function (response) {
                        currentAnchor.html(
                            `<i class="fa-brands fa-${icon}"></i> ${label}: ${value}`
                        );

                        $('#dynamicModal').modal('hide');

                        Notiflix.Notify.success('Successfully updated!');
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);

                        Notiflix.Notify.failure('Failed to save. Please try again.');
                    }
                });
            });
            $('#saveIntroBtn').on('click', function () {
                const introText = $('#introTextarea').val().trim();
                if (!introText) return;

                $('#introSpinner').removeClass('d-none');
                $(this).attr('disabled', true);

                $.ajax({
                    url: '{{ route("user.profile.update") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        type: 'intro',
                        value: introText
                    },
                    success: function (response) {
                        Notiflix.Notify.success('Intro saved successfully!');
                        $('#introModal').modal('hide');
                    },
                    error: function (xhr) {
                        console.error(xhr.responseText);
                        Notiflix.Notify.failure('Failed to save intro.');
                    },
                    complete: function () {
                        $('#introSpinner').addClass('d-none');
                        $('#saveIntroBtn').attr('disabled', false);
                    }
                });
            });

            $(document).on('click', '.add-skill-btn', function (e) {
                e.preventDefault();

                if ($('#skillsList').find('.skill-input').length > 0) return;

                const inputLi = `
                    <li class="skill-input-li">
                        <input type="text" class="skill-input" placeholder="Type a skill and hit Enter">
                    </li>
                `;
                $(this).closest('li').before(inputLi);

                $('#skillsList .skill-input').focus();
            });

            $(document).on('keypress', '.skill-input', function (e) {
                if (e.which === 13) {
                    const value = $(this).val().trim();
                    if (!value) return;

                    const exists = $('#skillsList .skill-item').filter(function () {
                        return $(this).data('skill').toLowerCase() === value.toLowerCase();
                    }).length;

                    if (exists) {
                        Notiflix.Notify.warning('This skill is already added.');
                        return;
                    }

                    const $inputLi = $(this).closest('li');
                    $inputLi.before(`
                        <li class="skill-item" data-skill="${value}">
                            ${value}
                            <span class="remove-skill" style="cursor: pointer; margin-left: 8px;">
                                <i class="fa-light fa-xmark"></i>
                            </span>
                        </li>
                    `);

                    $inputLi.remove();
                }
            });


            $(document).on('click', '.remove-skill', function () {
                $(this).closest('li').remove();
            });

            $('#saveSkillsBtn').on('click', function () {
                const $input = $('#skillsList .skill-input');

                if ($input.length) {
                    const value = $input.val().trim();

                    if (value) {
                        const exists = $('#skillsList .skill-item').filter(function () {
                            return $(this).data('skill').toLowerCase() === value.toLowerCase();
                        }).length;

                        if (!exists) {
                            $input.closest('li').before(`
                                <li class="skill-item" data-skill="${value}">
                                    ${value}
                                    <span class="remove-skill" style="cursor: pointer; margin-left: 8px;">
                                        <i class="fa-light fa-xmark"></i>
                                    </span>
                                </li>
                            `);
                        }
                    }

                    $input.closest('li').remove();
                }

                const skills = [];
                $('#skillsList .skill-item').each(function () {
                    skills.push($(this).data('skill'));
                });

                $.ajax({
                    url: '{{ route("user.profile.update") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        type: 'skills',
                        skill_value: skills
                    },
                    success: function () {
                        Notiflix.Notify.success('Skills updated successfully!');
                    },
                    error: function () {
                        Notiflix.Notify.failure('Failed to update skills. Please try again.');
                    }
                });
            });
        });


        let locationOffset = 0;
        let locationQuery = '';
        let locationLoading = false;
        let currentLocationType = 'Country';
        let currentLocationLabel = '@lang("Select a Country")';

        $('[data-bs-target="#locationModal"]').on('click', function () {
            currentLocationType = $(this).data('type') || 'Country';
            currentLocationLabel = $(this).data('label') || currentLocationType;

            $('#locationModalLabel').text(`Select a ${currentLocationLabel}`);
            $('#locationSearchInput').val('');
            $('#locationSearchResults').empty();
            locationOffset = 0;
            locationQuery = '';
            loadLocationData(true);
        });

        function loadLocationData(reset = false) {
            if (locationLoading) return;
            locationLoading = true;

            $.ajax({
                url: '{{ route('getLocations') }}',
                type: 'GET',
                data: {
                    q: locationQuery,
                    offset: locationOffset,
                    type: currentLocationType.toLowerCase()
                },
                success: function (response) {
                    if (reset) {
                        $('#locationSearchResults').empty();
                        locationOffset = 0;
                    }

                    if (response.length === 0 && locationOffset === 0) {
                        $('#locationSearchResults').append(`<li class="list-group-item text-muted">@lang("No results found")</li>`);
                    } else {
                        response.forEach(function (item) {
                            $('#locationSearchResults').append(`
                        <li class="list-group-item search-item locationData" data-id="${item.id}" style="cursor:pointer;">
                            ${item.name}
                        </li>
                    `);
                        });

                        $('.location-load-more').remove();

                        if (response.length === 10) {
                            $('#locationSearchResults').append(`
                        <li class="list-group-item text-center location-load-more" style="cursor:pointer;">
                            <span class="load-more-text">@lang("Load More")</span>
                            <span class="spinner-border spinner-border-sm ms-2 d-none" role="status" aria-hidden="true"></span>
                        </li>
                    `);
                        }
                    }

                    locationLoading = false;
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    locationLoading = false;
                }
            });
        }

        $('#locationSearchInput').on('keyup', function () {
            locationQuery = $(this).val().trim();
            locationOffset = 0;

            if (locationQuery.length < 2) {
                $('#locationSearchResults').empty();
                return;
            }

            loadLocationData(true);
        });

        $(document).on('click', '.location-load-more', function () {
            $(this).find('.load-more-text').addClass('d-none');
            $(this).find('.spinner-border').removeClass('d-none');

            locationOffset += 10;
            loadLocationData();
        });

        $(document).on('click', '.locationData', function () {
            const placeName = $(this).text();
            const iconMap = {
                country: 'fa-flag',
                state: 'fa-flag-usa',
                city: 'fa-city'
            };
            const typeLower = currentLocationType.toLowerCase();

            $(`#select${currentLocationType}`).html(
                `<i class="fa-light ${iconMap[typeLower] || 'fa-map'}"></i> ${currentLocationType}: ${placeName}`
            );

            bootstrap.Modal.getInstance(document.getElementById('locationModal')).hide();

            $.ajax({
                url: '{{ route("user.basic.profile.update") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: typeLower,
                    value: placeName
                },
                success: function () {
                    Notiflix.Notify.success(`Successfully saved your ${currentLocationType}!`);
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    Notiflix.Notify.failure('Failed to save the place. Please try again.');
                }
            });
        });

        $('#selectLanguageBtn').on('click', function () {
            $.ajax({
                url: '{{ route("getLanguage") }}',
                method: 'GET',
                beforeSend: function () {
                    $('.languageSelectDropdown').html('<p>Loading...</p>');
                },
                success: function (res) {
                    let html = '';
                    if (res.length > 0) {
                        html += '<ul class="list-group">';
                        res.forEach(lang => {
                            const checked = (lang.id === currentLanguageId) ? 'checked' : '';
                            html += `<li class="list-group-item d-flex align-items-center gap-2">
                            <label class="d-flex align-items-center gap-2 m-0 w-100">
                                <input type="radio" name="language_id" value="${lang.id}" data-name="${lang.name}" class="me-2" ${checked}>
                                <img src="${lang.imageurl}" alt="${lang.name}" width="24" height="16" style="object-fit: contain;">
                                <span>${lang.name}</span>
                            </label>
                        </li>`;
                        });
                        html += '</ul>';
                    } else {
                        html = '<p>No languages found.</p>';
                    }
                    $('.languageSelectDropdown').html(html);
                },
                error: function () {
                    $('.languageSelectDropdown').html('<p>Something went wrong!</p>');
                }
            });
        });
        $(document).on('change', 'input[name="language_id"]', function () {
            const selectedId = $(this).val();
            const selectedName = $(this).data('name');

            $.ajax({
                url: '{{ route("user.basic.profile.update") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: 'language_id',
                    value: selectedId
                },
                success: function (response) {
                    Notiflix.Notify.success('Language updated successfully!');
                    $('#languageModal').modal('hide');
                    $('#selectLanguageBtn').html(`<i class="far fa-language"></i> {{ __('Language') }} : ${selectedName}`);
                    window.location.reload();
                },
                error: function () {
                    Notiflix.Notify.failure('Failed to update language.');
                }
            });
        });
        $('#dynamicBasicSaveBtn').on('click', function () {
            const value = $('#dynamicBasicInput').val().trim();
            const type = $('#dynamicBasicType').val().trim();

            if (!value) return;
            $.ajax({
                url: '{{ route("user.basic.profile.update") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: type,
                    value: value
                },
                success: function (response) {
                    currentAnchor.html(
                        `<i class="fa-brands fa-${icon}"></i> ${label}: ${value}`
                    );

                    $('#dynamicModal').modal('hide');

                    Notiflix.Notify.success('Successfully updated!');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);

                    Notiflix.Notify.failure('Failed to save. Please try again.');
                }
            });
        });

        let currentAnchor;
        let label;
        let icon;

        $('[data-bs-target="#dynamicBasicModal"]').on('click', function () {
            currentAnchor = $(this);
            const type = $(this).data('type');
            label = $(this).data('label');
            icon = $(this).find('i').attr('class').split(' ')[1]?.replace('fa-', '') || 'edit'; // fallback icon

            $('#dynamicBasicType').val(type);

            const existingText = $(this).text().split(':')[1]?.trim() || '';
            $('#dynamicBasicInput').val(existingText);
        });

        $('#dynamicBasicSaveBtn').on('click', function () {
            const value = $('#dynamicBasicInput').val().trim();
            const type = $('#dynamicBasicType').val().trim();

            if (!value) return;

            $.ajax({
                url: '{{ route("user.basic.profile.update") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: type,
                    value: value
                },
                success: function (response) {
                    currentAnchor.html(
                        `<i class="fa-brands fa-${icon}"></i> ${label}: ${value}`
                    );

                    $('#dynamicBasicModal').modal('hide');
                    Notiflix.Notify.success('Successfully updated!');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    Notiflix.Notify.failure('Failed to save. Please try again.');
                }
            });
        });


        let currentAnchorTimeZone = null;

        $('[data-bs-target="#timeZoneModal"]').on('click', function () {
            currentAnchorTimeZone = $(this);
        });

        $('#timezoneSave').on('click', function () {
            const value = $('#timeZoneLabel').val();

            if (!value) return;

            $.ajax({
                url: '{{ route("user.basic.profile.update") }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: 'time_zone',
                    value: value
                },
                success: function () {
                    if (currentAnchorTimeZone) {
                        const icon = currentAnchorTimeZone.find('i').attr('class').split(' ')[1]?.replace('fa-', '') || 'calendar-check';
                        const label = currentAnchorTimeZone.data('label') || 'Time Zone';
                        currentAnchorTimeZone.html(`<i class="fa-regular fa-${icon}"></i> ${label}: ${value}`);
                    }

                    $('#timeZoneModal').modal('hide');
                    Notiflix.Notify.success('Successfully updated!');
                },
                error: function (xhr) {
                    console.error(xhr.responseText);
                    Notiflix.Notify.failure('Failed to save. Please try again.');
                }
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            const phoneModal = document.getElementById('phoneWithCodeModal');
            const phoneSelect = document.getElementById('phoneCodeSelect');
            const phoneInput = document.getElementById('phoneInput');
            const saveBtn = document.getElementById('savePhoneBtn');
            const countryData = @json(config('country'));

            phoneModal.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;
                const phoneCode = trigger.getAttribute('data-phone-code') ?? '';
                const phone = trigger.getAttribute('data-phone') ?? '';

                for (let option of phoneSelect.options) {
                    if (option.value === phoneCode) {
                        option.selected = true;
                        setPhoneLength(option);
                        break;
                    }
                }

                phoneInput.value = phone;
            });

            phoneSelect.addEventListener('change', function () {
                const selectedOption = this.options[this.selectedIndex];
                setPhoneLength(selectedOption);
            });

            saveBtn.addEventListener('click', function () {
                const phoneCode = phoneSelect.value.trim();
                const phone = phoneInput.value.trim();

                const selectedOption = phoneSelect.options[phoneSelect.selectedIndex];
                const phoneLengthAttr = selectedOption.getAttribute('data-length');
                let validLengths = [];

                try {
                    validLengths = JSON.parse(phoneLengthAttr);
                } catch {
                    validLengths = [];
                }

                if (typeof validLengths === 'number') {
                    validLengths = [validLengths];
                }

                if (validLengths.length && !validLengths.includes(phone.length)) {
                    Notiflix.Notify.failure(`Phone number must be ${validLengths.join(' or ')} digits long.`);
                    return;
                }

                fetch("{{ route('user.basic.phone.update') }}", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        type: 'phone',
                        phone_code: phoneCode,
                        phone: phone
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            Notiflix.Notify.failure('Failed to save. Please try again.');
                        }
                    });
            });

            function setPhoneLength(option) {
                const phoneLengthAttr = option.getAttribute('data-length');
                let lengths = [];

                try {
                    lengths = JSON.parse(phoneLengthAttr);
                } catch {
                    lengths = [];
                }

                if (typeof lengths === 'number') {
                    lengths = [lengths];
                }

                const maxLength = Math.max(...lengths, 15);
                const minLength = Math.min(...lengths, 4);

                phoneInput.setAttribute('maxlength', maxLength);
                phoneInput.setAttribute('minlength', minLength);
            }
        });
    </script>
@endpush
