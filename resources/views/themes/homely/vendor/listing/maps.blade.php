@extends(template().'layouts.user')
@section('title',trans('Maps'))
@section('content')
    <section class="listing-details-1 listing-map">

        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')
            <form id="mapForm" action="{{ route('user.listing.map.save') }}" method="post">
                @csrf

                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id ?? '' }}">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <h3>@lang("Where's your place located?")</h3>
                        <p>@lang("Your address is only shared with guests after they’ve made a reservation.")</p>
                        <div class="form-group mb-3">
                            <label for="destination" class="form-label">@lang('Select Destination')</label>
                            <select id="destination" name="destination" class="form-control soValue">
                                <option value="">@lang('-- Choose Destination --')</option>
                                @foreach($destinations as $item)
                                    <option value="{{ $item->id }}"
                                            data-lat="{{ $item->lat }}"
                                            data-lng="{{ $item->long }}"
                                            data-image-url="{{ getFile($item->thumb_driver, $item->thumb) }}"
                                            data-address="{{ $item->cityTake->name.', '.$item->stateTake->name.', '.$item->countryTake->name }}"
                                            data-title="{{ $item->title }}"
                                            {{ $property->destination_id == $item->id ? 'selected' : '' }}>
                                        {{ $item->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="header-right-form search-box top-search-box">
                            <div class="hrader-search-input select-option top-select-option">
                                <input type="text" name="full_address" class="soValue optionSearch top-optionSearch" value="{{ $property->address ?? '' }}" id="full_address" placeholder="@lang('Search for a place')">
                                <i class="fa-light fa-location-dot"></i>
                            </div>
                        </div>
                        <div class="listing-map-container">
                            <div id="mapContainer" style="height: 400px; border-radius: 8px;"></div>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="lat" id="clicked_lat" value="{{ $property->lat ?? '' }}" />
                <input type="hidden" name="lng" id="clicked_lng" value="{{ $property->long ?? '' }}" />
                <input type="hidden" name="country" id="country" value="{{ $property->country ?? '' }}" />
                <input type="hidden" name="state" id="state" value="{{ $property->state ?? '' }}" />
                <input type="hidden" name="city" id="city" value="{{ $property->city ?? '' }}" />
                <input type="hidden" name="zip_code" id="zip_code" value="{{ $property->zip_code ?? '' }}" />


                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.types', ['property_id' => $property->id]) }}" class="prev-btn"> @lang('Back')</a>
                    <button type="submit" class="next-btn"> @lang('Next')</button>
                </div>
            </form>
        </div>
    </section>

@endsection
@push('style')
    <style>
        .select2-container .select2-selection--single {
            height: 40px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px;
        }
        .mapPropertyBox{
            max-width:230px;
        }
        .mapPropertyBox .mapImage{
            width:100%;
            border-radius:6px;
            margin-bottom:8px;
            height: 120px;
        }
        .mapPropertyBox .mapTitle{
            color:#333;
            text-decoration:none;
            font-size:16px;
        }
        .mapPropertyBox .address{
            margin:4px 0;
            font-size: 14px;
        }
        .mapPropertyBox .direction-link{
            color:#1a73e8;
            text-decoration:underline;
        }
        .gm-style-iw.gm-style-iw-c{
            height: 300px !important;
        }
        .gm-ui-hover-effect{
            position: absolute !important;
            top: 20px;
            right: 21px;
            border: 1px solid var(--border-2) !important;
            border-radius: 50%;
            background: #fff !important;
            font-size: 6px;
        }
    </style>
@endpush

@push('script')
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                @foreach ($errors->all() as $error)
                Notiflix.Notify.failure(@json($error));
                @endforeach
            });
        </script>
    @endif

    <script>
        const googleMapApiKey = "{{ $googleMapApiKey ?? '' }}";
        const mapId = "{{ $googleMapId ?? '' }}";
        let map;
        let infoWindow;
        let marker;

        // Use property destination if available, else default to New York
        const savedLat = parseFloat("{{ $property->destination->lat ?? '40.7128' }}");
        const savedLng = parseFloat("{{ $property->destination->long ?? '-74.0060' }}");
        const defaultLat = 40.7128;
        const defaultLng = -74.0060;

        // Load Google Maps script
        if (googleMapApiKey) {
            const googleScript = document.createElement('script');
            googleScript.src = `https://maps.googleapis.com/maps/api/js?key=${googleMapApiKey}&libraries=marker&callback=initGoogleMap`;
            googleScript.async = true;
            googleScript.defer = true;
            document.head.appendChild(googleScript);
        }

        // Helper to generate popup content
        function getPopupContent(lat, lng, label, address, imageUrl, noImageUrl) {
            return `
                <div class="mapPropertyBox">
                    <img class="mapImage" src="${imageUrl}" alt="${label}" onerror="this.src='${noImageUrl}';">
                    <strong class="mapTitle">${label}</strong><br>
                    <p class="address">${address || 'Unknown address'}</p>
                </div>
            `;
        }

        // Initialize map
        function initGoogleMap() {
            if (!isNaN(savedLat) && !isNaN(savedLng)) {
                loadGoogleMap(savedLat, savedLng);
            } else if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    position => loadGoogleMap(position.coords.latitude, position.coords.longitude),
                    error => {
                        Notiflix.Notify.warning("Geolocation blocked. Using default location (New York).");
                        loadGoogleMap(defaultLat, defaultLng);
                    },
                    { timeout: 10000 }
                );
            } else {
                Notiflix.Notify.warning("Geolocation not supported. Using default location (New York).");
                loadGoogleMap(defaultLat, defaultLng);
            }
        }

        // Load Google Map at given coordinates
        function loadGoogleMap(lat, lng) {
            const center = { lat, lng };
            map = new google.maps.Map(document.getElementById('mapContainer'), {
                center,
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

            const { AdvancedMarkerElement } = google.maps.marker;
            const latLng = { lat, lng };

            // Add marker
            marker = new AdvancedMarkerElement({
                position: latLng,
                map,
                title: "Selected Location"
            });

            // Set hidden input values
            $('#clicked_lat').val(lat);
            $('#clicked_lng').val(lng);

            // Show initial InfoWindow
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: latLng }, (results, status) => {
                let displayName = "Unknown address";
                let label = "Selected Location";

                if (status === "OK" && results[0]) {
                    displayName = results[0].formatted_address.replace(/^[^,]+,\s*/, "");
                    label = results[0].address_components.find(c => c.types.includes("locality"))?.long_name
                        || results[0].address_components.find(c => c.types.includes("administrative_area_level_1"))?.long_name
                        || "Selected Location";
                }

                const noImageUrl = '{{ asset(template(true)."img/no_image.png") }}';
                const imageUrl = noImageUrl;

                if (infoWindow) infoWindow.close();
                infoWindow = new google.maps.InfoWindow({
                    content: getPopupContent(lat, lng, label, displayName, imageUrl, noImageUrl)
                });
                infoWindow.open(map, marker);
            });

            // Map click listener
            map.addListener("click", function (e) {
                handleMapClick(e.latLng.lat(), e.latLng.lng());
            });

            // Destination select change listener
            $('#destination').on('change', handleDestinationChange);
        }

        // Handle destination select change
        function handleDestinationChange() {
            const selected = $(this).find('option:selected');

            const lat = parseFloat(selected.data('lat'));
            const lng = parseFloat(selected.data('lng'));
            const label = selected.data('title') || "Selected Location";
            const address = selected.data('address') || '';
            const imageUrl = selected.data('imageUrl') || 'https://dummyimage.com/400x200/cccccc/000000&text=No+Image';

            const latLng = new google.maps.LatLng(lat, lng);
            map.setCenter(latLng);

            if (marker) {
                marker.position = latLng;
            } else {
                const { AdvancedMarkerElement } = google.maps.marker;
                marker = new AdvancedMarkerElement({
                    position: latLng,
                    map: map,
                    title: label
                });
            }

            if (infoWindow) infoWindow.close();
            infoWindow = new google.maps.InfoWindow({
                content: getPopupContent(lat, lng, label, address, imageUrl, imageUrl)
            });
            infoWindow.open(map, marker);

            $('#clicked_lat').val(lat);
            $('#clicked_lng').val(lng);
            $('#country, #state, #city, #zip_code').val('');
            $('#full_address').val(address);
        }

        // Handle manual map clicks
        function handleMapClick(lat, lng) {
            $('#clicked_lat').val(lat);
            $('#clicked_lng').val(lng);

            const latLng = { lat, lng };
            const { AdvancedMarkerElement } = google.maps.marker;

            if (marker) {
                marker.position = latLng;
            } else {
                marker = new AdvancedMarkerElement({
                    position: latLng,
                    map: map,
                    title: "Selected Location"
                });
            }

            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({ location: latLng }, (results, status) => {
                if (status === "OK" && results[0]) {
                    let displayName = results[0].formatted_address.replace(/^[^,]+,\s*/, "");
                    const addrComponents = results[0].address_components;

                    const getAddressPart = (type) => {
                        const comp = addrComponents.find(c => c.types.includes(type));
                        return comp ? comp.long_name : '';
                    };

                    $('#country').val(getAddressPart("country"));
                    $('#state').val(getAddressPart("administrative_area_level_1"));
                    $('#city').val(getAddressPart("locality") || getAddressPart("administrative_area_level_2"));
                    $('#zip_code').val(getAddressPart("postal_code"));
                    $('#full_address').val(displayName);

                    const noImageUrl = 'https://dummyimage.com/400x200/cccccc/000000&text=No+Image';
                    let matchedImageUrl = noImageUrl;

                    $('#destination option').each(function () {
                        const dLat = parseFloat($(this).data('lat'));
                        const dLng = parseFloat($(this).data('lng'));
                        const threshold = 0.0001;

                        if (Math.abs(lat - dLat) < threshold && Math.abs(lng - dLng) < threshold) {
                            matchedImageUrl = $(this).data('image_url') || noImageUrl;
                            return false;
                        }
                    });

                    const label = getAddressPart("locality") || "Selected Location";

                    if (infoWindow) infoWindow.close();
                    infoWindow = new google.maps.InfoWindow({
                        content: getPopupContent(lat, lng, label, displayName, matchedImageUrl, noImageUrl)
                    });
                    infoWindow.open(map, marker);
                }
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            const form = document.getElementById('mapForm');
            const postUrl = form ? form.action : '';
            const redirectUrl = '{{ route('user.listing.location', ['property_id' => $property->id ?? 0]) }}';

            @include(template().'vendor.listing.partials.cmn_script')
        });
    </script>
@endpush

