@if(isset($categories))
    <section class="categories" id="categories">
        <div class="container">
            <div class="common-title-container column-gap-3 row-gap-4 flex-wrap">
                <div class="common-title text-center text-sm-start">
                    <h3>{{ $categories['single']['heading'] ?? '' }}</h3>
                </div>
                <div class="filter-button">
                    <button
                        type="button"
                        class="btn-1"
                        data-bs-toggle="modal"
                        data-bs-target="#categoriesModal"
                    >
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                {{ $categories['single']['button_text'] ?? '' }}
                                <i class="fa-regular fa-sliders"></i>
                            </div>
                            <div class="hover-text btn-single">
                                {{ $categories['single']['button_text'] ?? '' }}
                                <i class="fa-regular fa-sliders"></i>
                            </div>
                        </div>
                    </button>
                </div>
            </div>

            <div class="categories-container">
                <div class="categories-botton-area">
                    <nav>
                        <div class="nav nav-tabs categories-tabs categories-tabs2" role="tablist">
                            <div class="swiper-container">
                                <div class="swiper categories-nav-slider">
                                    <div class="swiper-wrapper">
                                        @foreach($categories['categories'] as $key => $cat)
                                            <div class="swiper-slide">
                                                <button
                                                    class="nav-link {{ $loop->first ? 'active' : '' }} category-tab"
                                                    id="categories-tab-{{ $cat->id }}"
                                                    data-category-id="{{ $cat->id }}"
                                                    data-bs-toggle="tab"
                                                    data-bs-target="#categories-nav-{{ $cat->id }}"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="categories-nav-{{ $key }}"
                                                    aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                                                >
                                                    <span class="categories-icon">
                                                      <img src="{{ getFile($cat->image_driver, $cat->image) }}" alt="{{ $cat->name }}"/>
                                                    </span>
                                                    <span class="categories-tab-title">{{ $cat->name }}</span>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-button-next swiper-button-next1">
                                <i class="fa-duotone fa-light fa-arrow-right"></i>
                            </div>
                            <div class="swiper-button-prev swiper-button-prev1">
                                <i class="fa-duotone fa-light fa-arrow-left"></i>
                            </div>
                        </div>
                    </nav>
                </div>

                <div class="tab-content">
                    @foreach($categories['categories'] as $key => $cat)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                             id="categories-nav-{{ $cat->id }}"
                             role="tabpanel"
                             aria-labelledby="categories-tab-{{ $cat->id }}"
                             tabindex="0">
                            <div class="row g-4 gy-sm-5 showSearchData" id="category-{{ $cat->id }}">
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex w-100 load-more-btn-wrapper ">
                                    <button class="load-more-btn btn-2" data-category-id="{{ $cat->id }}" data-iteration="">
                                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                                        <div class="btn-wrapper">
                                            <div class="main-text btn-single">
                                                @lang('Keep Browsing')
                                                <div class="icon-box">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="16"
                                                        height="16"
                                                        viewBox="0 0 16 16"
                                                        fill="none"
                                                    >
                                                        <path
                                                            d="M1.85457 7.01898C1.85457 7.01898 1.26557 4.28448 4.67407 3.91348C4.67407 3.91348 4.27257 3.22598 2.62507 3.01598C2.62507 3.01598 4.17757 1.11748 6.73657 3.10798C7.95607 0.104985 10.2501 0.972985 10.2501 0.972985C8.92757 1.97798 8.92507 2.77498 8.92507 2.77498C12.0616 1.39198 12.9191 4.05398 12.9191 4.05398C10.9091 3.43598 9.12507 4.60098 9.12507 4.60098C13.8761 4.52098 12.2121 8.83648 12.2121 8.83648C10.0306 5.55248 7.55007 6.14398 7.55007 6.14398C7.55007 6.14398 5.10457 6.87148 4.85757 10.807C4.85757 10.807 1.25907 7.90148 5.41407 5.59498C5.41407 5.59498 3.28507 5.47898 1.85457 7.01898Z"
                                                            fill="#F23F3F"
                                                        />
                                                        <path
                                                            d="M9.82806 13.156C9.82406 13.117 9.82106 13.079 9.81656 13.041C9.74406 12.492 9.63956 11.947 9.50706 11.41C9.12206 9.8565 8.48556 8.3415 7.61456 6.988C7.6609 6.96403 7.70847 6.9425 7.75706 6.9235C7.84433 6.91114 7.93241 6.90529 8.02056 6.906C8.24306 6.906 8.53206 6.9355 8.86456 7.035C8.97706 7.203 9.09306 7.368 9.19956 7.538C9.51388 8.0358 9.80291 8.54912 10.0656 9.076C10.7049 10.3542 11.1701 11.7123 11.4486 13.114C13.4066 13.2585 14.1851 14.111 14.1851 15.1435H7.49456C7.49406 14.184 8.16506 13.3815 9.82806 13.156Z"
                                                            fill="#F23F3F"
                                                        />
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="hover-text btn-single">
                                                @lang('Keep Browsing')
                                                <div class="icon-box">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="16"
                                                        height="16"
                                                        viewBox="0 0 16 16"
                                                        fill="none"
                                                    >
                                                        <path
                                                            d="M1.85457 7.01898C1.85457 7.01898 1.26557 4.28448 4.67407 3.91348C4.67407 3.91348 4.27257 3.22598 2.62507 3.01598C2.62507 3.01598 4.17757 1.11748 6.73657 3.10798C7.95607 0.104985 10.2501 0.972985 10.2501 0.972985C8.92757 1.97798 8.92507 2.77498 8.92507 2.77498C12.0616 1.39198 12.9191 4.05398 12.9191 4.05398C10.9091 3.43598 9.12507 4.60098 9.12507 4.60098C13.8761 4.52098 12.2121 8.83648 12.2121 8.83648C10.0306 5.55248 7.55007 6.14398 7.55007 6.14398C7.55007 6.14398 5.10457 6.87148 4.85757 10.807C4.85757 10.807 1.25907 7.90148 5.41407 5.59498C5.41407 5.59498 3.28507 5.47898 1.85457 7.01898Z"
                                                            fill="#F23F3F"
                                                        />
                                                        <path
                                                            d="M9.82806 13.156C9.82406 13.117 9.82106 13.079 9.81656 13.041C9.74406 12.492 9.63956 11.947 9.50706 11.41C9.12206 9.8565 8.48556 8.3415 7.61456 6.988C7.6609 6.96403 7.70847 6.9425 7.75706 6.9235C7.84433 6.91114 7.93241 6.90529 8.02056 6.906C8.24306 6.906 8.53206 6.9355 8.86456 7.035C8.97706 7.203 9.09306 7.368 9.19956 7.538C9.51388 8.0358 9.80291 8.54912 10.0656 9.076C10.7049 10.3542 11.1701 11.7123 11.4486 13.114C13.4066 13.2585 14.1851 14.111 14.1851 15.1435H7.49456C7.49406 14.184 8.16506 13.3815 9.82806 13.156Z"
                                                            fill="#F23F3F"
                                                        />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                                <div class="d-flex ml-auto showingCount">
                                    <span class="text-muted">@lang('showing')</span>
                                    <span class="propertiesLength font-weight-bold mx-1"></span>
                                    <span class="text-muted">@lang('Of')</span>
                                    <span class="totalProperties font-weight-bold mx-1"></span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        @include(template().'frontend.services.host.host_modal')
        <div class="modal fade" id="categoriesModal" tabindex="-1" aria-labelledby="categoriesModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <form action="{{ route('services') }}" method="get">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title fs-5" id="categoriesModalLabel">
                                @lang('Filters')
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                            ></button>
                        </div>

                        <div class="modal-body">
                            <div class="categories-modal-content">
                                <h5 class="mb-3">@lang('Price range')</h5>
                                <div class="sidebar-range-slider">
                                    <div id="priceRange"></div>
                                    <div class="slider-labels">
                                        <span id="minLabel">{{ userCurrencyPosition($categories['min_price']) }}</span>
                                        <span id="maxLabel">{{ userCurrencyPosition($categories['max_price']) }}</span>
                                    </div>
                                    @php
                                        $currency_symbol = session()->get('currency_symbol', basicControl()->currency_symbol);
                                    @endphp
                                    <p>@lang('Price'): {{ $currency_symbol }}<span id="minDisplay">{{ userCurrencyPosition($categories['min_price']) }}</span> - {{ $currency_symbol }}<span id="maxDisplay">{{ userCurrencyPosition($categories['max_price']) }}</span></p>
                                </div>
                            </div>
                            <div class="categories-modal-content">
                                <h5>@lang('Rooms and beds')</h5>
                                <div class="modal-count-container">
                                    <div class="count-single">
                                        <div class="count-single-text">
                                            <h6>@lang('Room')</h6>
                                        </div>
                                        <div class="count-single-inner">
                                            <button type="button" class="decrement">
                                                <i class="fa-regular fa-minus"></i>
                                            </button>
                                            <span class="adult">0</span>
                                            <button type="button" class="increment">
                                                <i class="fa-regular fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="count-single">
                                        <div class="count-single-text">
                                            <h6>@lang('Bed')</h6>
                                        </div>
                                        <div class="count-single-inner">
                                            <button type="button" class="decrementTwo">
                                                <i class="fa-regular fa-minus"></i>
                                            </button>
                                            <span class="childeren">0</span>
                                            <button type="button" class="incrementTwo">
                                                <i class="fa-regular fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="count-single">
                                        <div class="count-single-text">
                                            <h6>@lang('Bathrooms')</h6>
                                        </div>
                                        <div class="count-single-inner">
                                            <button type="button" class="decrementThree">
                                                <i class="fa-regular fa-minus"></i>
                                            </button>
                                            <span class="room">0</span>
                                            <button type="button" class="incrementThree">
                                                <i class="fa-regular fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="categories-modal-content">
                                <h5 class="mb-3">@lang('Amenities')</h5>
                                <div class="amenities-list">
                                    <ul>
                                        @foreach($categories['amenities'] ?? [] as $amenity)
                                            <li>
                                                <a href="#" class="btn-3" data-id="{{ $amenity->id }}">
                                                    <div class="btn-wrapper">
                                                        <div class="main-text btn-single">
                                                            <i class="{{ $amenity->icon }}"></i>{{ $amenity->title }}
                                                        </div>
                                                        <div class="hover-text btn-single">
                                                            <i class="{{ $amenity->icon }}"></i>{{ $amenity->title }}
                                                        </div>
                                                    </div>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="min_price" id="inputMinPrice" value="{{ $categories['min_price'] }}">
                        <input type="hidden" name="max_price" id="inputMaxPrice" value="{{ $categories['max_price'] }}">

                        <input type="hidden" name="room" id="inputRoom" value="0">
                        <input type="hidden" name="bed" id="inputBed" value="0">
                        <input type="hidden" name="bathroom" id="inputBathroom" value="0">
                        <input type="hidden" name="amenities" id="inputAmenities" value="">

                        <div class="modal-footer d-flex justify-content-between gap-3 flex-wrap">
                            <button type="button" class="btn-3 rounded-2 px-3">
                                <div class="btn-wrapper h-45">
                                    <div class="main-text btn-single h-45">@lang('Clear all')</div>
                                    <div class="hover-text btn-single h-45">@lang('Clear all')</div>
                                </div>
                            </button>
                            <button type="submit" class="btn-2 rounded-2 px-3">
                                <div class="btn-wrapper h-45">
                                    <div class="main-text btn-single h-45">
                                        @lang('Filter')
                                    </div>
                                    <div class="hover-text btn-single h-45">
                                        @lang('Filter')
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const minPrice = {{ $categories['min_price'] }};
            const maxPrice = {{ $categories['max_price'] }};
            const currencySymbol = @json(session('currency_symbol', basicControl()->currency_symbol));

            const priceRange = document.getElementById('priceRange');
            const inputMinPrice = document.getElementById('inputMinPrice');
            const inputMaxPrice = document.getElementById('inputMaxPrice');
            const minLabel = document.getElementById('minLabel');
            const maxLabel = document.getElementById('maxLabel');
            const minDisplay = document.getElementById('minDisplay');
            const maxDisplay = document.getElementById('maxDisplay');

            if (priceRange) {
                noUiSlider.create(priceRange, {
                    start: [minPrice, maxPrice],
                    connect: true,
                    range: {
                        'min': minPrice,
                        'max': maxPrice
                    }
                });

                priceRange.noUiSlider.on('update', function (values) {
                    const min = parseFloat(values[0]).toFixed(0);
                    const max = parseFloat(values[1]).toFixed(0);

                    inputMinPrice.value = min;
                    inputMaxPrice.value = max;

                    minLabel.textContent = currencySymbol + min;
                    maxLabel.textContent = currencySymbol + max;
                    minDisplay.textContent = min;
                    maxDisplay.textContent = max;
                });
            }

            const setCount = (selector, incrementBtn, decrementBtn, inputField) => {
                const display = document.querySelector(selector);
                const inc = document.querySelector(incrementBtn);
                const dec = document.querySelector(decrementBtn);
                const input = document.querySelector(inputField);

                if (display && inc && dec && input) {
                    inc.addEventListener('click', () => {
                        let value = parseInt(display.textContent) || 0;
                        value++;
                        display.textContent = value;
                        input.value = value;
                    });
                    dec.addEventListener('click', () => {
                        let value = parseInt(display.textContent) || 0;
                        if (value > 0) value--;
                        display.textContent = value;
                        input.value = value;
                    });
                }
            };

            setCount('.adult', '.increment', '.decrement', '#inputRoom');
            setCount('.childeren', '.incrementTwo', '.decrementTwo', '#inputBed');
            setCount('.room', '.incrementThree', '.decrementThree', '#inputBathroom');

            const amenities = document.querySelectorAll('.amenities-list a.btn-3');
            const inputAmenities = document.getElementById('inputAmenities');
            let selectedAmenities = [];

            amenities.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();

                    const amenityId = btn.getAttribute('data-id');
                    if (!amenityId) return;

                    if (selectedAmenities.includes(amenityId)) {
                        selectedAmenities = selectedAmenities.filter(a => a !== amenityId);
                        btn.classList.remove('active');
                    } else {
                        selectedAmenities.push(amenityId);
                        btn.classList.add('active');
                    }

                    inputAmenities.value = selectedAmenities.join(',');
                });
            });

            const clearBtn = document.querySelector('.modal-footer .btn-3');
            if (clearBtn) {
                clearBtn.addEventListener('click', () => {
                    if (priceRange && priceRange.noUiSlider) {
                        priceRange.noUiSlider.set([minPrice, maxPrice]);
                    }

                    ['.adult', '.childeren', '.room'].forEach(selector => {
                        const el = document.querySelector(selector);
                        if (el) el.textContent = '0';
                    });

                    document.getElementById('inputRoom').value = 0;
                    document.getElementById('inputBed').value = 0;
                    document.getElementById('inputBathroom').value = 0;
                    document.getElementById('inputAmenities').value = '';

                    amenities.forEach(btn => btn.classList.remove('active'));
                    selectedAmenities = [];
                });
            }

            const form = document.querySelector('#categoriesModal form');
            if (form) {
                form.addEventListener('submit', () => {
                    if (priceRange && priceRange.noUiSlider) {
                        const values = priceRange.noUiSlider.get();
                        inputMinPrice.value = parseFloat(values[0]).toFixed(0);
                        inputMaxPrice.value = parseFloat(values[1]).toFixed(0);
                    }
                    inputAmenities.value = selectedAmenities.join(',');
                });
            }
        });
    </script>
@endif
