@extends('admin.layouts.app')
@section('page_title', __('Destination Add'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Manage Destination')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Destination Add')</h1>
                </div>
            </div>
        </div>
        <div class="alert alert-soft-dark mb-5" role="alert">
            <div class="d-flex align-items-center">
                <div class="flex-shrink-0">
                    <img class="avatar avatar-xl alert_image"
                         src="{{ asset('assets/admin/img/oc-megaphone.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="default">
                    <img class="avatar avatar-xl alert_image"
                         src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                         alt="Image Description" data-hs-theme-appearance="dark">
                </div>

                <div class="flex-grow-1 ms-3">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">
                            @lang("To generate the map, a valid Google Maps API key is required. Please ensure you have a valid key configured to proceed.")
                            <a type="button"
                               class="btn btn-white btn-sm getApi"
                               data-bs-toggle="modal"
                               data-bs-target="#getApiKey"
                            >@lang('How to get Map Api Key?')</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12 mb-3 mb-lg-0">
                <form action="{{ route('admin.destination.store') }}" method="post" enctype="multipart/form-data">
                    @csrf

                    <div class="card mb-3 mb-lg-5">
                        <div class="card-header">
                            <a type="button" href="{{ route('admin.all.destination') }}" class="btn btn-info float-end"><i class="bi bi-arrow-left"></i>@lang('Back')</a>
                            <h4 class="card-header-title">@lang('Destination information')</h4>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label for="productNameLabel" class="form-label">@lang('Name')
                                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type your destination name here..."></i>
                                </label>
                                <input type="text" class="form-control" name="name" id="nameLabel" placeholder="e.g dhaka" aria-label="name" value="{{ old('name') }}" onkeyup="generateSlug()">
                                @error('name')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="slug" class="form-label">@lang('Slug')
                                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="destination slug"></i>
                                </label>
                                <input type="text" class="form-control" name="slug" id="slug" aria-label="slug" value="{{ old('slug') }}">
                                @error('slug')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-sm-4">
                                    <div class="mb-4">
                                        <label class="CountryLevel" for="country">@lang('Country')</label>
                                        <select id="country" class="form-control js-select" name="country">
                                            <option value="" disabled selected>@lang('Select Country')</option>
                                            @foreach($location as $item)
                                                <option value="{{$item->id}}">@lang($item->name)</option>
                                            @endforeach
                                        </select>
                                        @error('country')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <div class="mb-4">
                                        <label for="state">@lang('State')</label>
                                        <select name="state" id="state" class="form-control js-select">
                                        </select>
                                        @error('state')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-4">
                                    <div class="mb-4">
                                        <label for="city">@lang('City')</label>
                                        <select name="city" id="city" class="form-control js-select">
                                        </select>
                                        @error('city')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="map">@lang('Map')</label>
                                        <input class="form-control" name="full_address" type="text" id="mapInput" placeholder="Click to View Map" readonly />
                                        <div id="mapModal" class="d-none position-fixed top-50 start-50 translate-middle p-3 bg-white border shadow-lg" style="width: 60%; height: 60%; z-index: 1000;">
                                            <input class="form-control mb-2" id="search" type="text" placeholder="Search location" />
                                            <div id="map" style="width: 100%; height: 90%;"></div>
                                            <a class="btn btn-danger mt-2 w-100" onclick="closeMap()">Close</a>
                                        </div>
                                        @error('map')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="lat">@lang('Latitude')</label>
                                        <input class="form-control" name="lat" type="text" id="lat" value="{{ old('lat') }}" placeholder="Select Location For Get Latitude" readonly />

                                        @error('lat')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="long">@lang('Longitude')</label>
                                        <input class="form-control" name="long" type="text" id="long" value="{{ old('long') }}" placeholder="Select Location For Get Longitude" readonly />

                                        @error('long')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <div class="justify-content-between">
                                            <div class="form-group">
                                                <a href="javascript:void(0)" class="btn btn-success float-left mt-3 generate">
                                                    <i class="fa fa-plus-circle"></i> @lang('Add Place')
                                                </a>
                                            </div>
                                            <div class="row addedField mt-3 col-12"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 mb-lg-5">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-body">
                                    <label class="form-label" for="details">@lang('Destination Details')</label>
                                    <textarea
                                        name="details"
                                        class="form-control summernote"
                                        cols="30"
                                        rows="5"
                                        id="details"
                                        placeholder="destination details"
                                    ></textarea>
                                    @error('details')
                                    <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 mb-lg-5">
                        <div class="card-body">
                            <label class="form-label" for="destinationThumbnail">@lang('Destination Thumbnail')</label>
                            <label class="form-check form-check-dashed" for="logoUploader" id="content_img">
                                <img id="previewImage"
                                     class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                     src="{{ asset("assets/admin/img/oc-browse-file.svg") }}"
                                     alt="Image Preview" data-hs-theme-appearance="default">
                                <span class="d-block">@lang("Browse your file here")</span>
                                <input type="file" class="js-file-attach form-check-input" name="thumb"
                                       id="logoUploader" data-hs-file-attach-options='{
                                                                  "textTarget": "#previewImage",
                                                                  "mode": "image",
                                                                  "targetAttr": "src",
                                                                  "allowTypes": [".png", ".jpeg", ".jpg"]
                                                               }'>
                            </label>
                            @error('thumb')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <p>@lang('For better resolution, please use an image with a size of') {{ config('filelocation.destination.size') }} @lang(' pixels.')</p>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">@lang("Save")</button>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="getApiKey" tabindex="-1" aria-labelledby="getApiKeyLabel" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">
                    <h3 class="pb-2">@lang('How to Get a Google Maps API Key')</h3>
                    <ol class="list-group list-group-numbered">
                        <li class="list-group-item">@lang('Go to the') <a href="https://console.cloud.google.com/" target="_blank">@lang('Google Maps Platform')</a></li>
                        <li class="list-group-item">@lang('Log in with your Google account')</li>
                        <li class="list-group-item">@lang('Go to the') <a href="https://console.cloud.google.com/apis/credentials" target="_blank">@lang('Credentials page')</a></li>
                        <li class="list-group-item">@lang('Click') <strong>@lang('Create credentials')</strong></li>
                        <li class="list-group-item">@lang('Select') <strong>@lang('API key')</strong></li>
                        <li class="list-group-item">@lang('Click') <strong>@lang('Close')</strong></li>
                        <li class="list-group-item">@lang('Find your new API key under ')<strong>@lang('API keys')</strong> @lang('on the Credentials page')</li>
                    </ol>
                    <p class="mt-3">@lang('You can restrict your API key to specific domains or websites. This is recommended before using the API key in production.')</p>
                    <h5>@lang('Additional Details')</h5>
                    <ul class="d-flex flex-column align-items-start gap-2">
                        <li>@lang('You need a Google account with billing enabled to create a Google Maps API key.')</li>
                        <li>@lang('You can use the API key to authenticate requests associated with your project for usage and billing purposes.')</li>
                        <li>@lang('You can find your API key again by going to ')<strong>@lang('Keys and credentials')</strong> @lang('and selecting your API key.')</li>
                        <li><a href="https://www.youtube.com/results?search_query=Google+Maps+API+key" target="_blank">@lang('Watch a tutorial on YouTube')</a></li>
                        <li>@lang('Read more in the') <a href="https://developers.google.com/maps/gmp-get-started" target="_blank">@lang('Google Maps Platform documentation')</a></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <div class="d-flex gap-3">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal" aria-label="Close">@lang('Close')</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
    <style>
        .ts-wrapper.form-control{
            height: 45px;
        }
        a.btn.btn-primary.ms-1 {
            padding: 1px 10px;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/timepicker-bs4.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dateandtime.js') }}"></script>
    <script>
        "use strict";
        const googleMapApiKey = "{{ $googleMapApiKey ?? '' }}";
        const mapId = "{{ $googleMapId ?? '' }}";

        function generateSlug() {
            let name = document.getElementById('nameLabel').value;
            let slug = name.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            document.getElementById('slug').value = slug;
        }

        document.getElementById('logoUploader').addEventListener('change', function() {
            let file = this.files[0];
            let reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
            }

            reader.readAsDataURL(file);
        });
        flatpickr('#Date', {
            enableTime: false,
            dateFormat: "Y-m-d",
            minDate: 'today'
        });

        $(document).ready(function(){

            $(".generate").on('click', function () {
                let lang = $(this).data();
                let form = `<div class="col-md-6 pb-2">
                    <div class="form-group">
                        <div class="input-group">
                            <input name="place[]" class="form-control" type="text" value="" required placeholder="{{trans('Enter a place')}}">
                            <span class="input-group-btn">
                                <button class="btn btn-white delete_desc" type="button">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </div>`;
                $(this).parents('.form-group').siblings('.addedField').append(form);
            });

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.col-md-6').remove();
            });

            $('.summernote').summernote({
                height: 200,
                disableDragAndDrop: true,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                }
            });
            HSCore.components.HSTomSelect.init('#category_id', {
                maxOptions: 250,
                placeholder: 'Select category'
            });
            HSCore.components.HSTomSelect.init('#country', {
                maxOptions: 250,
                placeholder: 'Select Country'
            });

            //state dropdown
            $('#country').on('change', function () {
                let idCountry = this.value;

                if ($('#state')[0].tomselect) {
                    $('#state')[0].tomselect.destroy();
                }
                $("#state").html('');

                $.ajax({
                    url: "{{route('admin.fetch.state')}}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "country_id": idCountry,
                    },
                    dataType: 'json',
                    success: function (result) {
                        let stateOptions = '<option value="">-- Select State --</option>';
                        $.each(result.states, function (key, value) {
                            stateOptions += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $("#state").html(stateOptions);

                        HSCore.components.HSTomSelect.init('#state', {
                            maxOptions: 250,
                            placeholder: 'Select State'
                        });
                    }
                });
            });

            //City Dropdown
            $('#state').on('change', function () {
                let idState = this.value;

                if ($('#city')[0].tomselect) {
                    $('#city')[0].tomselect.destroy();
                }

                $("#city").html('');

                $.ajax({
                    url: "{{route('admin.fetch.city')}}",
                    type: "POST",
                    data: {
                        state_id: idState,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (res) {
                        let cityOptions = '<option value="">-- Select City --</option>';
                        $.each(res.cities, function (key, value) {
                            cityOptions += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $("#city").html(cityOptions);

                        HSCore.components.HSTomSelect.init('#city', {
                            maxOptions: 250,
                            placeholder: 'Select City'
                        });
                    }
                });
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            let map, marker;
            let autocomplete;
            let geocoder;

            const googleMapApiKey = "{{ $googleMapApiKey ?? '' }}";


            function initGoogleMap() {
                geocoder = new google.maps.Geocoder();

                map = new google.maps.Map(document.getElementById("map"), {
                    center: { lat: 20, lng: 0 },
                    zoom: 13,
                    maxZoom: 20,
                    zoomControl: true,
                    zoomControlOptions: { position: google.maps.ControlPosition.RIGHT_CENTER },
                    streetViewControl: false,
                    fullscreenControl: false,
                    gestureHandling: 'greedy',
                    mapTypeControl: false,
                    scaleControl: true,
                    mapId: mapId,
                });

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function (position) {
                            const userLocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude
                            };
                            map.setCenter(userLocation);

                            new google.maps.Marker({
                                position: userLocation,
                                map: map,
                                title: "Your Location"
                            });
                        },
                        function () {
                            console.error("Error: Unable to fetch your location.");
                        }
                    );
                } else {
                    console.error("Geolocation is not supported by this browser.");
                }

                map.addListener("click", function (e) {
                    const lat = e.latLng.lat();
                    const lng = e.latLng.lng();
                    placeMarker(lat, lng);

                    geocoder.geocode({ location: { lat, lng } }, function (results, status) {
                        if (status === "OK" && results[0]) {
                            const addressComponents = results[0].address_components.slice(1);
                            const addressValue = addressComponents.map(component => component.long_name).join(', ');
                            document.getElementById("mapInput").value = addressValue;
                        } else {
                            document.getElementById("mapInput").value = "";
                        }
                        closeMap();
                    });
                });

                autocomplete = new google.maps.places.Autocomplete(document.getElementById("search"));
                autocomplete.bindTo("bounds", map);
                autocomplete.addListener("place_changed", function () {
                    const place = autocomplete.getPlace();
                    if (!place.geometry) {
                        Notiflix.Notify.failure("No details available for input: '" + place.name + "'");
                        return;
                    }
                    const lat = place.geometry.location.lat();
                    const lng = place.geometry.location.lng();
                    map.setCenter(place.geometry.location);
                    map.setZoom(13);
                    placeMarker(lat, lng);
                    document.getElementById("mapInput").value = place.formatted_address || place.name;
                });
            }

            function placeMarker(lat, lng, latlngObj = null) {
                if (marker) {
                    marker.setPosition({ lat, lng });
                } else {
                    marker = new google.maps.Marker({
                        position: { lat, lng },
                        map: map,
                    });

                }

                document.getElementById("lat").value = lat;
                document.getElementById("long").value = lng;

            }

            document.getElementById("mapInput").addEventListener("click", function () {
                document.getElementById("mapModal").classList.remove("d-none");

                if (!map) {
                    if (googleMapApiKey) {
                        const script = document.createElement("script");
                        script.src = `https://maps.googleapis.com/maps/api/js?key=${googleMapApiKey}&libraries=places&callback=initGoogleMapCallback`;
                        script.async = true;
                        script.defer = true;
                        document.head.appendChild(script);
                    } else {
                        Notiflix.Notify.failure("Google Maps API key is missing.");
                    }
                }
            });

            window.initGoogleMapCallback = function () {
                initGoogleMap();
            };
        });

        function closeMap() {
            document.getElementById("mapModal").classList.add("d-none");
        }
    </script>
@endpush
