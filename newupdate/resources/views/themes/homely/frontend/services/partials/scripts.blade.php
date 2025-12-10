
@push('script')
    <script src="{{ asset('assets/global/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script>

        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        const bookedDates = @json($bookedDates);

        const googleMapApiKey = "{{ $googleMapApiKey ?? '' }}";
        const mapId = "{{ $googleMapId ?? '' }}";


        tinymce.init({
            selector: '#reportDetails',
            height: 250,
            menubar: false,
            plugins: 'link lists code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link | code',
            branding: false,
            license_key: 'gpl'
        });

        const currencySymbol = "{{ userCurrencySymbol() }}";
        const maxGuests = "{{ $property->features->max_guests }}";
        const bookingRedirectRoute = @json(route('user.booking.guest.info', ['uid' => 'REPLACE_UID']));
        const csrfToken = '{{ csrf_token() }}';

        document.addEventListener('DOMContentLoaded', function () {
            const showBtn = document.querySelector('.near-this-area .show-btn');
            const textContainer = document.querySelector('.places-container.text-container');

            if(showBtn) {
                showBtn.addEventListener('click', function() {
                    textContainer.classList.toggle('show');
                    this.classList.toggle('rotate');
                });
            }
        });
        $(document).ready(function () {
            const maxGuests = '{{ $property->features?->max_guests ?? 10 }}';
            let adultCount = 1, childrenCount = 0, petCount = 0;

            const updateDisplay = () => {
                $(".adult").text(adultCount);
                $(".childeren").text(childrenCount);
                $(".pet").text(petCount);

                $("#adult_count").val(adultCount);
                $("#children_count").val(childrenCount);
                $("#pet_count").val(petCount);
            };

            updateDisplay();

            $(".count").on("click", function (e) {
                e.stopPropagation();
                $(".count").toggleClass("active");
            });

            $(".count-container").on("click", function (e) {
                e.stopPropagation();
            });

            $(document).on("click", function (e) {
                if (!$(e.target).closest(".count").length) {
                    $(".count").removeClass("active");
                }
            });

            const getTotalGuests = () => adultCount + childrenCount;

            $('.increment').click(function (e) {
                e.stopPropagation();
                if (getTotalGuests() < maxGuests) {
                    adultCount++;
                } else {
                    Notiflix.Notify.failure("Maximum total guests reached!");
                }
                updateDisplay();
            });

            $('.decrement').click(function (e) {
                e.stopPropagation();
                if (adultCount > 1) {
                    adultCount--;
                } else {
                    Notiflix.Notify.warning("At least 1 adult is required.");
                }
                updateDisplay();
            });

            $('.incrementTwo').click(function (e) {
                e.stopPropagation();
                if (adultCount === 0) {
                    return Notiflix.Notify.warning("Add at least one adult first!");
                }
                if (getTotalGuests() < maxGuests) {
                    childrenCount++;
                } else {
                    Notiflix.Notify.failure("Maximum total guests reached!");
                }
                updateDisplay();
            });

            $('.decrementTwo').click(function (e) {
                e.stopPropagation();
                if (childrenCount > 0) childrenCount--;
                updateDisplay();
            });

            $('.incrementThree').click(function (e) {
                e.stopPropagation();
                if (adultCount === 0) {
                    return Notiflix.Notify.warning("Add at least one adult first!");
                }
                petCount++;
                updateDisplay();
            });

            $('.decrementThree').click(function (e) {
                e.stopPropagation();
                if (petCount > 0) petCount--;
                updateDisplay();
            });

            const $dateBox = $(".date");
            const $input = $("#date-picker");

            $input.daterangepicker({
                autoUpdateInput: false,
                minDate: moment(),
                locale: { cancelLabel: "Clear", format: "DD/MM/YYYY" }
            });

            $dateBox.on("click", function (e) {
                e.stopPropagation();
                $input.focus();
                $input.data("daterangepicker").show();
                $(".daterangepicker").addClass("show");
            });

            $input.on("apply.daterangepicker", function (ev, picker) {
                $(this).val(
                    picker.startDate.format("DD/MM/YYYY") +
                    " - " +
                    picker.endDate.format("DD/MM/YYYY")
                );
                $(".daterangepicker").removeClass("show");
            });

            $input.on("cancel.daterangepicker", function () {
                $(this).val("");
                $(".daterangepicker").removeClass("show");
            });

            $(document).on("mousedown", ".daterangepicker, .daterangepicker .prev, .daterangepicker .next", function (e) {
                e.stopPropagation();
            });

            $(document).on("mousedown", function (e) {
                const drp = $input.data("daterangepicker");

                if (!$(e.target).closest(".date").length && !$(e.target).closest(".daterangepicker").length) {
                    if (drp) drp.hide();
                    $(".daterangepicker").removeClass("show");
                }

                if (!$(e.target).closest(".count").length) {
                    $(".count-container").removeClass("active");
                }
            });

            $('#bookingForm').submit(function (e) {
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
                            Notiflix.Notify.success('Booking initiated successfully!');
                            const redirectUrl = bookingRedirectRoute.replace('REPLACE_UID', response.booking.uid);
                            window.location.href = redirectUrl;
                        } else {
                            Notiflix.Notify.failure(response.message || 'Something went wrong!');
                        }
                    },
                    error: function (xhr) {
                        Notiflix.Loading.remove();

                        if (xhr.status === 401) {
                            return (window.location.href = "{{ route('login') }}");
                        }

                        const errors = xhr.responseJSON?.errors
                            ? Object.values(xhr.responseJSON.errors).flat().join('\n')
                            : 'Something went wrong.';
                        Notiflix.Notify.failure(errors);
                    }
                });
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            const desc = document.getElementById("description-content");
            const toggleBtn = document.getElementById("toggle-description");

            const fullText = `{!! addslashes($property->description) !!}`;
            const shortText = desc.innerHTML;

            toggleBtn.addEventListener("click", () => {
                const showingFull = desc.innerHTML === shortText;
                desc.innerHTML = showingFull ? fullText : shortText;
                toggleBtn.innerHTML = showingFull
                    ? 'Show Less <i class="fa-light fa-angle-up"></i>'
                    : 'Show More <i class="fa-light fa-angle-right"></i>';
            });
        });

        document.addEventListener("DOMContentLoaded", () => {
            new bootstrap.Tooltip('[data-bs-toggle="tooltip"]');
        });

        $(function () {
            const input = $('input[name="datefilter"]');
            const bookedDatesSet = new Set(bookedDates);

            input.daterangepicker({
                autoUpdateInput: false,
                minDate: moment(),
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY'
                },
                isInvalidDate: function (date) {
                    const formatted = date.format('YYYY-MM-DD');
                    return bookedDatesSet.has(formatted);
                }
            });

            input.on('show.daterangepicker', function (ev, picker) {
                setTimeout(() => {
                    picker.container.find('td.off, td.disabled').each(function () {
                        $(this).attr('title', 'Already booked');
                    });
                }, 10);
            });

            input.on('apply.daterangepicker', function (ev, picker) {
                const start = picker.startDate.clone();
                const end = picker.endDate.clone();
                let hasBlockedDate = false;

                const checkDate = start.clone();
                while (checkDate.isBefore(end)) {
                    const formatted = checkDate.format('YYYY-MM-DD');
                    if (bookedDatesSet.has(formatted)) {
                        hasBlockedDate = true;
                        break;
                    }
                    checkDate.add(1, 'day');
                }

                if (hasBlockedDate) {
                    Notiflix.Notify.failure('Selected date range includes already booked dates. Please choose a different range.');
                    $(this).val('');
                    return;
                }

                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                calculateTotalPrice();
            });

            input.on('cancel.daterangepicker', function () {
                $(this).val('');
            });
        });

        const convertToDate = (dateStr) => {
            const [d, m, y] = dateStr.split('/');
            return new Date(`${y}-${m}-${d}`);
        };

        const calculateTotalPrice = () => {
            const dateRange = $('input[name="datefilter"]').val();
            const [start, end] = dateRange.split(" - ");
            if (!start || !end) return;

            const nights = (convertToDate(end) - convertToDate(start)) / (1000 * 3600 * 24);

            const pricing = {
                nightly: {{ userAmount($property->pricing?->nightly_rate) ?? 0 }},
                weekly: {{ userAmount($property->pricing?->weekly_rate) ?? 0 }},
                monthly: {{ userAmount($property->pricing?->monthly_rate) ?? 0 }},
                cleaning: {{ userAmount($property->pricing?->cleaning_fee) ?? 0 }},
                service: {{ userAmount($property->pricing?->service_fee) ?? 0 }},
            };

            const customTaxes = {!! $taxInfos !!};
            const discounts = {
                enabled: {{ $property->discount }},
                info: {!! json_encode($property->discount_info) !!}
            };

            let totalPrice = 0;
            let fullWeeks = 0;
            let fullMonths = 0;
            let remainingNights = nights;

            if (nights >= 28) {
                fullMonths = Math.floor(nights / 30);
                remainingNights = nights % 30;

                if (remainingNights >= 7) {
                    fullWeeks = Math.floor(remainingNights / 7);
                    remainingNights %= 7;
                }

                totalPrice = fullMonths * pricing.monthly + fullWeeks * pricing.weekly + remainingNights * pricing.nightly;
            } else if (nights >= 7) {
                fullWeeks = Math.floor(nights / 7);
                remainingNights = nights % 7;
                totalPrice = fullWeeks * pricing.weekly + remainingNights * pricing.nightly;
            } else {
                remainingNights = nights;
                totalPrice = nights * pricing.nightly;
            }

            const cleaningFee = pricing.cleaning;
            const serviceFee = pricing.service;

            let taxAmount = 0;

            if (Array.isArray(customTaxes)) {
                customTaxes.forEach(tax => {
                    if (tax.type === 'percentage') {
                        taxAmount += (totalPrice * parseFloat(tax.amount || 0)) / 100;
                    } else if (tax.type === 'fixed') {
                        taxAmount += parseFloat(tax.amount || 0);
                    }
                });
            }

            const taxAndFees = cleaningFee + serviceFee + taxAmount;
            const preDiscountTotal = totalPrice + taxAndFees;

            let discountAmount = 0;

            if (discounts.enabled === 1 && discounts.info) {
                const d = discounts.info;

                if (nights >= 28 && d.monthly?.enabled === 'on') {
                    discountAmount += preDiscountTotal * parseFloat(d.monthly.percent || 0) / 100;
                } else if (nights >= 7 && d.weekly?.enabled === 'on') {
                    discountAmount += preDiscountTotal * parseFloat(d.weekly.percent || 0) / 100;
                } else if (d.new_listing?.enabled === 'on') {
                    discountAmount += preDiscountTotal * parseFloat(d.new_listing.percent || 0) / 100;
                }

                if (d.others) {
                    Object.values(d.others).forEach(o => {
                        if (o.enabled === 'on') {
                            discountAmount += preDiscountTotal * parseFloat(o.percent || 0) / 100;
                        }
                    });
                }
            }

            const finalTotal = preDiscountTotal - discountAmount;

            $('#night-count').text(nights);
            $('#total-night-price').text(`${currencySymbol}${totalPrice}`);
            $('#final-total').text(`${currencySymbol}${finalTotal.toFixed(2)}`);

            if (discountAmount > 0) {
                $('.final-initial-parent').removeClass('d-none');
                $('#final-initial-total').text(`${currencySymbol}${preDiscountTotal.toFixed(2)}`);
            } else {
                $('#final-initial-total').addClass('d-none').text('');
            }

            $('#nightly-rate').text(`${currencySymbol}${pricing.nightly}`);
            $('#weeks-price').text(`${currencySymbol}${pricing.weekly} x ${fullWeeks}`);
            $('#months-price').text(`${currencySymbol}${pricing.monthly} x ${fullMonths}`);
            $('#remaining-nights').text(`${currencySymbol}${pricing.nightly} x ${remainingNights}`);
            $('#cleaning-fee').text(`${currencySymbol}${cleaningFee}`);
            $('#service-fee').text(`${currencySymbol}${serviceFee}`);
            $('#discount-amount').text(`${currencySymbol}${discountAmount.toFixed(2)}`);
            $('#total-price').text(`${currencySymbol}${finalTotal.toFixed(2)}`);


            const $customTaxesContainer = $('.custom-taxes');
            $customTaxesContainer.empty()

            if (Array.isArray(customTaxes)) {
                customTaxes.forEach(tax => {
                    let amount = 0;

                    if (tax.type === 'percentage') {
                        amount = (totalPrice * parseFloat(tax.amount || 0)) / 100;
                    } else if (tax.type === 'fixed') {
                        amount = parseFloat(tax.amount || 0);
                    }

                    $customTaxesContainer.append(`
                        <div class="row mb-2">
                            <div class="col-8 text-danger">${tax.title}
                                <small class="text-muted text-danger">(${tax.type === 'percentage' ? tax.amount + '%' : currencySymbol + tax.amount})</small>
                            </div>
                            <div class="col-4 text-end fw-medium text-danger">${currencySymbol}${amount.toFixed(2)}</div>
                        </div>
                    `);
                });
            }
        };

        const showPriceDetails = () => {
            const dateRange = $('input[name="datefilter"]').val();
            if (!dateRange || !dateRange.includes(" - ")) {
                Notiflix.Notify.failure("Please select a valid date range.");
                return;
            }

            calculateTotalPrice();
            $('#priceDetailsModal').modal('show');
        };

        $('#price-details-link').on('click', function (e) {
            e.preventDefault();
            showPriceDetails();
        });

        $(document).on('submit', '#reportForm', function (e) {
            e.preventDefault();

            const name = $('#reportName').val().trim();
            const details = $('#reportDetails').val().trim();

            if (!name || !details) {
                Notiflix.Notify.failure('Name and Details are required.');
                return;
            }

            let form = $(this);
            let formData = new FormData(this);
            let url = form.attr('action');

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                beforeSend: function () {
                    $('.reportSubmit').prop('disabled', true).text('Submitting...');
                    Notiflix.Loading.hourglass('Submitting...');
                },
                success: function (response) {
                    Notiflix.Loading.remove();
                    Notiflix.Notify.success(response.message || 'Report submitted successfully.');
                    $('#reportModal').modal('hide');
                    form[0].reset();
                },
                error: function (xhr) {
                    Notiflix.Loading.remove();

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = Object.values(errors).map(err => err.join(' ')).join(' ');
                        Notiflix.Notify.failure(errorMsg);
                    } else {
                        Notiflix.Notify.failure(xhr.responseJSON?.message || 'Something went wrong.');
                    }
                },
                complete: function () {
                    $('.reportSubmit').prop('disabled', false).text('Submit');
                }
            });
        });
        function toggleFeature() {
            const items = $('#others-container .other-item');
            let index = 0;

            items.addClass('d-none').removeClass('active enter exit');

            if (items.length > 0) {
                $(items[0]).removeClass('d-none').addClass('active');
            }

            function showNextItem() {
                const currentItem = $(items[index]);
                const nextIndex = (index + 1) % items.length;
                const nextItem = $(items[nextIndex]);

                currentItem.removeClass('active').addClass('exit');

                nextItem.removeClass('d-none').addClass('enter');

                setTimeout(() => {
                    currentItem.removeClass('exit').addClass('d-none');
                    nextItem.removeClass('enter').addClass('active');
                    index = nextIndex;
                }, 500);

                setTimeout(showNextItem, 3000);
            }

            if (items.length > 1) {
                setTimeout(showNextItem, 3000);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const CONFIG = {
                lat: {{ $property->latitude ?? 0 }},
                lng: {{ $property->longitude ?? 0 }},
                imageUrl: "{{ getFile($property->photos->images['thumb']['driver'], $property->photos->images['thumb']['path']) }}",
                formatedImages: '@json($formatedPmages)',
                propertyTitle: "{{ $property->title }}",
                propertyAddress: "{{ $property->address }}",
                propertyPrice: "{{ $property->price ?? '' }}",
                detailsUrl: "{{ $property->details_url ?? '' }}"
            };

            const ROUTE_STYLES = {
                DRIVING: { color: '#4285F4', arrowColor: '#EA4335' },
                WALKING: { color: '#34A853', arrowColor: '#FBBC05' },
                TRANSIT: { color: '#673AB7', arrowColor: '#9C27B0' }
            };

            const PLACE_TYPE_ICONS = {
                home: '<i class="fas fa-home text-blue-600 text-xl"></i>',
                airport: '<i class="fas fa-plane text-black text-xl"></i>',
                restaurant: '<i class="fas fa-utensils text-black text-xl"></i>',
                lodging: '<i class="fas fa-hotel text-black text-xl"></i>',
                bus_station: '<i class="fas fa-bus text-black text-xl"></i>',
                train_station: '<i class="fas fa-train text-black text-xl"></i>',
                subway_station: '<i class="fas fa-subway text-black text-xl"></i>',
                shopping_mall: '<i class="fas fa-store text-black text-xl"></i>',
                supermarket: '<i class="fas fa-cart-shopping text-black text-xl"></i>',
                park: '<i class="fas fa-tree text-black text-xl"></i>',
                bank: '<i class="fas fa-building-columns text-black text-xl"></i>',
                default: '<i class="fas fa-map-marker-alt text-black text-xl"></i>'
            };

            const DOM_ELEMENTS = {
                mapSearchToggle: document.getElementById('mapSearchToggle'),
                mapSearchContainer: document.getElementById('mapSearchContainer'),
                mapSearchInput: document.getElementById('mapSearchInput'),
                mapSearchResults: document.getElementById('mapSearchResults'),
                expandMapBtn: document.getElementById('expandMapBtn'),
                googleMapEl: document.getElementById('googleMap')
            };

            const STATE = {
                currentTravelMode: 'DRIVING',
                lastDestination: null,
                isFullScreen: false,
                mapInstance: null,
                directionsService: null,
                directionsRenderer: null,
                placesService: null,
                tempMarkers: [],
                mainMarkerOverlay: null,
                fullscreenMapInstance: null,
                tempFullscreenMarkers: []
            };

            function init() {
                setupEventListeners();

                if (googleMapApiKey) {
                    DOM_ELEMENTS.googleMapEl.classList.remove('hidden');
                    loadGoogleMapsAPI();
                }
            }

            function setupEventListeners() {
                DOM_ELEMENTS.mapSearchToggle?.addEventListener('click', toggleMapSearch);

                DOM_ELEMENTS.expandMapBtn?.addEventListener('click', toggleFullScreenMap);

                document.querySelectorAll('.view-map-icon').forEach(btn => {
                    btn.addEventListener('click', handleViewMapIconClick);
                });
            }

            function toggleMapSearch() {
                DOM_ELEMENTS.mapSearchContainer.classList.toggle('hidden');
                if (!DOM_ELEMENTS.mapSearchContainer.classList.contains('hidden')) {
                    DOM_ELEMENTS.mapSearchInput.focus();
                }
            }

            function toggleFullScreenMap() {
                STATE.isFullScreen = !STATE.isFullScreen;

                if (STATE.isFullScreen) {
                    createFullscreenMap();
                } else {
                    closeFullscreenMap();
                }
            }

            function loadGoogleMapsAPI() {
                const script = document.createElement('script');
                script.src = `https://maps.googleapis.com/maps/api/js?key=${googleMapApiKey}&callback=initGoogleMap&libraries=places,geometry`;
                script.async = true;
                document.head.appendChild(script);

                window.initGoogleMap = initGoogleMap;
            }

            function initGoogleMap() {
                STATE.mapInstance = new google.maps.Map(DOM_ELEMENTS.googleMapEl, {
                    center: { lat: CONFIG.lat, lng: CONFIG.lng },
                    zoom: 13,
                    maxZoom: 20,
                    zoomControl: true,
                    zoomControlOptions: { position: google.maps.ControlPosition.RIGHT_CENTER },
                    streetViewControl: false,
                    fullscreenControl: false,
                    gestureHandling: 'greedy',
                    mapTypeControl: false,
                    scaleControl: true,
                    mapId:mapId,
                });

                new google.maps.Marker({
                    position: { lat: CONFIG.lat, lng: CONFIG.lng },
                    map: STATE.mapInstance
                });

                initDirectionsService();

                STATE.placesService = new google.maps.places.PlacesService(STATE.mapInstance);

                createPropertyMarker();

                setupMapClickHandler(STATE.mapInstance, STATE.tempMarkers);

                if (DOM_ELEMENTS.mapSearchInput) {
                    setupSearchFunctionality();
                }
            }

            function initDirectionsService() {
                STATE.directionsService = new google.maps.DirectionsService();
                STATE.directionsRenderer = new google.maps.DirectionsRenderer({
                    map: STATE.mapInstance,
                    suppressMarkers: true,
                    polylineOptions: {
                        strokeColor: ROUTE_STYLES.DRIVING.color,
                        strokeOpacity: 0.8,
                        strokeWeight: 5,
                        icons: [{
                            icon: {
                                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                                scale: 3,
                                strokeColor: ROUTE_STYLES.DRIVING.arrowColor,
                                strokeWeight: 2
                            },
                            offset: '100%',
                            repeat: '100px'
                        }]
                    }
                });

                STATE.directionsRenderer.addListener('mouseover', () => {
                    STATE.directionsRenderer.setOptions({
                        polylineOptions: { strokeWeight: 7, strokeOpacity: 1 }
                    });
                });

                STATE.directionsRenderer.addListener('mouseout', () => {
                    STATE.directionsRenderer.setOptions({
                        polylineOptions: { strokeWeight: 5, strokeOpacity: 0.8 }
                    });
                });
            }

            function createPropertyMarker() {
                createCombinedMarker({
                    position: { lat: CONFIG.lat, lng: CONFIG.lng },
                    title: CONFIG.propertyTitle,
                    address: CONFIG.propertyAddress,
                    price: CONFIG.propertyPrice,
                    imageUrl: CONFIG.imageUrl,
                    formatedImages: CONFIG.formatedImages,
                    detailsUrl: CONFIG.detailsUrl
                }, STATE.mapInstance, false);
            }

            function setupMapClickHandler(mapInstance, tempMarkersArray, isFullscreen = false) {
                mapInstance.addListener('click', function(event) {
                    const clickedLocation = event.latLng;
                    clearMarkers(tempMarkersArray);

                    getLocationDetails(clickedLocation, mapInstance, tempMarkersArray, isFullscreen);
                });
            }

            function clearMarkers(markersArray) {
                markersArray.forEach(marker => {
                    if (marker.setMap) marker.setMap(null);
                    else if (marker instanceof google.maps.OverlayView) marker.setMap(null);
                });
                markersArray.length = 0;
            }

            function getLocationDetails(location, mapInstance, tempMarkersArray, isFullscreen) {
                const geocoder = new google.maps.Geocoder();

                geocoder.geocode({ location: location }, (results, status) => {
                    const address = (status === "OK" && results[0]) ? results[0].formatted_address : "Address not found";

                    STATE.placesService.nearbySearch({
                        location: location,
                        radius: 50
                    }, (places, status) => {
                        let images = [];
                        let title = "Selected Location";

                        if (status === google.maps.places.PlacesServiceStatus.OK && places.length > 0) {
                            const place = places[0];
                            title = place.name || title;
                            if (place.photos && place.photos.length > 0) {
                                images = place.photos.slice(0, 5).map(photo =>
                                    photo.getUrl({ maxWidth: 250, maxHeight: 180 })
                                );
                            }
                        }

                        const overlay = createCombinedMarker({
                            position: { lat: location.lat(), lng: location.lng() },
                            title: title,
                            address: address,
                            price: '',
                            formatedImages: JSON.stringify(images)
                        }, mapInstance, true);

                        const marker = new google.maps.Marker({
                            position: location,
                            map: mapInstance
                        });

                        tempMarkersArray.push(overlay, marker);
                        mapInstance.setCenter(location);
                        mapInstance.setZoom(15);

                        STATE.lastDestination = location;

                        if (STATE.isFullScreen) {
                            updateFullscreenDirections(
                                STATE.lastDestination,
                                STATE.fullscreenDirectionsService,
                                STATE.fullscreenDirectionsRenderer,
                                STATE.currentTravelMode || 'DRIVING'
                            );
                        } else {
                            updateDirections(
                                STATE.lastDestination,
                                STATE.directionsService,
                                STATE.directionsRenderer,
                                STATE.currentTravelMode || 'DRIVING'
                            );
                        }
                    });
                });
            }

            function setupSearchFunctionality() {
                showSearchInstruction();

                const service = new google.maps.places.AutocompleteService();
                const bounds = new google.maps.LatLngBounds(
                    new google.maps.LatLng(CONFIG.lat - 0.09, CONFIG.lng - 0.09),
                    new google.maps.LatLng(CONFIG.lat + 0.09, CONFIG.lng + 0.09)
                );

                DOM_ELEMENTS.mapSearchInput.addEventListener('input', function() {
                    const inputValue = this.value.trim();
                    if (!inputValue) return showSearchInstruction();

                    service.getPlacePredictions(
                        { input: inputValue, bounds, strictBounds: true, types: ['establishment'] },
                        (predictions, status) => {
                            DOM_ELEMENTS.mapSearchResults.innerHTML = '';
                            if (status !== google.maps.places.PlacesServiceStatus.OK || !predictions?.length) {
                                DOM_ELEMENTS.mapSearchResults.innerHTML = '<div class="search-instruction">@lang("No results found")</div>';
                                return;
                            }

                            predictions.forEach(prediction => {
                                createSearchResultItem(prediction);
                            });
                        }
                    );
                });
            }

            function showSearchInstruction() {
                DOM_ELEMENTS.mapSearchResults.innerHTML = `
                    <div class="search-instruction">
                        <strong>@lang('Find a place on the map')</strong><br>
                        @lang('Find restaurants, hotels, landmarks, airports, bus stands, stores and more').
                    </div>
                `;
            }

            function createSearchResultItem(prediction) {
                const div = document.createElement('div');
                div.className = 'search-result-item';
                div.innerHTML = `
                    <div class="search-icon text-2xl flex-shrink-0">⏳</div>
                    <div class="search-text flex flex-col">
                        <div class="search-title">${prediction.structured_formatting.main_text}</div>
                        <div class="search-address">${prediction.structured_formatting.secondary_text || ''}</div>
                    </div>
                `;
                DOM_ELEMENTS.mapSearchResults.appendChild(div);

                STATE.placesService.getDetails({ placeId: prediction.place_id }, place => {
                    const placeType = place.types?.find(t => PLACE_TYPE_ICONS[t]) || 'default';
                    div.querySelector('.search-icon').innerHTML = PLACE_TYPE_ICONS[placeType];

                    div.addEventListener('click', () => {
                        handlePlaceSelection(place);
                    });
                });
            }

            function handlePlaceSelection(place) {
                DOM_ELEMENTS.mapSearchResults.innerHTML = '';
                DOM_ELEMENTS.mapSearchContainer.classList.add('hidden');
                DOM_ELEMENTS.mapSearchInput.value = '';
                showSearchInstruction();

                const images = place.photos?.slice(0, 5).map(p =>
                    p.getUrl({ maxWidth: 250, maxHeight: 180 })
                ) || [PLACE_TYPE_ICONS.default];

                createCombinedMarker({
                    position: { lat: place.geometry.location.lat(), lng: place.geometry.location.lng() },
                    title: place.name,
                    address: place.formatted_address || '',
                    price: '',
                    formatedImages: JSON.stringify(images)
                }, STATE.mapInstance, true);

                new google.maps.Marker({
                    position: { lat: place.geometry.location.lat(), lng: place.geometry.location.lng() },
                    map: STATE.mapInstance
                });

                STATE.mapInstance.setCenter(place.geometry.location);
                STATE.mapInstance.setZoom(14);
                STATE.lastDestination = place.geometry.location;
                updateDirections(STATE.lastDestination);
            }

            function createFullscreenMap() {
                const fullscreenContainer = document.createElement('div');
                fullscreenContainer.id = 'fullscreenMapContainer';
                fullscreenContainer.style.position = 'fixed';
                fullscreenContainer.style.top = '0';
                fullscreenContainer.style.left = '0';
                fullscreenContainer.style.width = '100vw';
                fullscreenContainer.style.height = '100vh';
                fullscreenContainer.style.zIndex = '9999';
                fullscreenContainer.style.backgroundColor = 'white';
                fullscreenContainer.style.transition = 'opacity 0.3s ease';
                fullscreenContainer.style.opacity = '0';

                const closeButton = createCloseButton();

                const mapClone = DOM_ELEMENTS.googleMapEl.cloneNode(true);
                mapClone.style.width = '100%';
                mapClone.style.height = '100%';
                mapClone.style.position = 'absolute';
                mapClone.style.top = '0';
                mapClone.style.left = '0';

                const fullscreenSearchToggle = createFullscreenSearchToggle();
                const fullscreenSearchContainer = createFullscreenSearchContainer();

                fullscreenContainer.appendChild(mapClone);
                fullscreenContainer.appendChild(closeButton);
                fullscreenContainer.appendChild(fullscreenSearchToggle);
                fullscreenContainer.appendChild(fullscreenSearchContainer);
                document.body.appendChild(fullscreenContainer);

                setTimeout(() => {
                    fullscreenContainer.style.opacity = '1';
                }, 10);

                DOM_ELEMENTS.expandMapBtn.innerHTML = '<i class="fas fa-compress"></i>';

                setTimeout(() => {
                    initFullscreenMap(mapClone, fullscreenSearchContainer);
                }, 50);
            }

            function createCloseButton() {
                const closeButton = document.createElement('button');
                closeButton.innerHTML = '<i class="fas fa-times"></i>';
                closeButton.style.position = 'absolute';
                closeButton.style.top = '20px';
                closeButton.style.right = '20px';
                closeButton.style.zIndex = '10000';
                closeButton.style.background = 'white';
                closeButton.style.borderRadius = '50%';
                closeButton.style.width = '40px';
                closeButton.style.height = '40px';
                closeButton.style.border = 'none';
                closeButton.style.boxShadow = '0 2px 4px rgba(0,0,0,0.2)';
                closeButton.style.cursor = 'pointer';

                closeButton.addEventListener('click', () => {
                    document.body.removeChild(document.getElementById('fullscreenMapContainer'));
                    STATE.isFullScreen = false;
                    DOM_ELEMENTS.expandMapBtn.innerHTML = '<i class="fas fa-arrow-up-right-and-arrow-down-left-from-center"></i>';
                });

                return closeButton;
            }

            function createFullscreenSearchToggle() {
                const fullscreenSearchToggle = document.createElement('button');
                fullscreenSearchToggle.id = 'fullscreenSearchToggle';
                fullscreenSearchToggle.className = 'map-search-toggle';
                fullscreenSearchToggle.innerHTML = '<i class="fas fa-search"></i>';
                fullscreenSearchToggle.style.position = 'absolute';
                fullscreenSearchToggle.style.top = '20px';
                fullscreenSearchToggle.style.left = '20px';
                fullscreenSearchToggle.style.zIndex = '10000';
                fullscreenSearchToggle.style.background = 'white';
                fullscreenSearchToggle.style.borderRadius = '50%';
                fullscreenSearchToggle.style.width = '40px';
                fullscreenSearchToggle.style.height = '40px';
                fullscreenSearchToggle.style.border = 'none';
                fullscreenSearchToggle.style.boxShadow = '0 2px 4px rgba(0,0,0,0.2)';
                fullscreenSearchToggle.style.cursor = 'pointer';

                return fullscreenSearchToggle;
            }

            function createFullscreenSearchContainer() {
                const fullscreenSearchContainer = document.createElement('div');
                fullscreenSearchContainer.id = 'fullscreenSearchContainer';
                fullscreenSearchContainer.className = 'map-search-container hidden';
                fullscreenSearchContainer.style.position = 'absolute';
                fullscreenSearchContainer.style.top = '12px';
                fullscreenSearchContainer.style.left = '60px';
                fullscreenSearchContainer.style.zIndex = '10000';
                fullscreenSearchContainer.style.width = '300px';

                const searchInput = DOM_ELEMENTS.mapSearchInput.cloneNode(true);
                const searchResults = DOM_ELEMENTS.mapSearchResults.cloneNode(true);

                fullscreenSearchContainer.innerHTML = `
                    <div class="map-search-card">
                        ${searchInput.outerHTML}
                        <div id="fullscreenSearchResults" class="map-search-results"></div>
                    </div>
                `;

                return fullscreenSearchContainer;
            }

            function initFullscreenMap(mapElement, searchContainer) {
                const newMap = new google.maps.Map(mapElement, {
                    center: { lat: CONFIG.lat, lng: CONFIG.lng },
                    zoom: 13,
                    maxZoom: 20,
                    zoomControl: true,
                    zoomControlOptions: { position: google.maps.ControlPosition.RIGHT_CENTER },
                    streetViewControl: false,
                    fullscreenControl: false,
                    gestureHandling: 'greedy',
                    mapTypeControl: false,
                    scaleControl: true,
                    mapId:mapId,
                });

                new google.maps.Marker({
                    position: { lat: CONFIG.lat, lng: CONFIG.lng },
                    map: newMap
                });

                STATE.fullscreenDirectionsService = new google.maps.DirectionsService();
                STATE.fullscreenDirectionsRenderer = new google.maps.DirectionsRenderer({
                    map: newMap,
                    suppressMarkers: true,
                    polylineOptions: {
                        strokeColor: ROUTE_STYLES.DRIVING.color,
                        strokeOpacity: 0.8,
                        strokeWeight: 5,
                        icons: [{
                            icon: {
                                path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                                scale: 3,
                                strokeColor: ROUTE_STYLES.DRIVING.arrowColor,
                                strokeWeight: 2
                            },
                            offset: '100%',
                            repeat: '100px'
                        }]
                    }
                });

                setupMapClickHandler(newMap, STATE.tempFullscreenMarkers, true);

                createCombinedMarker({
                    position: { lat: CONFIG.lat, lng: CONFIG.lng },
                    title: CONFIG.propertyTitle,
                    address: CONFIG.propertyAddress,
                    price: CONFIG.propertyPrice,
                    imageUrl: CONFIG.imageUrl,
                    formatedImages: CONFIG.formatedImages,
                    detailsUrl: CONFIG.detailsUrl
                }, newMap, false);

                setupFullscreenSearch(newMap, searchContainer, STATE.fullscreenDirectionsService, STATE.fullscreenDirectionsRenderer);

                setTimeout(() => google.maps.event.trigger(newMap, 'resize'), 100);
            }

            function setupFullscreenSearch(map, searchContainer, directionsService, directionsRenderer) {
                const searchInput = searchContainer.querySelector('input');
                const searchResults = searchContainer.querySelector('.map-search-results');
                const searchToggle = document.getElementById('fullscreenSearchToggle');

                showFullscreenSearchInstruction(searchResults);

                searchToggle.addEventListener('click', () => {
                    searchContainer.classList.toggle('hidden');
                    if (!searchContainer.classList.contains('hidden')) {
                        showFullscreenSearchInstruction(searchResults);
                        searchInput.focus();
                    }
                });

                if (searchInput) {
                    const service = new google.maps.places.AutocompleteService();
                    const placesService = new google.maps.places.PlacesService(map);

                    searchInput.addEventListener('input', function() {
                        const inputValue = this.value.trim();
                        if (!inputValue) {
                            showFullscreenSearchInstruction(searchResults);
                            return;
                        }

                        service.getPlacePredictions({
                            input: inputValue,
                            bounds: map.getBounds(),
                            strictBounds: true,
                            types: ['establishment']
                        }, (predictions, status) => {
                            searchResults.innerHTML = '';
                            if (status !== google.maps.places.PlacesServiceStatus.OK || !predictions?.length) {
                                searchResults.innerHTML = '<div class="search-no-results">No results found</div>';
                                return;
                            }

                            predictions.forEach(prediction => {
                                createFullscreenSearchResultItem(prediction, placesService, map, searchResults, searchContainer, searchInput, directionsService, directionsRenderer);
                            });
                        });
                    });
                }
            }

            function showFullscreenSearchInstruction(resultsContainer) {
                if (resultsContainer) {
                    resultsContainer.innerHTML = `
                        <div class="search-instruction">
                            <strong>@lang('Find a place on the map')</strong><br>
                            @lang('Find restaurants, hotels, landmarks, airports, bus stands, stores and more').
                        </div>
                    `;
                }
            }

            function createFullscreenSearchResultItem(prediction, placesService, map, resultsContainer, searchContainer, searchInput, directionsService, directionsRenderer) {
                const div = document.createElement('div');
                div.className = 'search-result-item flex items-start gap-2 p-2 hover:bg-gray-100 cursor-pointer rounded transition';

                div.innerHTML = `
                    <div class="search-icon text-2xl flex-shrink-0">⏳</div>
                    <div class="search-text flex flex-col">
                        <div class="search-title font-semibold text-gray-800">${prediction.structured_formatting.main_text}</div>
                        <div class="search-address text-gray-500 text-sm">${prediction.structured_formatting.secondary_text || ''}</div>
                    </div>
                `;
                resultsContainer.appendChild(div);

                placesService.getDetails({ placeId: prediction.place_id }, place => {
                    const placeTypes = place.types || [];
                    let icon = PLACE_TYPE_ICONS.default;
                    for (const t of placeTypes) {
                        if (PLACE_TYPE_ICONS[t]) {
                            icon = PLACE_TYPE_ICONS[t];
                            break;
                        }
                    }

                    const iconSpan = div.querySelector('.search-icon');
                    if (iconSpan) iconSpan.innerHTML = icon;

                    div.addEventListener('click', () => {
                        handleFullscreenPlaceSelection(place, map, resultsContainer, searchContainer, searchInput, directionsService, directionsRenderer);
                    });
                });
            }

            function handleFullscreenPlaceSelection(place, map, resultsContainer, searchContainer, searchInput, directionsService, directionsRenderer) {
                resultsContainer.innerHTML = '';
                searchContainer.classList.add('hidden');
                searchInput.value = '';

                let images = [];
                if (place.photos && place.photos.length) {
                    images = place.photos.slice(0, 5).map(p =>
                        p.getUrl({ maxWidth: 250, maxHeight: 180 })
                    );
                }

                if (!images.length) {
                    images = [];
                }

                createCombinedMarker({
                    position: { lat: place.geometry.location.lat(), lng: place.geometry.location.lng() },
                    title: place.name,
                    address: place.formatted_address || '',
                    formatedImages: JSON.stringify(images)
                }, map, true);

                new google.maps.Marker({
                    position: { lat: place.geometry.location.lat(), lng: place.geometry.location.lng() },
                    map: map
                });

                map.setCenter(place.geometry.location);
                map.setZoom(16);

                updateFullscreenDirections(place.geometry.location, directionsService, directionsRenderer);
            }

            function updateFullscreenDirections(destination, directionsService, directionsRenderer, mode = 'DRIVING') {
                if (!destination || !directionsService || !directionsRenderer) return;

                directionsService.route({
                    origin: { lat: CONFIG.lat, lng: CONFIG.lng },
                    destination: destination,
                    travelMode: google.maps.TravelMode[mode] || google.maps.TravelMode.DRIVING
                }, (response, status) => {
                    if (status === google.maps.DirectionsStatus.OK && response.routes.length) {
                        directionsRenderer.setOptions({
                            polylineOptions: {
                                strokeOpacity: 0,
                                strokeWeight: 4,
                                icons: [{
                                    icon: {
                                        path: google.maps.SymbolPath.CIRCLE,
                                        fillColor: "#0c3bd5",
                                        fillOpacity: 1,
                                        scale: 2,
                                        strokeOpacity: 0
                                    },
                                    offset: '0',
                                    repeat: '10px'
                                }]
                            }
                        });
                        directionsRenderer.setDirections(response);
                    } else {
                        console.warn("Directions request failed:", status);
                    }
                });
            }

            function closeFullscreenMap() {
                const container = document.getElementById('fullscreenMapContainer');
                if (container) {
                    container.style.opacity = '0';
                    setTimeout(() => {
                        document.body.removeChild(container);
                    }, 300);
                }
                DOM_ELEMENTS.expandMapBtn.innerHTML = '<i class="fas fa-expand"></i>';
            }

            function handleViewMapIconClick() {
                const lat = parseFloat(this.dataset.lat);
                const lng = parseFloat(this.dataset.long);
                const title = this.dataset.title || "Place";

                if (!lat || !lng) return;

                const position = { lat: lat, lng: lng };
                const geocoder = new google.maps.Geocoder();

                geocoder.geocode({ location: position }, (results, status) => {
                    let address = (status === "OK" && results[0]) ? results[0].formatted_address : "Address not found";

                    const request = {
                        location: position,
                        radius: 50,
                        keyword: title
                    };

                    if (!STATE.placesService) return;

                    STATE.placesService.nearbySearch(request, (places, status) => {
                        let images = [];
                        if (status === google.maps.places.PlacesServiceStatus.OK && places.length > 0) {
                            const place = places[0];
                            if (place.photos && place.photos.length > 0) {
                                images = place.photos.slice(0, 5).map(photo =>
                                    photo.getUrl({ maxWidth: 250, maxHeight: 180 })
                                );
                            }
                        }

                        if (!images.length) images = [];

                        createCombinedMarker({
                            position: position,
                            title: title,
                            address: address,
                            price: '',
                            formatedImages: JSON.stringify(images)
                        }, STATE.mapInstance, true);

                        STATE.mapInstance.setCenter(position);
                        STATE.mapInstance.setZoom(15);
                        STATE.lastDestination = position;

                        if (typeof updateDirections === "function") {
                            updateDirections(STATE.lastDestination, STATE.currentTravelMode ?? 'DRIVING');
                        }
                    });
                });
            }

            function createCombinedMarker(options, map, showToggle = false) {
                const markerDiv = document.createElement('div');
                markerDiv.className = 'combined-marker';

                const images = options.formatedImages ? JSON.parse(options.formatedImages) : [];
                if (!images.length && options.imageUrl) {
                    images.push(options.imageUrl);
                }

                markerDiv.innerHTML = generateMarkerHTML(options, images, showToggle);

                if (images.length > 1) {
                    setupImageSlider(markerDiv, images);
                }

                if (showToggle) {
                    addTravelModeToggle(markerDiv, options.position);
                }

                const markerOverlay = createMarkerOverlay(markerDiv, options.position, map);

                const closeBtn = markerDiv.querySelector('.marker-close');
                if (closeBtn) {
                    closeBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        markerOverlay.setMap(null);
                    });
                }

                if (options.detailsUrl) {
                    markerDiv.style.cursor = 'pointer';
                    markerDiv.addEventListener('click', () => window.location.href = options.detailsUrl);
                }

                return markerOverlay;
            }

            function generateMarkerHTML(options, images, showToggle) {
                let imagesHtml = '';

                if (images.length > 0) {
                    if (images.length > 1) {
                        imagesHtml = `
                            <div class="marker-image-slider relative h-32 overflow-hidden">
                                <div class="slider-container flex transition-transform duration-300" style="width: ${images.length * 100}%">
                                    ${images.map(img => `
                                        <div class="slide flex-shrink-0" style="width: ${100 / images.length}%">
                                            <img src="${img}" class="w-100 h-full object-cover">
                                        </div>
                                    `).join('')}
                                </div>

                                <div class="slider-controls arrorControls absolute top-50 w-full d-flex justify-content-between align-items-center">
                                    <button class="slider-arrow left absolute top-1/2 left-2 -translate-y-1/2 bg-white bg-opacity-70 hover:bg-opacity-100 rounded-full p-1 shadow">
                                        <i class="fas fa-chevron-left"></i>
                                    </button>

                                    <button class="slider-arrow right absolute top-1/2 right-2 -translate-y-1/2 bg-white bg-opacity-70 hover:bg-opacity-100 rounded-full p-1 shadow">
                                        <i class="fas fa-chevron-right"></i>
                                    </button>
                                </div>

                                <div class="slider-controls absolute bottom-2 left-0 right-0 flex justify-center gap-1">
                                    ${images.map((_, index) => `
                                        <button class="slider-dot w-2 h-2 rounded-full bg-white bg-opacity-50 ${index === 0 ? 'bg-opacity-100' : ''}"
                                                data-index="${index}"></button>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    } else {
                        imagesHtml = `
                            <div class="marker-image-container h-32 overflow-hidden">
                                <img src="${images[0]}" class="w-full h-full object-cover">
                            </div>
                        `;
                    }
                }

                return `
                    <div class="marker-content relative bg-white rounded-lg shadow-md overflow-hidden" style="width: 250px;">
                        <button class="marker-close absolute top-2 right-2 w-6 h-6 flex items-center justify-center
                                       bg-white text-gray-600 rounded-full shadow-md hover:bg-gray-100 hover:text-black
                                       transition duration-200 z-10">
                            <i class="fas fa-times"></i>
                        </button>

                        ${imagesHtml}
                        <div class="marker-text">
                            <div class="marker-title truncate">${options.title}</div>
                            <div class="marker-address flex items-start text-sm text-gray-600">
                                <svg class="w-4 h-4 mt-0.5 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17.657 16.657L13.414 20.9a1.998 1.998 0
                                             01-2.827 0l-4.244-4.243a8 8 0
                                             1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 11a3 3 0 11-6 0 3 3 0
                                             016 0z"></path>
                                </svg>
                                <span class="flex-1 break-words line-clamp-2" title="${options.address}">
                                    ${options.address}
                                </span>
                            </div>
                            ${options.price ? `<div class="marker-price text-sm font-semibold text-blue-600 mt-1">${options.price}</div>` : ''}
                        </div>
                    </div>
                `;
            }

            function setupImageSlider(markerDiv, images) {
                const sliderContainer = markerDiv.querySelector('.slider-container');
                const dots = markerDiv.querySelectorAll('.slider-dot');
                let currentSlide = 0;
                let slideInterval;
                const prevBtn = markerDiv.querySelector('.slider-arrow.left');
                const nextBtn = markerDiv.querySelector('.slider-arrow.right');

                function startSlider() {
                    slideInterval = setInterval(() => {
                        currentSlide = (currentSlide + 1) % images.length;
                        updateSlider();
                    }, 3000);
                }

                function updateSlider() {
                    sliderContainer.style.transform = `translateX(-${currentSlide * (100 / images.length)}%)`;

                    dots.forEach((dot, index) => {
                        dot.classList.toggle('bg-opacity-100', index === currentSlide);
                        dot.classList.toggle('bg-opacity-50', index !== currentSlide);
                    });

                    if (prevBtn) {
                        prevBtn.style.visibility = currentSlide === 0 ? 'hidden' : 'visible';
                    }
                    if (nextBtn) {
                        nextBtn.style.visibility = currentSlide === images.length - 1 ? 'hidden' : 'visible';
                    }
                }

                updateSlider();
                startSlider();

                dots.forEach((dot, index) => {
                    dot.addEventListener('click', (e) => {
                        e.stopPropagation();
                        currentSlide = index;
                        updateSlider();
                        clearInterval(slideInterval);
                        startSlider();
                    });
                });

                const sliderElement = markerDiv.querySelector('.marker-image-slider');
                sliderElement.addEventListener('mouseenter', () => {
                    clearInterval(slideInterval);
                });

                sliderElement.addEventListener('mouseleave', startSlider);

                prevBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    currentSlide = (currentSlide - 1 + images.length) % images.length;
                    updateSlider();
                    clearInterval(slideInterval);
                    startSlider();
                });

                nextBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    currentSlide = (currentSlide + 1) % images.length;
                    updateSlider();
                    clearInterval(slideInterval);
                    startSlider();
                });
            }

            function addTravelModeToggle(markerDiv, position) {
                const toggleDiv = document.createElement('div');
                toggleDiv.className = 'marker-travel-toggle mt-2 p-1 bg-white rounded shadow-md';

                const selectEl = document.createElement('select');
                selectEl.className = 'travel-mode-select w-full px-2 py-1 border rounded';
                toggleDiv.appendChild(selectEl);

                markerDiv.querySelector('.marker-text').appendChild(toggleDiv);

                ['mousedown', 'mouseup', 'click'].forEach(eventType => {
                    selectEl.addEventListener(eventType, e => e.stopPropagation());
                });

                const modes = ['DRIVING', 'WALKING', 'TRANSIT'];
                const results = [];
                let processedCount = 0;

                modes.forEach(mode => {
                    STATE.directionsService.route({
                        origin: { lat: CONFIG.lat, lng: CONFIG.lng },
                        destination: position,
                        travelMode: google.maps.TravelMode[mode]
                    }, (response, status) => {
                        processedCount++;
                        if (status === google.maps.DirectionsStatus.OK) {
                            const leg = response.routes[0].legs[0];
                            results.push({
                                mode,
                                text: `${getModeEmoji(mode)} ${capitalize(mode)} (${leg.duration.text})`,
                                durationValue: leg.duration.value
                            });
                        }
                        if (processedCount === modes.length) {
                            const flightDurationValue = 3600;
                            results.push({
                                mode: 'FLIGHT',
                                text: `${getModeEmoji('FLIGHT')} Flight (1 hr)`,
                                durationValue: flightDurationValue
                            });

                            results.sort((a, b) => a.durationValue - b.durationValue);

                            selectEl.innerHTML = '';
                            results.forEach(item => {
                                const opt = document.createElement('option');
                                opt.value = item.mode;
                                opt.textContent = item.text;
                                selectEl.appendChild(opt);
                            });

                            selectEl.value = results[0].mode;
                            STATE.currentTravelMode = results[0].mode;
                        }
                    });
                });

                selectEl.addEventListener('change', function(e) {
                    e.stopPropagation();
                    STATE.currentTravelMode = this.value;
                    if (STATE.isFullScreen) {
                        updateFullscreenDirections(
                            position,
                            STATE.fullscreenDirectionsService,
                            STATE.fullscreenDirectionsRenderer,
                            STATE.currentTravelMode
                        );
                    } else {
                        updateDirections(position);
                    }
                });

                function getModeEmoji(mode) {
                    switch (mode) {
                        case 'DRIVING': return '🚗';
                        case 'WALKING': return '🚶';
                        case 'TRANSIT': return '🚌';
                        case 'FLIGHT': return '✈️';
                        default: return '';
                    }
                }

                function capitalize(str) {
                    return str.charAt(0) + str.slice(1).toLowerCase();
                }
            }

            function createMarkerOverlay(markerDiv, position, map) {
                const markerOverlay = new google.maps.OverlayView();

                markerOverlay.onAdd = function () {
                    this.getPanes().floatPane.appendChild(markerDiv);
                };

                markerOverlay.draw = function () {
                    const projection = this.getProjection();
                    const positionPx = projection.fromLatLngToDivPixel(position);

                    if (markerDiv) {
                        const topGap = 10;
                        const rightGap = 22;
                        const markerWidth = markerDiv.offsetWidth || 200;
                        markerDiv.style.left = (positionPx.x - markerWidth - rightGap) + 'px';
                        markerDiv.style.top = (positionPx.y - 60 - topGap) + 'px';
                    }
                };

                markerOverlay.onRemove = function () {
                    if (markerDiv && markerDiv.parentNode) {
                        markerDiv.parentNode.removeChild(markerDiv);
                    }
                };

                markerOverlay.setMap(map);

                return markerOverlay;
            }

            function updateDirections(destination, mode = 'DRIVING') {
                if (!destination) return;
                const travelMode = google.maps.TravelMode[mode] || google.maps.TravelMode.DRIVING;

                STATE.directionsService.route({
                    origin: { lat: CONFIG.lat, lng: CONFIG.lng },
                    destination: destination,
                    travelMode: travelMode
                }, (response, status) => {
                    if (status === google.maps.DirectionsStatus.OK) {

                        STATE.directionsRenderer.setOptions({
                            polylineOptions: {
                                strokeOpacity: 0,
                                strokeWeight: 4,
                                icons: [{
                                    icon: {
                                        path: google.maps.SymbolPath.CIRCLE,
                                        fillColor: "#0c3bd5",
                                        fillOpacity: 1,
                                        scale: 2,
                                        strokeOpacity: 0
                                    },
                                    offset: '0',
                                    repeat: '10px'
                                }]
                            }
                        });

                        STATE.directionsRenderer.setDirections(response);
                    } else {
                        console.warn('Directions request failed:', status);
                    }
                });
            }

            init();
        });

        document.addEventListener('DOMContentLoaded', function () {
            const uploadBox = document.getElementById('uploadBox');
            const imageInput = document.getElementById('imageInput');
            const previewWrapper = document.getElementById('previewWrapper');
            const placeholder = document.getElementById('placeholder');

            let currentFiles = [];

            function updatePlaceholder() {
                if (!placeholder || !previewWrapper) return;
                if (currentFiles.length > 0) {
                    placeholder.classList.add('d-none');
                    previewWrapper.classList.remove('d-none');
                } else {
                    placeholder.classList.remove('d-none');
                    previewWrapper.classList.add('d-none');
                }
            }

            function updateInputFiles() {
                if (!imageInput) return;
                const dataTransfer = new DataTransfer();
                currentFiles.forEach(file => dataTransfer.items.add(file));
                imageInput.files = dataTransfer.files;
            }

            function showPreviews(files) {
                if (!previewWrapper) return;
                Array.from(files).forEach((file) => {
                    if (!file.type.startsWith('image/')) return;

                    currentFiles.push(file);

                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const previewBox = document.createElement('div');
                        previewBox.classList.add('image-preview-box');

                        const img = document.createElement('img');
                        img.src = e.target.result;

                        const removeBtn = document.createElement('button');
                        removeBtn.innerText = '×';
                        removeBtn.className = 'remove-preview-btn';
                        removeBtn.onclick = () => {
                            previewBox.remove();
                            currentFiles = currentFiles.filter(f => f !== file);
                            updateInputFiles();
                            updatePlaceholder();
                        };

                        previewBox.appendChild(removeBtn);
                        previewBox.appendChild(img);
                        previewWrapper.appendChild(previewBox);
                    };
                    reader.readAsDataURL(file);
                });

                updatePlaceholder();
            }

            if (imageInput) {
                imageInput.addEventListener('change', function () {
                    showPreviews(this.files);
                    updateInputFiles();
                });
            } else {
                console.warn('imageInput element not found.');
            }

            if (uploadBox) {
                uploadBox.addEventListener('dragover', function (e) {
                    e.preventDefault();
                    uploadBox.classList.add('border-highlight');
                });

                uploadBox.addEventListener('dragleave', function (e) {
                    e.preventDefault();
                    uploadBox.classList.remove('border-highlight');
                });

                uploadBox.addEventListener('drop', function (e) {
                    e.preventDefault();
                    uploadBox.classList.remove('border-highlight');
                    const files = e.dataTransfer.files;
                    if (files.length > 0) {
                        showPreviews(files);
                        updateInputFiles();
                    }
                });
            } else {
                console.warn('uploadBox element not found.');
            }
        });

        document.querySelector('.service-top-share .shareBtn').addEventListener('click', function(e) {
            e.preventDefault();
            if (navigator.share) {
                navigator.share({
                    title: document.title,
                    text: "Check out this property!",
                    url: "{{ url()->current() }}"
                }).catch((error) => console.log('Error sharing', error));
            } else {
                const shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
                shareModal.show();
            }
        });

        window.onclick = function(event) {
            const modal = document.getElementById('shareModal');
            if (event.target === modal) {
                modal.style.display = 'none';
                modal.setAttribute('aria-hidden', 'true');
            }
        }

        document.getElementById('copyLink').addEventListener('click', function () {
            if (navigator.clipboard) {
                navigator.clipboard.writeText("{{ url()->current() }}").then(() => {
                    Notiflix.Notify.success('Link copied to clipboard!');
                }).catch(() => {
                    Notiflix.Notify.failure('Failed to copy the link.');
                });
            } else {
                const dummy = document.createElement('textarea');
                document.body.appendChild(dummy);
                dummy.value = "{{ url()->current() }}";
                dummy.select();
                try {
                    document.execCommand('copy');
                    Notiflix.Notify.success('Link copied to clipboard!');
                } catch {
                    Notiflix.Notify.failure('Failed to copy the link.');
                }
                document.body.removeChild(dummy);
            }
        });

        $(document).on('click', '.favouritlistBtn', function (e) {
            e.preventDefault();
            if (!isAuthenticated) {
                window.location.href = "{{ route('login') }}";
                return;
            }
            let button = $(this);
            let product_id = button.data('product_id');
            let heartIcon = button.find('i');
            $.ajax({
                url: "{{ route('user.wishlist') }}",
                type: "POST",
                data: {
                    product_id: product_id,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.success) {
                        if (response.isFavorited) {
                            heartIcon.removeClass('fa-regular fa-heart').addClass('fa-solid fa-heart');
                            Notiflix.Notify.success('Added to wishlist!');
                        } else {
                            heartIcon.removeClass('fa-solid fa-heart').addClass('fa-regular fa-heart');
                            Notiflix.Notify.info('Removed from wishlist!');
                        }
                    } else {
                        Notiflix.Notify.failure('There was an issue updating your wishlist.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error adding to wishlist:', error);
                }
            });
        });
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById('togglePlacesBtn');
            const hiddenItems = document.querySelectorAll('.place-item.d-none');
            let expanded = false;

            if(toggleBtn){
                toggleBtn.addEventListener('click', function() {
                    if (!expanded) {
                        hiddenItems.forEach(el => el.classList.remove('d-none'));
                        toggleBtn.textContent = "Show Less";
                        expanded = true;
                    } else {
                        hiddenItems.forEach((el, index) => {
                            if(index >= 0) el.classList.add('d-none');
                        });
                        toggleBtn.textContent = "Show More";
                        expanded = false;
                        window.scrollTo({ top: toggleBtn.offsetTop - 200, behavior: 'smooth' });
                    }
                });
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('bookNowBtn');

            if (btn.dataset.affiliate) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    Notiflix.Notify.warning('Affiliates cannot book this property.');
                });
            }
        });

        document.addEventListener("DOMContentLoaded", function() {
            const datePicker = document.getElementById("date-picker");

            datePicker.addEventListener("click", function() {
                setTimeout(() => {
                    const picker = document.querySelector(".daterangepicker");
                    if (picker && !picker.classList.contains("detailsRangePicker")) {
                        picker.classList.add("detailsRangePicker");
                    }
                }, 50);
            });
        });
    </script>
@endpush
