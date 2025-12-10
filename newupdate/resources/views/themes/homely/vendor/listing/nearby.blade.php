@extends(template().'layouts.user')
@section('title',trans('Nearby Places'))
@section('content')
    <section class="listing-details-1 listing-location">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')
            @php
                $nearestPlaces = is_string($property->nearest_places)
                    ? json_decode($property->nearest_places, true)
                    : ($property->nearest_places ?? []);
            @endphp

            <form id="nearByForm" action="{{ route('user.listing.nearby.save') }}" method="post">
                @csrf

                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id ?? '' }}">

                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <h3>@lang('Share information about nearby places')</h3>
                        <p>@lang('This is optional information for your listing item.')</p>
                        <div class="mb-3">
                            <label>@lang('Pick Nearby Places on Map')</label>
                            <div id="nearby-map" style="height: 400px; width: 100%;"></div>
                        </div>

                        <div id="nearby-places-container"></div>
                    </div>
                </div>

                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.location', ['property_id' => $property->id]) }}" class="prev-btn">@lang('Back')</a>
                    <button type="submit" class="next-btn">@lang('Next')</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset(template(true).'css/flatpickr.min.css') }}">
    <style>
        .position-absolute.delete-feature {
            top: -14px;
            right: -14px;
            width: 32px;
            height: 32px;
            background-color: #dc3545;
            color: #fff;
            border: 3px solid #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            z-index: 10;
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.3s ease;
        }
        .position-absolute.delete-feature:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }
        .position-absolute.delete-feature i {
            pointer-events: none;
            font-size: 14px;
        }
    </style>
@endpush
@if ($errors->any())
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            @foreach ($errors->all() as $error)
            Notiflix.Notify.failure(@json($error));
            @endforeach
        });
    </script>
@endif
@push('script')
    <script type="module">
        import { Loader } from "https://unpkg.com/@googlemaps/js-api-loader@1.15.1/dist/index.esm.js";

        let map;
        let markers = [];
        let polylines = [];
        const latValue = "{{ $property->latitude }}";
        const lngValue = "{{ $property->longitude }}";

        const propertyLat = isNaN(parseFloat(latValue)) ? 0 : parseFloat(latValue);
        const propertyLng = isNaN(parseFloat(lngValue)) ? 0 : parseFloat(lngValue);
        const nearbyContainer = document.getElementById('nearby-places-container');
        const existingNearbyPlaces = @json($nearestPlaces);
        const mapId = '{{ $googleMapId }}';
        const apiKey = "{{ $googleMapApiKey }}";

        const loader = new Loader({
            apiKey: apiKey,
            version: "weekly",
            libraries: ["marker"]
        });

        loader.load().then(() => {
            initNearbyMap();
        });

        function initNearbyMap() {
            map = new google.maps.Map(document.getElementById("nearby-map"), {
                center: { lat: propertyLat, lng: propertyLng },
                zoom: 8,
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

            new google.maps.marker.AdvancedMarkerElement({
                position: { lat: propertyLat, lng: propertyLng },
                map: map,
                title: "@lang('Property Location')"
            });

            loadExistingNearbyPlaces();

            map.addListener("click", function(e) {
                addNearbyPlaceMarker(e.latLng.lat(), e.latLng.lng());
            });
        }

        function loadExistingNearbyPlaces() {
            const nearbyPlacesArray = Object.values(existingNearbyPlaces);
            if (nearbyPlacesArray.length && !document.getElementById('existing-places-label')) {
                const label = document.createElement('h5');
                label.id = 'existing-places-label';
                label.textContent = 'Existing Places';
                label.className = 'mb-3';
                nearbyContainer.appendChild(label);
            }

            nearbyPlacesArray.forEach((place, index) => {
                addMarkerFromData(place.lat, place.lng, place.title, place.distance, index);
            });
        }

        function addMarkerFromData(lat, lng, title = '', distance = '', index = null) {
            if (isNaN(lat) || isNaN(lng)) return;

            const marker = new google.maps.marker.AdvancedMarkerElement({
                position: { lat: parseFloat(lat), lng: parseFloat(lng) },
                map: map,
                draggable: true
            });
            markers.push(marker);

            if (!distance) {
                distance = calculateDistance(propertyLat, propertyLng, lat, lng);
            }

            if (index === null) index = nearbyContainer.children.length;

            const div = document.createElement('div');
            div.className = "border p-3 mb-3 rounded position-relative";
            div.innerHTML = `
                <button type="button" class="position-absolute delete-feature remove-place-btn" aria-label="Remove">
                    <i class="fas fa-times"></i>
                </button>
                <div class="mb-2">
                    <label>@lang('Place Title')</label>
                    <input type="text" name="nearby_places[${index}][title]" class="form-control" value="${title}" required>
                </div>
                <div class="mb-2">
                    <label>@lang('Distance (km)')</label>
                    <input type="number" step="0.1" name="nearby_places[${index}][distance]" class="form-control" value="${distance}" required>
                </div>
                <input type="hidden" name="nearby_places[${index}][lat]" value="${lat}">
                <input type="hidden" name="nearby_places[${index}][lng]" value="${lng}">
            `;
            nearbyContainer.appendChild(div);

            const titleInput = div.querySelector(`input[name="nearby_places[${index}][title]"]`);

            div.querySelector('.remove-place-btn').addEventListener('click', function() {
                marker.setMap(null);
                if (marker.polyline) marker.polyline.setMap(null);
                div.remove();
            });

            marker.polyline = drawDottedLine(propertyLat, propertyLng, lat, lng);

            marker.addEventListener('dragend', async function(e) {
                const newLat = e.latLng.lat();
                const newLng = e.latLng.lng();
                div.querySelector(`input[name="nearby_places[${index}][lat]"]`).value = newLat;
                div.querySelector(`input[name="nearby_places[${index}][lng]"]`).value = newLng;
                div.querySelector(`input[name="nearby_places[${index}][distance]"]`).value = calculateDistance(propertyLat, propertyLng, newLat, newLng);

                if (marker.polyline) marker.polyline.setMap(null);
                marker.polyline = drawDottedLine(propertyLat, propertyLng, newLat, newLng);
            });
        }

        async function addNearbyPlaceMarker(lat, lng) {
            const index = nearbyContainer.children.length;
            const distance = calculateDistance(propertyLat, propertyLng, lat, lng);

            addMarkerFromData(lat, lng, '', distance, index);
        }

        function drawDottedLine(lat1, lng1, lat2, lng2) {
            const line = new google.maps.Polyline({
                path: [
                    { lat: parseFloat(lat1), lng: parseFloat(lng1) },
                    { lat: parseFloat(lat2), lng: parseFloat(lng2) }
                ],
                strokeOpacity: 0,
                icons: [{
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        scale: 1,
                        strokeOpacity: 1,
                        fillColor: '#0c3bd5',
                        fillOpacity: 1
                    },
                    offset: '0',
                    repeat: '10px'
                }],
                map: map
            });
            polylines.push(line);
            return line;
        }

        function calculateDistance(lat1, lng1, lat2, lng2) {
            const R = 6371;
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLng = (lng2 - lng1) * Math.PI / 180;
            const a =
                Math.sin(dLat / 2) ** 2 +
                Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLng / 2) ** 2;
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return (R * c).toFixed(2);
        }

        const form = document.getElementById('nearByForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.informations') }}';

        @include(template().'vendor.listing.partials.cmn_script')
    </script>
@endpush



