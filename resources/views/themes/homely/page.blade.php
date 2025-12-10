@extends(template() . 'layouts.app')
@section('title',trans('Home'))
@section('content')
    {!!  $sectionsData !!}
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset(template(true) . "css/flatpickr.min.css") }}"/>
    <style>
        .search-item.nearby-item {
            background-color: rgba(var(--primary-color-rgb), 0.1);
            color: var(--primary-color);
            border-left: 3px solid var(--primary-color);
        }

        .search-item.nearby-item .country {
            font-weight: bold;
        }

        .search-item.nearby-item:hover {
            background-color: rgba(var(--primary-color-rgb), 0.2) !important;
        }

        .search-item:not(.nearby-item) {
            background-color: #fff;
            color: #333;
            border-left: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .search-item:not(.nearby-item):hover {
            background-color: rgba(0, 0, 0, 0.05);
            border-left: 3px solid transparent;
        }
        #categoriesModal .modal-body{
            max-height: 600px;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset(template(true).'js/flatpickr.min.js') }}"></script>

    <script>
        const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
        const currency_symbol = '{{ userCurrencySymbol() }}';
        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
        const defaultLat = 40.7128;
        const defaultLng = -74.0060;

        $(document).on('click', '.hostButton', function () {
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
            amenities.forEach(function (amenity) {
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
                hostData.host_review.forEach(function (review) {
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

        $('#hostModal').on('shown.bs.modal', function () {
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
                    delay: 4000,
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


        $(document).on('click', '.load-more-btn', function () {
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
                    text.text('@lang("Load More")');
                    button.prop('disabled', false);
                });
        });
        $(document).ready(function () {
            const $searchBox = $(".location-search-box");
            const $searchDropdown = $(".location-search-dropdown");
            const $searchInput = $(".optionSearch");

            const homeDestinations = $("#home_destinations").val();

            let parsedHomeDestinations = [];
            try {
                parsedHomeDestinations = JSON.parse(homeDestinations);
            } catch (e) {
                console.warn("home_destinations is not valid JSON:", e);
            }
            function performSearch(query) {
                const $results = $("#search-results");

                if (!query) {
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

                        $results.html(resultHTML);
                    } else {
                        $results.html('<li>@lang("No destinations available")</li>');
                    }
                    return;
                }

                $.ajax({
                    url: "{{ route('fetch.search') }}",
                    method: "GET",
                    data: { query: query },
                    dataType: "json",
                    success: function (response) {
                        let resultHTML = response.length
                            ? response.map(destination => `
                                <li class="search-item" data-title="${destination.title}">
                                    <div class="options-list-icon">
                                        <i class="fa-light fa-location-dot"></i>
                                    </div>
                                    <div class="options-list-inner">
                                        <h6 class="country">${destination.title}</h6>
                                        <p>${destination.type ? destination.type : ''}</p>
                                    </div>
                                </li>
                            `).join('')
                            : '<li>@lang("No results found")</li>';

                        $results.html(resultHTML);
                    }
                });
            }

            $searchInput.on("focus keyup", function () {
                $searchDropdown.show();
                performSearch($(this).val().trim());
                const countBox = $(".destination-search .count");
                countBox.removeClass("active");
            });

            $(document).on("click", function (e) {
                if (!$(e.target).closest($searchBox).length) {
                    $searchDropdown.hide();
                }
            });

            $searchInput.on("blur", function () {
                setTimeout(() => {
                    $searchDropdown.hide();
                }, 200);
            });

            $(document).on("click", ".search-item", function () {
                $searchInput.val($(this).data("title"));
                $searchDropdown.hide();
            });

            let initialCategoryId = $(".category-tab.active").data("category-id");
            if (initialCategoryId) {
                fetchByCategory(initialCategoryId, 1);
            }

            $(".category-tab").on("click", function () {
                let categoryId = $(this).data("category-id");
                fetchByCategory(categoryId, 1);
            });

            function fetchProperties(categoryId) {
                $.ajax({
                    url: "{{ route('get.properties') }}",
                    type: 'GET',
                    data: { category_id: categoryId },
                    success: function(res) {
                        let container = $('.showSearchProperty');
                        if (res.properties && res.properties.length > 0) {
                            let html = '';
                            res.properties.forEach(function(property) {
                                html += '<div class="col-xl-2 col-lg-3 col-md-4">' +
                                    '<div class="trending-single">' +
                                    '<a href="' + property.detailsUrl + '">' +
                                    '<h6>' + truncateChars(property.title, 30) + '</h6>' +
                                    '<p>' + truncateChars(property.description, 40) + '</p>' +
                                    '</a>' +
                                    '</div>' +
                                    '</div>';
                            });
                            container.html(html);
                        } else {
                            container.html('<p>No properties found</p>');
                        }
                    },
                    error: function() {
                        $('.showSearchProperty').html('<p>Error loading properties</p>');
                    }
                });
            }

            function truncateChars(text, maxChars = 20) {
                if (!text) return '';
                if (text.length <= maxChars) return text;
                return text.substring(0, maxChars) + '...';
            }
            const firstCategoryId = $('.trending-tab-btn.active').data('category-id');
            if (firstCategoryId) {
                fetchProperties(firstCategoryId);
            }

            $(document).on('click', '.trending-tab-btn', function() {
                $('.trending-tab-btn').removeClass('active');
                $(this).addClass('active');

                const categoryId = $(this).data('category-id');
                fetchProperties(categoryId);
            });

        });
        const carouselOptions = `{"loop": true, "margin": 0, "autoheight":true, "lazyload":true, "nav": true, "dots": true, "autoplay": false, "autoplayTimeout": 6000, "smartSpeed": 300, "responsive":{ "0" :{ "items": "1" }, "600" :{ "items": "1" }, "768" :{ "items": "1" } , "992":{ "items": "1" }, "1200":{ "items": "1" }}}`;


        function escapeHtml(str) {
            return String(str)
                .replace(/&/g, '&amp;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;');
        }

        function renderHome101(category) {
            return `
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="categories-single categories-item">
                        <div class="categories-single-image-container">
                            <div class="most-favorite">${category.host?.vendorInfo?.badgeInfo
                                ? `<div class="most-favorite"><a href="#">${escapeHtml(category.host.vendorInfo.badgeInfo.title)}</a></div>`
                                : ''}</div>
                            <button type="button"
                                    class="filter-main-button hostButton"
                                    data-bs-toggle="modal"
                                    data-bs-target="#hostModal"
                                    data-host='${escapeHtml(JSON.stringify(category.host))}'
                                    data-active_properties='${escapeHtml(JSON.stringify(category.host.active_properties))}'
                                    data-amenities='${escapeHtml(JSON.stringify(category.amenities))}'>
                                <span class="host-btn">
                                    <span class="pageFoldRight"></span>
                                    <img src="${category.host.imagepath}" alt="image">
                                </span>
                            </button>
                            <div class="wishlist-icon">
                                <a href="#0" data-product_id="${category.id}">
                                    ${category.is_wishlisted === 1
                                        ? '<i class="fa-solid fa-heart"></i>'
                                        : '<i class="fa-regular fa-heart"></i>'
                                    }
                                </a>
                            </div>
                            <div class="theme_carousel owl-theme owl-carousel" data-options='${carouselOptions}'>
                                ${Array.isArray(category.imagepath) ? category.imagepath.map(image => `
                                    <div class="categories-single-image">
                                        <a href="${category.detailsRoute}"><img src="${image}" alt="image"></a>
                                    </div>`).join('') : ''}
                            </div>
                        </div>
                        <div class="categories-single-content">
                            <div class="categories-single-title"><a href="${category.detailsRoute}" title="${category.title}">${category.title.length > 30 ? category.title.substring(0, 30) + '...' : category.title}</a></div>
                            <div class="categories-single-date">
                                <div class="rat">
                                    <i class="fa-sharp fa-solid fa-star"></i>
                                    ${(!isNaN(parseFloat(category.review_summary?.average_rating)) && parseFloat(category.review_summary?.average_rating) > 0)
                                    ? parseFloat(category.review_summary.average_rating).toFixed(1)
                                    : 'New'}
                                </div>
                                <div class="rat">
                                    <h5>${category.price} <span>/Night</span></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
        }

        function fetchByCategory(categoryId, iteration) {
            if (!categoryId) return Promise.reject('Invalid Category ID');

            return new Promise((resolve, reject) => {
                const fetchData = (latitude, longitude) => {
                    const data = {
                        id: categoryId,
                        iteration: iteration,
                        latitude: latitude,
                        longitude: longitude
                    };

                    $.ajax({
                        url: "{{ route('fetch.category') }}",
                        type: "GET",
                        data: data,
                        success: function(response) {
                            if (!response.properties || response.properties.length === 0) {
                                $('.showSearchData').html(`
                                    <div class="no-data-message text-center p-4">
                                        <img class="showImgError mb-3"
                                             src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                             alt="No results found"
                                             style="max-width:150px; opacity:0.8;" />
                                        <h5 class="fw-bold text-muted">Oops! No results found</h5>
                                        <p class="text-secondary mt-2">
                                            We couldn’t find any properties matching your search.<br>
                                            Try adjusting your filters or search again with different criteria.
                                        </p>

                                        <button class="btn-3 try-again-btn mt-3">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                🔍 Try Again
                                                </div>
                                                <div class="hover-text btn-single">
                                                🔍 Try Again
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                `);

                                $('.load-more-btn').addClass('d-none');
                                $('.showingCount').addClass('d-none');
                                return;
                            }

                            let renderTemplate =  renderHome101;
                            let categoriesHtml = response.properties.map(renderTemplate).join('');

                            $('.showSearchData').html(categoriesHtml);
                            $('.propertiesLength').text(response.properties.length);
                            $('.totalProperties').text(response.totalProperties);

                            $('.showingCount').removeClass('d-none');
                            if (response.properties.length < response.totalProperties) {
                                $('.load-more-btn').removeClass('d-none');
                            } else {
                                $('.load-more-btn').addClass('d-none');
                            }

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
                                    0: { items: 1 },
                                    600: { items: 1 },
                                    768: { items: 1 },
                                    992: { items: 1 },
                                    1200: { items: 1 }
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
                        pos => fetchData(pos.coords.latitude, pos.coords.longitude),
                        err => {

                            fetchData(defaultLat, defaultLng);
                        }
                    );
                } else {

                    fetchData(defaultLat, defaultLng);
                }
            });
        }

        $(function () {
            $('input[name="datefilter"]').daterangepicker({
                autoUpdateInput: true,
                minDate: moment(),
                startDate: moment(),               // today
                endDate: moment().add(2, 'days'),  // today + 2 days
                locale: {
                    format: 'DD/MM/YYYY',          // <-- important
                    cancelLabel: 'Clear'
                }
            });
            $('input[name="datefilter"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(
                    picker.startDate.format('DD/MM/YYYY') + 
                    ' - ' + 
                    picker.endDate.format('DD/MM/YYYY')
                );
            });
            $('input[name="datefilter"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
            });
        });


        $(function() {

            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
            });

        });

        document.addEventListener("DOMContentLoaded", function () {
            let adult = 2;
            let children = 0;
            let pet = 0;

            function updateCounts() {
                document.querySelectorAll(".count-counter-inner .adult").forEach(el => el.textContent = adult);
                document.querySelectorAll(".count-counter-inner .childeren").forEach(el => el.textContent = children);
                document.querySelectorAll(".count-counter-inner .pet").forEach(el => el.textContent = pet);
                document.querySelectorAll(".count-single-inner .adultCount").forEach(el => el.textContent = adult);
                document.querySelectorAll(".count-single-inner .childrenCount").forEach(el => el.textContent = children);
                document.querySelectorAll(".count-single-inner .petCount").forEach(el => el.textContent = pet);

                const adultInput = document.getElementById("adult_count");
                const childrenInput = document.getElementById("children_count");
                const petInput = document.getElementById("pet_count");

                if (adultInput) adultInput.value = adult;
                if (childrenInput) childrenInput.value = children;
                if (petInput) petInput.value = pet;
            }

            const increment = document.querySelector(".increment");
            if (increment) increment.addEventListener("click", () => { adult++; updateCounts(); });

            const decrement = document.querySelector(".decrement");
            if (decrement) decrement.addEventListener("click", () => { if (adult > 0) adult--; updateCounts(); });

            const incrementTwo = document.querySelector(".incrementTwo");
            if (incrementTwo) incrementTwo.addEventListener("click", () => { children++; updateCounts(); });

            const decrementTwo = document.querySelector(".decrementTwo");
            if (decrementTwo) decrementTwo.addEventListener("click", () => { if (children > 0) children--; updateCounts(); });

            const incrementThree = document.querySelector(".incrementThree");
            if (incrementThree) incrementThree.addEventListener("click", () => { pet++; updateCounts(); });

            const decrementThree = document.querySelector(".decrementThree");
            if (decrementThree) decrementThree.addEventListener("click", () => { if (pet > 0) pet--; updateCounts(); });

            updateCounts();
        });

        $(document).on('click', '.try-again-btn', function (e) {
            window.location.reload();
        });

        $(document).on('click', '.wishlist-icon a', function (e) {
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

        window.addEventListener("load", function() {
            if (window.innerWidth >= 768) {
                const checkPicker = setInterval(() => {
                    const picker = document.querySelector(".daterangepicker");
                    if (picker) {
                        picker.classList.add("customised-daterangepicker");
                        clearInterval(checkPicker);
                    }
                }, 100);
            }
        });
    </script>
@endpush
