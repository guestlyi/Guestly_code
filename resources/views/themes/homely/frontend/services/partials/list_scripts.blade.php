@push('script')
    <script>
        const googleMapApiKey = "{{ $googleMapApiKey ?? '' }}";
        const mapId = "{{ $googleMapId ?? '' }}";
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        let map;
        const is_present_destination = "{{ request()->search ? true : false }}";
        const defaultLat = 40.7128;
        const defaultLng = -74.0060;

        if (googleMapApiKey) {
            const script = document.createElement('script');
            script.src =
                `https://maps.googleapis.com/maps/api/js?key=${googleMapApiKey}&callback=initGoogleMap&libraries=marker`;
            script.async = true;
            script.defer = true;
            document.head.appendChild(script);
        }

        function initGoogleMap() {
            const infoBoxScript = document.createElement('script');
            infoBoxScript.src =
                "https://cdn.jsdelivr.net/npm/google-maps-utility-library-v3-infobox@1.1.14/dist/infobox.min.js";
            document.head.appendChild(infoBoxScript);

            function createMap(lat, lng) {
                map = new google.maps.Map(document.getElementById("map"), {
                    center: {
                        lat,
                        lng
                    },
                    zoom: 8,
                    minZoom: 3,
                    maxZoom: 20,
                    zoomControl: false,
                    streetViewControl: false,
                    fullscreenControl: false,
                    mapTypeControl: false,
                    scaleControl: false,
                    gestureHandling: "greedy",
                    mapId: mapId,
                });

                new google.maps.marker.AdvancedMarkerElement({
                    position: {
                        lat,
                        lng
                    },
                    map: map,
                    title: "You are here"
                });
            }

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (pos) => {
                        const lat = pos.coords.latitude;
                        const lng = pos.coords.longitude;
                        createMap(lat, lng);
                    },
                    (err) => {
                        createMap(defaultLat, defaultLng);
                    }, {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 60000
                    }
                );
            } else {
                createMap(defaultLat, defaultLng);
            }
        }

        function createPopupHtml({
            image,
            images,
            title,
            detailsRoute,
            address,
            lat,
            lng,
            price,
            rating = null
        }) {
            let parsedImages = [];
            try {
                parsedImages = images ? (typeof images === "string" ? JSON.parse(images) : images) : [];
            } catch (e) {
                parsedImages = [];
            }

            const sliderId = `markerSlider_${lat}_${lng}`;

            let imagesHtml = '';
            if (parsedImages.length) {
                imagesHtml = `
                    <div id="${sliderId}" class="marker-image-slider relative h-32 overflow-hidden">
                        <div class="slider-container flex transition-transform duration-300" style="width: ${parsedImages.length * 100}%">
                            ${parsedImages.map(img => `
                                    <div class="slide flex-shrink-0" style="width: ${100 / parsedImages.length}%">
                                        <img src="${img}" class="w-100 h-full object-cover">
                                    </div>
                                `).join('')}
                        </div>

                        ${parsedImages.length > 1 ? `
                            <div class="slider-controls arrorControls absolute top-50 w-full d-flex justify-content-between align-items-center">
                                <button class="slider-arrow left absolute top-1/2 left-2 -translate-y-1/2 bg-white bg-opacity-70 hover:bg-opacity-100 rounded-full p-1 shadow">
                                    <i class="fas fa-chevron-left"></i>
                                </button>

                                <button class="slider-arrow right absolute top-1/2 right-2 -translate-y-1/2 bg-white bg-opacity-70 hover:bg-opacity-100 rounded-full p-1 shadow">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>

                            <div class="slider-controls absolute bottom-2 left-0 right-0 flex justify-center gap-1">
                                ${parsedImages.map((_, index) => `
                                <button class="slider-dot w-2 h-2 rounded-full bg-white bg-opacity-50 ${index === 0 ? 'bg-opacity-100' : ''}"
                                        data-index="${index}"></button>
                            `).join('')}
                            </div>
                            ` : ''}
                    </div>
                `;
            } else {
                imagesHtml = `<div class="marker-image-container">
                    <img src="${image || 'https://via.placeholder.com/400x200?text=No+Image'}" alt="${title}" class="w-100 h-full object-cover">
                </div>`;
            }

            let ratingHtml = '';
            if (rating != null && !isNaN(rating)) {
                rating = parseFloat(rating);
                const fullStars = Math.floor(rating);
                const halfStar = rating % 1 >= 0.5 ? 1 : 0;
                const emptyStars = 5 - fullStars - halfStar;

                ratingHtml = '<div class="marker-rating mb-1">';
                for (let i = 0; i < fullStars; i++) ratingHtml += '<i class="fa fa-star text-warning"></i>';
                if (halfStar) ratingHtml += '<i class="fa fa-star-half-alt text-warning"></i>';
                for (let i = 0; i < emptyStars; i++) ratingHtml += '<i class="far fa-star text-warning"></i>';
                ratingHtml += ` <span class="fw-bold">${rating.toFixed(1)}</span>`;
                ratingHtml += '</div>';
            } else {
                ratingHtml = '<div class="marker-rating mb-1">New</div>';
            }

            return `
                <div class="marker-content">
                    <button class="marker-close">
                        <i class="fas fa-times"></i>
                    </button>
                    ${imagesHtml}
                    <div class="marker-text">
                        <div class="marker-title">
                            <a href="${detailsRoute}" target="_blank">${title}</a>
                        </div>
                        <div class="marker-address mb-2">
                            ${address || 'Unknown address'}
                        </div>
                        ${ratingHtml}
                        <div class="d-flex align-items-center justify-content-between flex-row-reverse">
                            ${price ? `<div class="marker-price fw-bold">${price}</div>` : ''}
                            <div class="durations-dropdown" data-lat="${lat}" data-lng="${lng}">
                                <button class="duration-btn btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    ⏱ Calculating...
                                </button>
                                <ul class="dropdown-menu duration-list"></ul>
                            </div>
                        </div>
                    </div>
                </div>`;
        }



        $(document).on('click', '.load-more-btn', function() {
            let button = $(this);
            let categoryId = button.data('category-id');
            let iteration = button.data('iteration') || 1;
            let spinner = button.find('.spinner-border');
            let text = button.find('.load-more-text');

            spinner.removeClass('d-none');
            text.text('Loading...');
            button.prop('disabled', true);

            fetchByCategory(categoryId, iteration + 1)
                .then((hasMoreData) => {
                    button.data('iteration', iteration + 1);

                    $(".showSearchData").last()[0].scrollIntoView({
                        behavior: "smooth"
                    });

                    if (hasMoreData) {
                        button.removeClass('d-none');
                    } else {
                        button.addClass('d-none');
                    }
                })
                .catch((error) => {
                    console.error('Error loading data:', error);
                })
                .finally(() => {
                    spinner.addClass('d-none');
                    text.text('@lang('Load More')');
                    button.prop('disabled', false);
                });
        });

        $(document).ready(function() {
            fetchByCategory(null, 1);

            let typingTimer;
            const $input = $('.optionSearch');
            const $resultsBox = $('.top-search-options');

            const homeDestinations = $("#home_destinations").val();

            let parsedHomeDestinations = [];
            try {
                parsedHomeDestinations = JSON.parse(homeDestinations);
            } catch (e) {
                console.warn("home_destinations is not valid JSON:", e);
            }
            $input.on('focus', function() {
                if ($(this).val().trim().length === 0) {
                    if (parsedHomeDestinations.length > 0) {
                        let resultHTML = `
                            <li class="search-item nearby-item" data-title="Nearby">
                                <div class="options-list-icon">
                                    <i class="fa-light fa-location-dot"></i>
                                </div>
                                <div class="options-list-inner">
                                    <h6 class="country">@lang("Nearby")</h6>
                                    <p>@lang("Find destinations near you")</p>
                                </div>
                            </li>
                        `;

                        resultHTML += parsedHomeDestinations.map(destination => `
                            <li class="search-item" data-title="${destination.title}">
                                <div class="options-list-icon">
                                    <i class="fa-light fa-location-dot"></i>
                                </div>
                                <div class="options-list-inner">
                                    <h6 class="country">${destination.title}</h6>
                                    <p>${destination.country_take?.name ?? ''}</p>
                                </div>
                            </li>
                        `).join('');

                        $resultsBox.html(resultHTML);
                    } else {
                        $resultsBox.html('<li>@lang("No destinations available")</li>');
                    }
                    return;
                }
            });

            $input.on('keyup', function() {
                clearTimeout(typingTimer);

                const query = $(this).val().trim();
                if (query.length < 2) {
                    $resultsBox.html('');
                    return;
                }

                typingTimer = setTimeout(() => {
                    fetchDestination('keys', query);
                }, 300);
            });

            $resultsBox.on('click', 'li', function() {
                const selectedText = $(this).find('.country').text() || $(this).find(
                    '.options-list-inner h6').text();
                $input.val(selectedText);
                $resultsBox.empty();
                $input.trigger('change');
            });
        });

        function fetchDestination(type, key) {
            $.ajax({
                url: "{{ route('fetch.destination') }}",
                type: "GET",
                data: type === 'keys' ? {
                    search: key
                } : {},
                success: function(response) {
                    if (!Array.isArray(response)) {
                        Notiflix.Notify.failure('Invalid response format.');
                        return;
                    }

                    if (type === 'keys') {
                        const $resultsBox = $('.top-search-options');
                        if (response.length > 0) {
                            let html = '';
                            response.forEach(dest => {
                                html += `
                                    <li data-lat="${dest.lat}" data-long="${dest.long}">
                                        <div class="options-list-icon">
                                            <i class="fa-thin fa-location-dot"></i>
                                        </div>
                                        <div class="options-list-inner">
                                            <h6 class="country">${dest.title || 'Destination'}</h6>
                                            <p>
                                              ${dest.city_take ? dest.city_take.name : ''}${dest.state_take && (dest.state_take || dest.country_take) ? ', ' : ''}
                                              ${dest.state_take ? dest.state_take.name : ''}${dest.state_take && dest.country_take ? ', ' : ''}
                                              ${dest.country_take ? dest.country_take.name : ''}
                                            </p>
                                        </div>
                                    </li>`;
                            });
                            $resultsBox.html(html);
                        } else {
                            $resultsBox.html(
                                '<li><div class="options-list-inner"><p>@lang('No results found').</p></div></li>');
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching destination:', error);
                }
            });
        }

        let propertyInfoWindow = null;
        let directionsRenderers = [];
        const pillMarkersMap = new Map();
        const HOVER_ACTIVE_CLASS = 'pill-marker-active';
        const PAN_ON_HOVER = false;

        function fetchByCategory(categoryId, iteration) {
            return new Promise((resolve, reject) => {

                const fetchData = (latitude, longitude) => {
                    const params = new URLSearchParams(window.location.search);
                    const queryData = {
                        id: categoryId,
                        iteration: iteration,
                        sort_by: document.getElementById('sort_by')?.value || 'all',
                        latitude: latitude,
                        longitude: longitude
                    };

                    for (const [key, value] of params.entries()) {
                        queryData[key] = value;
                    }

                    $.ajax({
                        url: "{{ route('fetch.category') }}",
                        type: "GET",
                        data: queryData,
                        success: function(response) {
                            if (!response.properties || response.properties.length === 0) {
                                $('.showSearchData').html(`
                                    <div class="no-data-wrapper text-center py-5">
                                        <div class="no-data-icon mb-3">
                                            <img src="{{ asset('assets/admin/img/place.svg') }}" alt="No data" />
                                        </div>
                                        <h4 class="no-data-title mb-2">@lang("We couldn’t find any rentals matching your search.")</h4>
                                        <p class="no-data-text mb-4">@lang("Please modify your search criteria and try again.")</p>
                                    </div>
                                `);

                                $('.load-more-btn').addClass('d-none');
                                $('.showingCount').addClass('d-none');
                                $('.totalPropertiesData').addClass('d-none');
                                $('.textAreaHere').text('0 Properties');
                                return resolve(false);
                            }

                            if (response.properties.length > 0 && map) {
                                const firstProperty = is_present_destination ?
                                    response.properties[0] :
                                    {
                                        latitude,
                                        longitude
                                    };

                                map.setCenter({
                                    lat: parseFloat(firstProperty.latitude),
                                    lng: parseFloat(firstProperty.longitude)
                                });
                                map.setZoom(13);
                            }

                            let categoriesHtml = response.properties.map((property) => `
                                <div class="col-xxl-4 col-sm-6">
                                    <div class="categories-single"
                                         data-id="${property.id ?? ''}"
                                         data-lat="${property.latitude}"
                                         data-lng="${property.longitude}"
                                         data-title="${property.title}"
                                         data-image="${property.thumb || ''}"
                                         data-images='${JSON.stringify(property.imagepath || [])}'
                                         data-address="${property.address}"
                                         data-price="${property.price}"
                                         data-rating="${property.review_summary.average_rating}"
                                         data-details_route="${property.detailsRoute}">

                                        <div class="categories-single-image-container">
                                            ${property.is_most_favorite ? `
                                                    <div class="most-favorite">
                                                        <a href="${property.detailsRoute}">@lang('Most favorite')</a>
                                                    </div>` : ''}

                                                <button type="button"
                                                        class="filter-main-button hostButton"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#hostModal"
                                                        data-host='${escapeHtml(JSON.stringify(property.host))}'
                                                        data-amenities='${escapeHtml(JSON.stringify(property.amenities))}'>
                                                    <span class="host-btn">
                                                        <span class="pageFoldRight"></span>
                                                        <img src="${property.host.imagepath}" alt="image">
                                                    </span>
                                                </button>

                                            <div class="wishlist-icon">
                                                <a href="#0" data-product_id="${property.id}">
                                                    ${property.is_wishlisted === 1
                                                        ? '<i class="fa-solid fa-heart"></i>'
                                                        : '<i class="fa-regular fa-heart"></i>'
                                                    }
                                                </a>
                                            </div>

                                            <div class="theme_carousel owl-theme owl-carousel" data-options='{"loop": true, "margin": 0, "autoheight":true, "lazyload":true, "nav": true, "dots": true, "autoplay": false, "autoplayTimeout": 6000, "smartSpeed": 300, "responsive":{ "0" :{ "items": "1" }, "600" :{ "items" : "1" }, "768" :{ "items" : "1" } , "992":{ "items" : "1" }, "1200":{ "items" : "1" }}}'>
                                                ${Array.isArray(property.imagepath) ? property.imagepath.map(image => `
                                                        <div class="categories-single-image">
                                                            <a href="${property.detailsRoute}"><img src="${image}" alt="image"></a>
                                                        </div>
                                                    `).join('') : ''}
                                            </div>
                                        </div>

                                        <div class="categories-single-content property-single-content">
                                            <div class="categories-single-title">
                                                <a href="${property.detailsRoute}"  title="${property.title}" target="_blank">
                                                    ${property.title.length > 28 ? property.title.substring(0, 28) + '...' : property.title}
                                                </a>
                                            </div>
                                            <div class="categories-single-date">
                                                <div class="rat"><i class="fa-sharp fa-solid fa-star"></i>
                                                    ${property.review_summary?.average_rating ? parseFloat(property.review_summary.average_rating).toFixed(1) : 'New'}
                                                </div>
                                                <div class="rat"><i class="fa-thin fa-line"></i> <span>${property.distance || '0'}</span>@lang('km')</div>
                                            </div>
                                            <div class="categories-single-btn">
                                                <div class="categories-single-btn-text">
                                                    <h5>${property.price} <span>/@lang('Night')</span></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `).join('');

                            pillMarkersMap.forEach(obj => {
                                try {
                                    obj.overlay.setMap(null);
                                } catch (e) {}
                            });
                            pillMarkersMap.clear();

                            response.properties.forEach((property) => {
                                createPillMarker(property, map);
                            });

                            $('.showingCount').removeClass('d-none');
                            if (response.properties.length < response.totalProperties) {
                                $('.load-more-btn').removeClass('d-none');
                            } else {
                                $('.load-more-btn').addClass('d-none');
                            }

                            $('.propertiesLength').text(response.properties.length);
                            const total = response.totalProperties || 0;
                            $('.totalPropertyThisMap').text(total > 1 ? (total - 1) + '+' : total);
                            $('.totalProperties').text(total);
                            $('.totalPropertiesData').removeClass('d-none').text(total);
                            $('.textAreaHere').text('+ Properties');
                            $('.showSearchData').html(categoriesHtml);

                            document.querySelectorAll('.features-others-text').forEach(
                            container => {
                                const data = container.getAttribute('data-others');
                                let features = [];
                                try {
                                    const parsed = JSON.parse(data);
                                    features = Object.entries(parsed)
                                        .filter(([_, value]) => value === "1")
                                        .map(([key]) => key.replace(/_/g, ' '));
                                } catch (e) {
                                    console.error('Invalid others data', e);
                                }
                                const textElement = container.querySelector(
                                    '.others-text-content');
                                if (features.length === 0) {
                                    textElement.innerText = 'No extra features';
                                    return;
                                }
                                let index = 0;
                                const rotate = () => {
                                    textElement.innerText = features[index];
                                    index = (index + 1) % features.length;
                                };
                                rotate();
                                setInterval(rotate, 3000);
                            });

                            $('.owl-carousel').owlCarousel({
                                loop: true,
                                margin: 0,
                                autoheight: true,
                                lazyload: true,
                                nav: true,
                                dots: true,
                                autoplay: false,
                                autoplayTimeout: 6000,
                                smartSpeed: 300,
                                responsive: {
                                    0: {
                                        items: 1
                                    },
                                    600: {
                                        items: 1
                                    },
                                    768: {
                                        items: 1
                                    },
                                    992: {
                                        items: 1
                                    },
                                    1200: {
                                        items: 1
                                    }
                                }
                            });

                            resolve(response.hasMoreData);
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX Error:', error);
                            reject(error);
                        }
                    });
                };

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            fetchData(position.coords.latitude, position.coords.longitude);
                        },
                        (error) => {
                            fetchData(defaultLat, defaultLng);
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 60000
                        }
                    );
                } else {
                    fetchData(defaultLat, defaultLng);
                }
            });
        }

        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        function initMarkerSlider(markerContent) {
            if (!markerContent) return;
            const slider = markerContent.querySelector('.marker-image-slider');
            if (!slider) return;

            const sliderContainer = slider.querySelector('.slider-container');
            const slides = sliderContainer.querySelectorAll('.slide');
            const dots = slider.querySelectorAll('.slider-dot');
            const prevBtn = slider.querySelector('.slider-arrow.left');
            const nextBtn = slider.querySelector('.slider-arrow.right');

            if (slides.length <= 1) return;

            let currentSlide = 0;

            function updateSlider() {
                sliderContainer.style.transform = `translateX(-${currentSlide * (100 / slides.length)}%)`;
                dots.forEach((dot, idx) => {
                    dot.classList.toggle('bg-opacity-100', idx === currentSlide);
                    dot.classList.toggle('bg-opacity-50', idx !== currentSlide);
                });
            }

            prevBtn?.addEventListener('click', () => {
                currentSlide = (currentSlide - 1 + slides.length) % slides.length;
                updateSlider();
            });

            nextBtn?.addEventListener('click', () => {
                currentSlide = (currentSlide + 1) % slides.length;
                updateSlider();
            });

            dots.forEach((dot, idx) => {
                dot.addEventListener('click', () => {
                    currentSlide = idx;
                    updateSlider();
                });
            });

            updateSlider();
        }

        let hoverTimeout;

        $(document).on('mouseenter', '.categories-single', function() {
            const $this = $(this);
            const lat = parseFloat($this.data('lat'));
            const lng = parseFloat($this.data('lng'));
            if (!lat || !lng || !map) return;

            clearTimeout(hoverTimeout);

            hoverTimeout = setTimeout(() => {
                const activeClass = 'pill-marker-active';

                document.querySelectorAll('.pill-marker').forEach(marker => {
                    marker.classList.remove(activeClass);
                });

                const allMarkers = document.querySelectorAll('.pill-marker');
                allMarkers.forEach(marker => {
                    const markerLat = parseFloat(marker.getAttribute('data-lat'));
                    const markerLng = parseFloat(marker.getAttribute('data-lng'));
                    if (markerLat === lat && markerLng === lng) {
                        marker.classList.add(activeClass);
                    }
                });

                map.panTo({
                    lat,
                    lng
                });

                if (map.getZoom() < 8) {
                    map.setZoom(8);
                }

            }, 200);
        });

        $(document).on('mouseleave', '.categories-single', function() {
            clearTimeout(hoverTimeout);
            document.querySelectorAll('.pill-marker').forEach(marker => {
                marker.classList.remove('pill-marker-active');
            });
        });

        function clearDirections() {
            directionsRenderers.forEach(r => {
                try {
                    r.setMap(null);
                } catch (e) {}
            });
            directionsRenderers = [];
            $('#directions-distances').empty?.();
            $('#directions-info').addClass('d-none');
        }

        function getAirDistance(lat1, lon1, lat2, lon2) {
            const R = 6371; // km
            const dLat = (lat2 - lat1) * Math.PI / 180;
            const dLon = (lon2 - lon1) * Math.PI / 180;
            const a =
                Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                Math.cos(lat1 * Math.PI / 180) *
                Math.cos(lat2 * Math.PI / 180) *
                Math.sin(dLon / 2) * Math.sin(dLon / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return R * c;
        }

        function formatDuration(seconds) {
            if (seconds < 60) return `${seconds} sec`;

            const days = Math.floor(seconds / 86400);
            const hours = Math.floor((seconds % 86400) / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;

            let parts = [];
            if (days > 0) parts.push(`${days}d`);
            if (hours > 0) parts.push(`${hours}h`);
            if (minutes > 0) parts.push(`${minutes}m`);
            if (secs > 0 && days === 0) parts.push(`${secs}s`);

            return parts.join(' ');
        }

        function populateDurations($dropdownWrapper, lat, lng) {
            if (!$dropdownWrapper.length || !lat || !lng || !map) return;

            const $btn = $dropdownWrapper.find(".duration-btn");
            const $list = $dropdownWrapper.find(".duration-list");

            $list.empty();
            $btn.text("⏱ Calculating...");

            const calculateDurations = (originLat, originLng) => {
                const origin = {
                    lat: originLat,
                    lng: originLng
                };
                const destination = {
                    lat,
                    lng
                };
                const directionsService = new google.maps.DirectionsService();

                const travelModes = [{
                        mode: google.maps.TravelMode.DRIVING,
                        label: "🚗 "
                    },
                    {
                        mode: google.maps.TravelMode.WALKING,
                        label: "🚶 "
                    },
                    {
                        mode: google.maps.TravelMode.TRANSIT,
                        label: "🚌 "
                    },
                    {
                        mode: google.maps.TravelMode.BICYCLING,
                        label: "🚴 "
                    }
                ];

                const results = [];

                const airDistance = getAirDistance(origin.lat, origin.lng, destination.lat, destination.lng);
                const flightSpeedKmh = 800;
                const flightDurationSec = Math.round((airDistance / flightSpeedKmh) * 3600);
                results.push({
                    mode: 'FLIGHT',
                    text: `✈️ ${formatDuration(flightDurationSec)}`,
                    durationValue: flightDurationSec,
                    origin,
                    destination
                });

                let processedCount = 0;

                travelModes.forEach(({
                    mode,
                    label
                }) => {
                    directionsService.route({
                            origin,
                            destination,
                            travelMode: mode
                        },
                        (result, status) => {
                            processedCount++;
                            if (status === 'OK' && result.routes.length) {
                                const leg = result.routes[0].legs[0];
                                if (leg) {
                                    results.push({
                                        mode: mode,
                                        text: `${label}${leg.duration.text}`,
                                        durationValue: leg.duration.value,
                                        route: result
                                    });
                                }
                            }

                            if (processedCount === travelModes.length) {
                                $list.empty();
                                results.forEach(item => {
                                    $list.append(
                                        `<li data-mode="${item.mode}"><a class="dropdown-item">${item.text}</a></li>`
                                        );
                                });

                                if (results.length) $btn.text(results[0].text);

                                clearDirections();
                                let fastestRoute = results.filter(r => r.route)
                                    .sort((a, b) => a.durationValue - b.durationValue)[0];

                                if (!fastestRoute) {
                                    fastestRoute = results.filter(r => r.mode === 'FLIGHT')[0];
                                }

                                if (fastestRoute) {
                                    if (fastestRoute.route) {
                                        const directionsRenderer = new google.maps.DirectionsRenderer({
                                            map: map,
                                            suppressMarkers: false,
                                            preserveViewport: true,
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
                                        directionsRenderer.setDirections(fastestRoute.route);
                                        directionsRenderers.push(directionsRenderer);
                                    } else if (fastestRoute.mode === 'FLIGHT') {
                                        const flightLine = new google.maps.Polyline({
                                            path: [fastestRoute.origin, fastestRoute.destination],
                                            geodesic: false,
                                            strokeColor: "#0c3bd5",
                                            strokeOpacity: 0,
                                            strokeWeight: 2,
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
                                            }],
                                            map: map
                                        });
                                        directionsRenderers.push(flightLine);
                                    }
                                }
                            }
                        }
                    );
                });
            };

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    pos => calculateDurations(pos.coords.latitude, pos.coords.longitude),
                    err => {
                        calculateDurations(defaultLat, defaultLng);
                        $btn.text("⚠️ Using default location");
                    }
                );
            } else {
                calculateDurations(defaultLat, defaultLng);
                $btn.text("⚠️ Using default location");
            }
        }

        function createPillMarker(property, map) {
            const pillDiv = document.createElement("div");
            pillDiv.className = "pill-marker";
            pillDiv.innerText = property.price ? property.price : "$--";

            if (property.id !== undefined) pillDiv.setAttribute('data-id', String(property.id));
            pillDiv.setAttribute('data-lat', String(property.latitude));
            pillDiv.setAttribute('data-lng', String(property.longitude));

            pillDiv.style.position = "absolute";
            pillDiv.style.padding = "11px 17px";
            pillDiv.style.fontSize = "14px";
            pillDiv.style.background = "#fff";
            pillDiv.style.border = "1px solid #ddd";
            pillDiv.style.borderRadius = "20px";
            pillDiv.style.boxShadow = "0 2px 6px rgba(0,0,0,0.2)";
            pillDiv.style.fontWeight = "800";
            pillDiv.style.cursor = "pointer";
            pillDiv.style.whiteSpace = "nowrap";
            pillDiv.style.transition = "all 0.2s ease";
            pillDiv.style.pointerEvents = "auto";

            const overlay = new google.maps.OverlayView();

            overlay.onAdd = function() {
                const panes = this.getPanes();
                panes.overlayMouseTarget.appendChild(pillDiv);

                pillDiv.addEventListener('click', function(e) {
                    e.stopPropagation();
                    pillMarkersMap.forEach(v => v.el.classList.remove(HOVER_ACTIVE_CLASS));
                    pillDiv.classList.add(HOVER_ACTIVE_CLASS);

                    openInfoBoxForProperty(property);
                });
            };

            overlay.draw = function() {
                const projection = this.getProjection();
                if (!projection) return;

                const position = projection.fromLatLngToDivPixel(
                    new google.maps.LatLng(property.latitude, property.longitude)
                );
                if (position) {
                    pillDiv.style.left = position.x - (pillDiv.offsetWidth / 2) + "px";
                    pillDiv.style.top = position.y - pillDiv.offsetHeight + "px";
                }
            };

            overlay.onRemove = function() {
                try {
                    if (pillDiv.parentNode) pillDiv.parentNode.removeChild(pillDiv);
                } catch (e) {}
            };

            overlay.setMap(map);

            const key = property.id ? String(property.id) : `${property.latitude}_${property.longitude}`;
            pillMarkersMap.set(key, {
                el: pillDiv,
                overlay: overlay,
                property: property
            });

            return overlay;
        }

        function openInfoBoxForProperty(property) {
            if (!property) return;

            if (propertyInfoWindow) {
                try {
                    propertyInfoWindow.close();
                } catch (e) {}
            }
            clearDirections();
            const data = {
                image: property.thumb || '',
                images: property.imagepath || [],
                title: property.title,
                detailsRoute: property.detailsRoute,
                address: property.address,
                lat: property.latitude,
                lng: property.longitude,
                price: property.price,
                rating: property.review_summary.average_rating
            };

            const html = createPopupHtml(data);

            propertyInfoWindow = new InfoBox({
                content: html,
                disableAutoPan: false,
                pixelOffset: new google.maps.Size(-140, -40),
                closeBoxURL: "",
                boxStyle: {
                    width: "300px",
                    pointerEvents: "auto"
                }
            });

            propertyInfoWindow.open(map);
            try {
                propertyInfoWindow.setPosition({
                    lat: parseFloat(property.latitude),
                    lng: parseFloat(property.longitude)
                });
            } catch (e) {}

            google.maps.event.addListener(propertyInfoWindow, "domready", function() {
                const $dropdownWrapper = $(".durations-dropdown[data-lat='" + property.latitude + "'][data-lng='" +
                    property.longitude + "']");
                populateDurations($dropdownWrapper, parseFloat(property.latitude), parseFloat(property.longitude));

                const markerContent = document.querySelector('.marker-content');
                initMarkerSlider(markerContent);

                $(".marker-close").on("click", function() {
                    try {
                        propertyInfoWindow.close();
                    } catch (e) {}
                    clearDirections();
                });
            });
        }

        $(document).on('click', '.wishlist-icon a', function(e) {
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

        $('#sort_by').on('change', function() {
            let categoryId = $(".category-tab.active").data("category-id");
            let iteration = 1;

            fetchByCategory(categoryId, iteration);
        });

        const minPrice = {{ $min_price ?? 0 }};
        const maxPrice = {{ $max_price ?? 0 }};
        const minWithCurrency = '{{ userCurrencyPosition($min_price) }}';
        const maxWithCurrency = '{{ userCurrencyPosition($max_price) }}';
        const currencySymbol = @json($currency_symbol);


        $(function() {
            $('input[name="datefilter"]').daterangepicker({
                autoUpdateInput: false,
                minDate: moment(),
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY'));
            });

            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

        });

        $(function() {

            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

        });

        document.addEventListener("DOMContentLoaded", function() {

            const counters = [{
                    spanClass: "adultOut",
                    inputId: "adult-input"
                },
                {
                    spanClass: "childrenOut",
                    inputId: "children-input"
                },
                {
                    spanClass: "petOut",
                    inputId: "pet-input"
                },
            ];

            counters.forEach(c => {
                const span = document.querySelector(`.count-single-inner span.${c.spanClass}`);
                const container = span?.closest(".count-single-inner");
                const input = document.getElementById(c.inputId);

                if (!span || !container || !input) return;

                span.textContent = 0;
                input.value = 0;

                const inc = container.querySelector(".increment, .incrementTwo, .incrementThree");
                const dec = container.querySelector(".decrement, .decrementTwo, .decrementThree");

                inc?.addEventListener("click", () => {
                    let val = parseInt(span.textContent) || 0;
                    val++;
                    span.textContent = val;
                    input.value = val;
                });

                dec?.addEventListener("click", () => {
                    let val = parseInt(span.textContent) || 0;
                    val = val > 0 ? val - 1 : 0;
                    span.textContent = val;
                    input.value = val;
                });
            });

            const modalCounts = ["room", "bed", "bathroom"];
            modalCounts.forEach(key => {
                document.querySelectorAll(
                    `#categoriesModal .count-single-inner button[data-target="${key}"]`).forEach(
                btn => {
                    const span = document.querySelector(`#categoriesModal .count-value.${key}`);
                    const input = document.getElementById(
                        `input${key.charAt(0).toUpperCase() + key.slice(1)}`);
                    btn.addEventListener("click", () => {
                        let val = parseInt(span.textContent) || 0;
                        val = btn.classList.contains("increment") ? val + 1 : val > 0 ?
                            val - 1 : 0;
                        span.textContent = val;
                        input.value = val;
                    });
                });
            });

            document.querySelectorAll('#categoriesModal .amenities-list a.btn-3').forEach(btn => {
                btn.addEventListener('click', e => {
                    e.preventDefault();
                    btn.classList.toggle('active');
                });
            });

            document.querySelector('#categoriesModal .modal-footer .btn-3')?.addEventListener('click', function() {
                document.querySelectorAll('#categoriesModal .count-value').forEach(span => span
                    .textContent = '0');
                modalCounts.forEach(k => document.getElementById(
                    `input${k.charAt(0).toUpperCase() + k.slice(1)}`).value = 0);
                document.querySelectorAll('#categoriesModal .amenities-list a.active').forEach(a => a
                    .classList.remove('active'));
                document.getElementById('inputAmenities').value = '';

                if (window.priceRange && window.priceRange.noUiSlider) {
                    window.priceRange.noUiSlider.set([window.priceRangeValues.initialMin, window
                        .priceRangeValues.initialMax
                    ]);
                }
            });

            document.querySelector('#categoriesModal form')?.addEventListener('submit', function() {
                modalCounts.forEach(k => {
                    const span = document.querySelector(`#categoriesModal .count-value.${k}`);
                    if (span) document.getElementById(
                            `input${k.charAt(0).toUpperCase() + k.slice(1)}`).value = span
                        .textContent;
                });

                const selected = Array.from(document.querySelectorAll(
                        '#categoriesModal .amenities-list a.active'))
                    .map(a => a.closest('li')?.dataset.id)
                    .filter(Boolean);
                document.getElementById('inputAmenities').value = selected.join(',');

                if (window.priceRangeValues) {
                    document.getElementById('inputMinPrice').value = window.priceRangeValues.min;
                    document.getElementById('inputMaxPrice').value = window.priceRangeValues.max;
                }
            });

            const priceRange = document.getElementById('priceRange');
            if (priceRange) {
                const currency = '{{ $currency_symbol }}';
                window.priceRangeValues = {
                    min: {{ request()->min_price ?? $min_price ?? 0 }},
                    max: {{ request()->max_price ?? $max_price ?? 0 }},
                    initialMin: {{ $min_price ?? 0 }},
                    initialMax: {{ $max_price ?? 0 }}
                };

                noUiSlider.create(priceRange, {
                    start: [window.priceRangeValues.min, window.priceRangeValues.max],
                    connect: true,
                    range: {
                        min: window.priceRangeValues.initialMin,
                        max: window.priceRangeValues.initialMax
                    },
                    format: {
                        to: value => Math.round(value),
                        from: value => Number(value)
                    }
                });

                priceRange.noUiSlider.on('update', (values) => {
                    const min = Math.round(values[0]);
                    const max = Math.round(values[1]);

                    document.getElementById('minDisplay').textContent = min;
                    document.getElementById('maxDisplay').textContent = max;
                    document.getElementById('minLabel').textContent = currency + min;
                    document.getElementById('maxLabel').textContent = currency + max;

                    window.priceRangeValues.min = min;
                    window.priceRangeValues.max = max;
                });
            }
        });

        $(document).on('click', '.hostButton', function() {
            let hostData = $(this).data('host');
            let amenities = $(this).data('amenities');

            $('#hostModalImage').attr('src', hostData.imagepath);
            $('#hostModalName, .askHostName').text(hostData.fullname);
            $('#hostModalName, .hostname').text(hostData.firstname);
            $('#hostModalDesignation').text(hostData.designation || 'Super host');
            $('#hostModalReviews').text(hostData.host_review_count || '0');
            $('#hostModalRating').text(hostData.vendor_info.avg_rating || '0.0');
            $('#hostModalYears').text(hostData.vendor_info.active_years || '0');
            $('#hostModalWork').text(hostData.vendor_info.my_work || 'No work info available');
            $('#hostModalLanguages').text(hostData.language?.name || 'Languages not provided');

            let location = '';
            if (hostData.city) location += hostData.city;
            if (hostData.state) location += (location ? ', ' : '') + hostData.state;
            if (hostData.country) location += (location ? ', ' : '') + hostData.country;
            $('#hostModalLocation').text(location || 'Location not provided');

            $('#hostModalDescription').text(hostData.vendor_info.intro || 'Description not available');

            $('#amenitiesList').empty();
            amenities.forEach(function(amenity) {
                $('#amenitiesList').append(`
            <li>
                <a class="btn-3" href="${amenity.url}">
                    <div class="btn-wrapper">
                        <div class="main-text btn-single">
                            <i class="${amenity.icon}"></i> ${amenity.title}
                        </div>
                        <div class="hover-text btn-single">
                            <i class="${amenity.icon}"></i> ${amenity.title}
                        </div>
                    </div>
                </a>
            </li>
        `);
            });

            const reviewWrapper = $('.single-item-carousel .swiper-wrapper').empty();
            let slidesCount = 0;

            if (hostData.host_review?.length > 0) {
                hostData.host_review.forEach(function(review) {
                    const reviewerName = [review.guest.firstname, review.guest.lastname].filter(Boolean).join(' ') || 'Anonymous';
                    const reviewDate = new Date(review.created_at).toLocaleString('default', { month: 'long', year: 'numeric' });
                    const comment = review.comment || 'No comment available';
                    const imageUrl = review.guest.image_url || '/default-user.png';

                    const slide = `
                <div class="swiper-slide">
                    <div class="host-modal-testimonial-single">
                        <p>“${comment}”</p>
                        <div class="modal-guest-info">
                            <img src="${imageUrl}" alt="Guest">
                            <div class="info">
                                <h5>${reviewerName}</h5>
                                <p>${reviewDate}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
                    reviewWrapper.append(slide);
                    slidesCount++;
                });
            } else {
                reviewWrapper.append(`
            <div class="swiper-slide">
                <div class="host-modal-testimonial-single">
                    <p>No reviews available for this host.</p>
                </div>
            </div>
        `);
                slidesCount = 1;
            }
        });

        $('#hostModal').on('shown.bs.modal', function() {
            if (window.hostReviewSwiper) {
                window.hostReviewSwiper.destroy(true, true);
            }

            const $carousel = $(this).find('.single-item-carousel');
            const $next = $(this).find('.swiper-button-next')[0];
            const $prev = $(this).find('.swiper-button-prev')[0];

            window.hostReviewSwiper = new Swiper($carousel[0], {
                loop: $carousel.find('.swiper-slide').length > 1,
                slidesPerView: 1,
                spaceBetween: 20,
                speed: 800,
                autoplay: $carousel.find('.swiper-slide').length > 1 ? {
                    delay: 5000,
                    disableOnInteraction: false,
                } : false,
                navigation: {
                    nextEl: $next,
                    prevEl: $prev,
                },
            });

            window.hostReviewSwiper.update();
            window.hostReviewSwiper.slideTo(0);
        });
    </script>
@endpush
