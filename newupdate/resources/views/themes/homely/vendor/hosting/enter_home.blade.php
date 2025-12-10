@extends(template().'layouts.user')
@section('title',trans('Enter Home'))
@section('content')
    <section class="enter-home-search">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="enter-home-info">
                        <div class="enter-home-info-inner">
                            <div class="enter-home-info-title">
                                <h2>@lang('Your home could')</h2>

                                <h2>@lang('make') <span id="minLabel"></span></h2>
                                <h2>@lang('on VibeStay')</h2>
                            </div>
                            <div class="home-range-slider">
                                <h5><span id="minDisplay">1 </span> @lang('Night') <span>. {{ userCurrencyPosition($minimum_price) }}/@lang('night')</span></h5>
                                <p>@lang('Learn how we') <a href="#" data-bs-target="#earningInfoModal" data-bs-toggle="modal">@lang('estimate earnings')</a></p>
                                <div id="priceRange"></div>
                            </div>
                            <div class="enter-home-search-btn d-flex flex-column gap-2">
                                <a class="btn-2" href="{{ route('user.listing.introduction.setup') }}" {{ auth()->user()->role = 1 ? 'disabled' : '' }}>
                                    <div class="btn-wrapper">
                                        <div class="main-text btn-single">
                                            @lang('Add Your Home')
                                        </div>
                                        <div class="hover-text btn-single">
                                            @lang('Add Your Home')
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="enter-home-map">
                        <div id="enterMap" class="map-container"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="co-host">
        <div class="container">
            <div class="common-title">
                <h3>@lang('A co-host can do the hosting for you')</h3>
                <p>@lang('Now you can hire a high-quality, local co‑host to take care of your home and guests.')</p>
            </div>
            <div class="row">
                @foreach($hosts ?? [] as $host)
                    <div class="col-lg-3 col-md-6">
                        <div class="co-host-single">
                            <div class="co-host-single-image">
                                <img src="{{ getFile($host->image_driver, $host->image) }}" alt="{{ $host->firstname . ' ' . $host->lastname }}">
                            </div>
                            <div class="co-host-single-designation">
                                <h4>{{ $host->firstname . ' ' . $host->lastname }}</h4>
                                <p>@lang('Co-host in') {{ $host->city ? $host->city . ',' : '' }} {{ $host->country ?? '' }}</p>
                            </div>
                            <div class="co-host-single-info">
                                <ul>
                                    <li>
                                        <h6><i class="fas fa-star"></i> {{ number_format($host->vendorInfo->avg_rating ?? 0, 1) }}</h6>
                                        <p>@lang('guest rating')</p>
                                    </li>
                                    <li class="border"></li>
                                    <li>
                                        <h6>
                                            @if(!empty($host->vendorInfo->created_at))
                                                {{ number_format(\Carbon\Carbon::parse($host->vendorInfo->created_at)->diffInDays(now()) / 365, 2) }}+
                                            @else
                                                0.00+
                                            @endif
                                        </h6>
                                        <p>@lang('years hosting')</p>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <div class="modal fade" id="earningInfoModal" tabindex="-1" aria-labelledby="earningInfoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="earningInfoModalLabel">@lang('How we estimate your earning potential')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="enter-area-container">
                        <div class="listing-container">
                            <p>@lang('To estimate your earnings, we review the past 12 months of booking data from similar '. basicControl()->site_title. ' listings.')</p>
                            <p>@lang('We choose these listings based on the information you share about your place.')</p>
                            <p>@lang('If you enter an address, you\'ll get a more specific estimate based on the listings closest to you.')</p>
                            <p>@lang('If you enter an area, we look at the top 50% of similar listings in that area, based on their earnings.')</p>
                            <p>@lang('Based on these similar listings, we estimate the average nightly earnings and multiply that number by the number of nights you indicate you will host.')</p>
                            <p>@lang('We also provide the average number of nights booked per month in your area, assuming places are available on '. basicControl()->site_title. ' every night of the month.')</p>
                            <p>@lang('Nightly earnings are the price set by each Host minus the '. basicControl()->site_title. ' Host service fee.')</p>
                            <p>@lang('We don\'t subtract taxes or hosting expenses.')</p>
                            <p>@lang('Your actual earnings will depend on several factors, including your availability, price, and the demand in your area.')</p>
                            <p>@lang('Your ability to host may also depend on local laws.')</p>
                            <p>@lang('Learn more about responsible hosting.')</p>
                            <p>@lang('These earning estimates are not an appraisal or estimate of property value.')</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('style')
    <style>

        .disabled-overlay {
            position: relative;
            pointer-events: none;
            opacity: 0.5;
        }
        .disabled-overlay::after {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: white;
            opacity: 0.4;
            z-index: 1;
            border-radius: 5px;
        }

        .map-container {
            width: 100%;
            height: 500px;
            border-radius: 20px;
            overflow: hidden;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset(template(true) . 'js/nouislider.min.js') }}"></script>

    <script>
        const minimum_price = '{{ $minimum_price }}';
        const currency_symbol = '{{ userCurrencySymbol() }}';
        const mapId = '{{ $googleMapId }}';
        const mapKey = '{{ $googleMapApiKey }}';

        if ($('.home-range-slider').length) {
            $(document).ready(function () {
                let priceSlider = document.getElementById('priceRange');

                noUiSlider.create(priceSlider, {
                    start: [1, 30],
                    connect: true,
                    range: { 'min': 1, 'max': 30 },
                    format: {
                        to: function (value) { return Math.round(value); },
                        from: function (value) { return Number(value); }
                    }
                });

                priceSlider.noUiSlider.on('update', function (values, handle) {
                    let minValue = values[0];
                    let maxValue = values[1];
                    let customMinLabelValue = minValue * minimum_price;
                    let customMaxLabelValue = maxValue * minimum_price;

                    $('#minDisplay').text(minValue);
                    $('#maxDisplay').text(maxValue);
                    $('#minLabel').text(currency_symbol + customMinLabelValue);
                    $('#maxLabel').text(currency_symbol + customMaxLabelValue);
                });
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            const privateRoomTab = document.getElementById("nav-profile-tab");
            const privateRoomContent = document.querySelector("#nav-profile .listing-room-single");

            if (privateRoomTab && privateRoomContent) {
                privateRoomTab.addEventListener("click", function () {
                    privateRoomContent.classList.add("disabled-overlay");
                });

                privateRoomContent.classList.remove("disabled-overlay");
            }
        });

        function loadGoogleMaps(callback) {
            if (typeof google !== "undefined" && google.maps) {
                callback();
                return;
            }

            if (!document.getElementById("googleMapsScript")) {
                const script = document.createElement("script");
                script.id = "googleMapsScript";
                script.src = `https://maps.googleapis.com/maps/api/js?key=${mapKey}&callback=${callback.name}&libraries=marker`;
                script.defer = true;
                script.async = true;
                document.head.appendChild(script);
            }
        }

        function initMap() {
            const defaultLocation = { lat: 40.7128, lng: -74.0060 };

            const initializeMap = (location, message = null) => {
                if (message) {
                    Notiflix.Notify.warning(message);
                }

                const map = new google.maps.Map(document.getElementById("enterMap"), {
                    center: location,
                    zoom: 13,
                    maxZoom: 20,
                    zoomControl: true,
                    zoomControlOptions: { position: google.maps.ControlPosition.RIGHT_CENTER },
                    streetViewControl: false,
                    fullscreenControl: false,
                    gestureHandling: 'greedy',
                    mapTypeControl: false,
                    scaleControl: true,
                    mapId: mapId
                });

                const markerDiv = document.createElement("div");
                markerDiv.style.backgroundColor = "white";
                markerDiv.style.padding = "5px";
                markerDiv.style.borderRadius = "5px";
                markerDiv.innerText = "You are here";

                new google.maps.marker.AdvancedMarkerElement({
                    map: map,
                    position: location,
                    content: markerDiv,
                    title: "You are here"
                });
            };

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const userLocation = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude
                        };
                        initializeMap(userLocation);
                    },
                    function(error) {
                        initializeMap(defaultLocation, "Geolocation blocked or failed. Using New York as default.");
                    }
                );
            } else {
                initializeMap(defaultLocation, "Geolocation not supported. Using New York as default.");
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            loadGoogleMaps(initMap);
        });
    </script>
@endpush

