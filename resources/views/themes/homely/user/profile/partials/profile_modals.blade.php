<div class="modal fade" id="placeModal" tabindex="-1" aria-labelledby="placeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="placeModalLabel">@lang('Select a Place')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <input type="text" id="placeSearchInput" class="form-control" placeholder="@lang('Search for a place')" autocomplete="off">

                <div id="searchResultsContainer" class="search-results-container mt-3">
                    <ul id="searchResults" class="list-group">
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="dynamicModal" tabindex="-1" aria-labelledby="dynamicModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="dynamicModalLabel">@lang('Update')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <input type="text" class="form-control" id="dynamicInput" placeholder="">
            </div>

            <div class="modal-footer bx-shadow-0">
                <button type="button" class="btn btn-primary" id="dynamicSaveBtn">@lang('Save')</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="introModal" tabindex="-1" aria-labelledby="introModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="introModalLabel">@lang('Add Intro')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
            </div>
            <div class="modal-body">
                <textarea id="introTextarea" class="form-control" rows="5" placeholder="@lang('Write something about yourself...')">{{ $vendorInfo->intro ?? '' }}</textarea>
            </div>
            <div class="modal-footer bx-shadow-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                <button type="button" class="btn btn-primary" id="saveIntroBtn">
                    <span class="spinner-border spinner-border-sm d-none me-2" id="introSpinner" role="status" aria-hidden="true"></span>
                    @lang('Save')
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="locationModalLabel">@lang('Select a Country')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <input type="text" id="locationSearchInput" class="form-control" placeholder="@lang('Search...')" autocomplete="off">

                <div id="countrySearchResultsContainer" class="search-results-container mt-3">
                    <ul id="locationSearchResults" class="list-group mt-3"></ul>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="languageModal" tabindex="-1" aria-labelledby="languageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="languageModalLabel">@lang('Select a Language')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="languageSelectDropdown">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="dynamicBasicModal" tabindex="-1" aria-labelledby="dynamicBasicModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dynamicBasicModalLabel">@lang('Update')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <input type="text" class="form-control" id="dynamicBasicInput" placeholder="">
                <input hidden="" type="text" class="form-control" id="dynamicBasicType" placeholder="">
            </div>

            <div class="modal-footer bx-shadow-0">
                <button type="button" class="btn btn-primary" id="dynamicBasicSaveBtn">@lang('Save')</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="timeZoneModal" tabindex="-1" aria-labelledby="timeZoneModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="timeZoneModalLabel">@lang('Update')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="timezones">
                    <select
                        class="js-select form-select @error('time_zone') is-invalid @enderror"
                        id="timeZoneLabel" name="time_zone">
                        @foreach(timezone_identifiers_list() as $key => $value)
                            <option value="{{$value}}" {{  (old('time_zone',auth()->user()->time_zone) == $value ? ' selected' : '') }}>{{ $value}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer bx-shadow-0">
                <button type="button" class="btn btn-primary" id="timezoneSave">@lang('Save')</button>
            </div>

        </div>
    </div>
</div>
<div class="modal fade" id="phoneWithCodeModal" tabindex="-1" aria-labelledby="phoneWithCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="phoneWithCodeModalLabel">@lang('Update Phone')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <label class="form-label">@lang('Phone Code')</label>
                <select class="form-select mb-2" id="phoneCodeSelect">
                    @foreach(config('country') as $country)
                        <option value="{{ $country['phone_code'] }}" data-flag="{{ asset($country['flag']) }}" data-length="{{ json_encode($country['phoneLength']) }}" data-name="{{ $country['name'] }}">
                            {{ $country['name'] }} ({{ $country['phone_code'] }})
                        </option>
                    @endforeach
                </select>

                <label class="form-label">@lang('Phone Number')</label>
                <input type="number" class="form-control" id="phoneInput">
            </div>

            <div class="modal-footer bx-shadow-0">
                <button type="button" class="btn btn-primary" id="savePhoneBtn">@lang('Save')</button>
            </div>
        </div>
    </div>
</div>
