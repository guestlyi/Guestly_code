<div id="preferencesSection" class="card">
    <div class="card-header">
        <h4 class="card-title">@lang('Preferences')</h4>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.affiliate.profile.preferences.update', $affiliate->id) }}" method="POST">
            @csrf

            <div class="row mb-4">
                <label for="languageLabel" class="col-sm-3 col-form-label form-label">@lang('Language')</label>

                <div class="col-sm-9">
                    <div class="tom-select-custom">
                        <select class="js-select form-select" id="languageLabel"
                                data-hs-tom-select-options='{
                                    "searchInDropdown": false
                                }'
                                name="language"
                        >
                            @foreach($languages as $language)
                                <option
                                    value="{{ $language->name }}"
                                    data-img="{{ getFile($language->flag_driver, $language->flag) }}"
                                    {{ $affiliate->language == $language->name ? 'selected' : '' }}
                                >
                                    {{ $language->name }} ({{ $language->short_name }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <label for="timeZoneLabel" class="col-sm-3 col-form-label form-label">@lang('Time zone')</label>

                <div class="col-sm-9">
                    <div class="tom-select-custom">
                        <select class="js-select form-select @error('time_zone') is-invalid @enderror"
                            id="timeZoneLabel" name="time_zone">
                            @foreach(timezone_identifiers_list() as $key => $value)
                                <option value="{{$value}}" {{  (old('time_zone', $affiliate->time_zone) == $value ? ' selected' : '') }}>{{ $value}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">@lang('Save Changes')</button>
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
        HSCore.components.HSTomSelect.init('#languageLabel', {
            maxOptions: 250,
            placeholder: 'Select language',
            render: {
                option: function(data, escape) {
                    return `<span class="d-flex align-items-center">
                    <img class="avatar avatar-xss avatar-circle me-2" src="${escape(data.img)}" alt="Flag" />
                    <span>${escape(data.text)}</span>
                </span>`;
                },
                item: function(data, escape) {
                    return `<span class="d-flex align-items-center">
                    <img class="avatar avatar-xss avatar-circle me-2" src="${escape(data.img)}" alt="Flag" />
                    <span>${escape(data.text)}</span>
                </span>`;
                }
            }
        });
        HSCore.components.HSTomSelect.init('#timeZoneLabel', {
            maxOptions: 250,
            placeholder: 'Select Time Zone'
        })
    </script>
@endpush
