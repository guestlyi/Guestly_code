@extends(template() . 'layouts.app')
@section('title',trans('Homes & Stays'))
@section('content')
    <input type="hidden" name="home_destinations" id="home_destinations" value="{{ $homeDestinations ?? '' }}" />

    <div class="offcanvas destination-modal offcanvas-top destination-offcanvas" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
        <div class="offcanvas-header">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="destination-search destination-search-five">
                <div class="container">
                    <div class="row g-4 justify-content-center">
                        <div class="col-xxl-10">
                            <form action="{{ route('services') }}" method="get">
                                <div class="destination-search-five-container">
                                    <div class="destination-search-inner">
                                        <div class="location-search-box">
                                            <div class="select-option">
                                                <div class="select-icon">
                                                    <i class="fa-light fa-location-dot"></i>
                                                </div>
                                                <div class="select-option-content">

                                                    <input
                                                        type="search"
                                                        class="soValue optionSearch"
                                                        name="search"
                                                        placeholder="Search destination"
                                                        value="{{ old('search', request()->search) }}"
                                                    />
                                                </div>
                                            </div>
                                            <div class="location-search-dropdown">
                                                <ul class="search-options top-search-options" id="search-results"></ul>
                                                <button class="show-more" style="display:none;">@lang('Show More')</button>
                                            </div>
                                        </div>
                                        <div class="hr-line"></div>
                                        <div class="date">
                                            <div class="select-option">
                                                <div class="select-icon">
                                                    <i class="fa-light fa-door-open"></i>
                                                </div>
                                                <div class="select-option-content">
                                                    <input
                                                        type="text"
                                                        id="date-picker"
                                                        name="datefilter"
                                                        placeholder="12/12/2024 - 14/12/2024"
                                                        value="{{ old('datefilter', request()->datefilter) }}"
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="hr-line"></div>
                                        <div class="count">
                                            <input type="hidden" name="adult_count" id="adult_count" value="{{ request()->adult_count ?? 0 }}" />
                                            <input type="hidden" name="children_count" id="children_count" value="{{ request()->children_count ?? 0 }}" />
                                            <input type="hidden" name="pet_count" id="pet_count" value="{{ request()->pet_count ?? 0 }}" />
                                            <div class="count-counter select-option">
                                                <div class="select-icon">
                                                    <i class="fa-light fa-user"></i>
                                                </div>
                                                <div class="select-option-content">
                                                    <div class="count-input">
                                                        <div class="count-counter-inner">
                                                            <span class="adult">0</span>
                                                            <p>@lang('adult')</p>
                                                        </div>
                                                        <div class="count-counter-inner">
                                                            <span class="childeren">0</span>
                                                            <p>@lang('children')</p>
                                                        </div>
                                                        <div class="count-counter-inner">
                                                            <span class="room">0</span>
                                                            <p>@lang('pet')</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="count-container">
                                                <div class="count-single">
                                                    <div class="count-single-text">
                                                        <h6>@lang('Adult')</h6>
                                                        <p>@lang('Over 12 Years')</p>
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
                                                        <h6>@lang('Children')</h6>
                                                        <p>@lang('Below 12 Years')</p>
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
                                                        <h6>@lang('Pet')</h6>
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

                                        <button type="submit" class="btn-1">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    <i class="fa-regular fa-magnifying-glass"></i>
                                                    <span class="d-md-none d-xl-block">@lang('Search')</span>
                                                </div>
                                                <div class="hover-text btn-single">
                                                    <i class="fa-regular fa-magnifying-glass"></i>
                                                    <span class="d-md-none d-xl-block">@lang('Search')</span>
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="categories service-categories">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-7">
                    <div class="services-filer mb-4">
                        <div class="services-showing">
                            <h5 class="totalPropertiesText"><span class="totalPropertyThisMap">0</span> @lang('properties within map area')</h5>
                        </div>
                        <div class="services-filer-btn">
                            <button type="button" class="btn-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">
                                <div class="btn-wrapper h-45">
                                    <div class="main-text btn-single h-45">
                                        <i class="far fa-search"></i>@lang('Search')
                                    </div>
                                    <div class="hover-text btn-single h-45">
                                        <i class="far fa-search"></i>@lang('Search')
                                    </div>
                                </div>
                            </button>

                            <button type="button" class="btn-2" data-bs-toggle="modal" data-bs-target="#categoriesModal">
                                <div class="btn-wrapper h-45">
                                    <div class="main-text btn-single h-45">
                                        <i class="far fa-filter me-2"></i> @lang('Filters')
                                    </div>
                                    <div class="hover-text btn-single h-45">
                                        <i class="far fa-filter me-2"></i> @lang('Filters')
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                    <div class="row g-4 showSearchData">

                    </div>
                    <div class="d-flex justify-content-between align-items-center searchLoadMoreShow">
                        <button class="btn btn-primary load-more-btn d-none" data-category-id="" data-iteration="1">
                            <span class="load-more-text">@lang('Load More')</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                        <div class="d-flex ml-auto showingCount">
                            <span class="text-muted">@lang('showing')</span>
                            <span class="propertiesLength font-weight-bold mx-1"></span>
                            <span class="text-muted">@lang('Of')</span>
                            <span class="totalProperties font-weight-bold mx-1"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="sercive-map">
                        <div class="sercive-map-inner">
                            <div id="map"></div>
                            <div id="directions-info" class="d-none">
                                <h4>@lang('Route Distances')</h4>
                                <ul id="directions-distances"></ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="categoriesModal" tabindex="-1" aria-labelledby="categoriesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered">
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

                    <div class="modal-body overflow-auto">
                        <div class="categories-modal-content">
                            <h5 class="mb-3">@lang('Price range')</h5>
                            <div class="sidebar-range-slider">
                                <div id="priceRange"></div>
                                <div class="slider-labels">
                                    <span id="minLabel">{{ userCurrencyPosition(request()->min_price ?? $min_price) }}</span>
                                    <span id="maxLabel">{{ userCurrencyPosition(request()->max_price ?? $max_price) }}</span>
                                </div>
                                @php
                                    $currency_symbol = session()->get('currency_symbol', basicControl()->currency_symbol);
                                @endphp
                                <p>@lang('Price'): {{ $currency_symbol }}<span id="minDisplay">{{ userCurrencyPosition(request()->min_price ?? $min_price) }}</span> - {{ $currency_symbol }}<span id="maxDisplay">{{ userCurrencyPosition(request()->max_price ?? $max_price) }}</span></p>
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
                                        <button type="button" class="decrement" data-target="room">
                                            <i class="fa-regular fa-minus"></i>
                                        </button>
                                        <span class="count-value room">{{ request()->room ?? 0 }}</span>
                                        <button type="button" class="increment" data-target="room">
                                            <i class="fa-regular fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Bed -->
                                <div class="count-single">
                                    <div class="count-single-text">
                                        <h6>@lang('Bed')</h6>
                                    </div>
                                    <div class="count-single-inner">
                                        <button type="button" class="decrement" data-target="bed">
                                            <i class="fa-regular fa-minus"></i>
                                        </button>
                                        <span class="count-value bed">{{ request()->bed ?? 0 }}</span>
                                        <button type="button" class="increment" data-target="bed">
                                            <i class="fa-regular fa-plus"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Bathroom -->
                                <div class="count-single">
                                    <div class="count-single-text">
                                        <h6>@lang('Bathrooms')</h6>
                                    </div>
                                    <div class="count-single-inner">
                                        <button type="button" class="decrement" data-target="bathroom">
                                            <i class="fa-regular fa-minus"></i>
                                        </button>
                                        <span class="count-value bathroom">{{ request()->bathroom ?? 0 }}</span>
                                        <button type="button" class="increment" data-target="bathroom">
                                            <i class="fa-regular fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="categories-modal-content">
                            <h5 class="mb-3">@lang('Amenities')</h5>
                            @php
                                $selectedAmenities = explode(',', request()->amenities ?? '');
                            @endphp
                            <div class="amenities-list">
                                <ul>
                                    @foreach($amenities ?? [] as $amenity)
                                        @php
                                            $isActive = in_array((string) $amenity->id, $selectedAmenities);
                                        @endphp
                                        <li data-id="{{ $amenity->id }}">
                                            <a href="#" class="btn-3 {{ $isActive ? 'active' : '' }}">
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

                    <input type="hidden" name="min_price" id="inputMinPrice" value="{{ request()->min_price ?? $min_price }}">
                    <input type="hidden" name="max_price" id="inputMaxPrice" value="{{ request()->max_price ?? $max_price }}">

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

    @include(template().'frontend.services.host.host_modal')
@endsection
@push('style')
    <style>
        #map { width: 100%; height: 100%; min-height: 400px; }
        .sercive-map { position: relative; }
        .marker-content{
            display: flex;
            flex-direction: column;
            align-items: end;
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
            padding: 0;
            min-width: 180px;
            max-width: 290px;
            width: 100% !important;
            position: relative;
        }
        .marker-image-container{
            width: 290px;
            height: 200px;
            border-radius: 16px 16px 0 0;
        }
        .marker-image-container img{
            width: 100%;
            height: 100%;
            border-radius: 16px 16px 0 0;
        }
        .marker-text {
            flex: 1;
            min-width: 0;
            padding: 20px 10px 20px;
            width: 100%;
        }
        .marker-address svg{
            height: 14px;
        }

        .marker-title, .marker-title a {
            font-weight: 400;
            font-size: 16px;
            color: #333 !important;
            margin-bottom: 10px;
        }

        .marker-address {
            font-size: 14px;
            color: #666;
            overflow: hidden;
            margin-top: 2px;
        }
        .marker-address span{
            cursor: pointer;
        }

        .marker-price {
            font-size: 20px;
            color: #1a73e8;
            font-weight: bold;
            margin-top: 2px;
        }

        .sercive-map-inner{
            position: relative;
            width: 100%;
            height: 100%;
        }
        #directions-info {
            position: absolute !important;
            bottom: 30px !important;
            right: 60px !important;
        }
        .marker-close {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #f2f2f2;
            border: none;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
            z-index: 9;
        }

        .marker-close:hover {
            background: #e0e0e0;
        }
        .durations-dropdown {
            padding: 15px 0;
        }
        .durations-dropdown .dropdown-menu {
            min-width: 180px;
            padding: 8px 0;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.15);
        }

        .durations-dropdown .dropdown-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .durations-dropdown .dropdown-item small {
            font-size: 12px;
            color: #888;
        }
        .marker-image-slider {
            position: relative;
            height: 180px;
            overflow: hidden;
        }

        .slider-container {
            display: flex;
            height: 100%;
            transition: transform 0.3s ease;
        }

        .slider-container .slide img{
            border-radius: 8px 8px 0 0;
            height: 100%;
        }
        .slide {
            flex-shrink: 0;
            height: 100%;
        }

        .slider-controls {
            position: absolute;
            bottom: 10px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 6px;
            z-index: 10;
        }

        .slider-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: white;
            opacity: 0.5;
            cursor: pointer;
            transition: opacity 0.3s ease;
        }

        .slider-dot.active, .slider-dot.bg-opacity-100 {
            opacity: 1;
        }
        .arrorControls .slider-arrow{
            width: 24px !important;
            height: 24px !important;
            border-radius: 50% !important;
            background: var(--white-color) !important;
            color: var(--text-color-1) !important;
            font-size: 12px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .arrorControls{
            padding: 12px !important;
            transform: translateY(-50%);
            transition: 0.5s;
            opacity: 0;
            visibility: hidden;
        }
        .marker-content:hover .arrorControls{
            opacity: 1;
            visibility: visible;
        }
        .no-data-wrapper{
            display: flex;
            align-items: center;
            flex-direction: column;
        }
        .no-data-icon{
            height: 200px;
            width: 300px;
        }
        .pill-marker-active {
            background: #0c3bd5 !important;
            color: #fff !important;
            transform: scale(1.1);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            z-index: 9999;
        }
        .page-wrapper {
            overflow: initial !important;
        }
        .sticky-header {
            display: none;
        }
        .main-header {
            position: fixed !important;
            background: #fff;
        }
        .service-categories {
            padding: 70px 0 120px !important;
        }

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
        .location-search-dropdown {
            top: calc(100% + 27px) !important;
        }
        .destination-offcanvas .offcanvas-header {
            justify-content: flex-end;
        }
        .services-filer-btn .btn-1, .services-filer-btn .btn-2{
            border-radius: 8px !important;
        }
        #categoriesModal .modal-dialog-scrollable .modal-body {
            overflow-y: auto !important;
            max-height: 600px;
        }
        .count-container {
            top: calc(100% + 25px) !important;
        }
    </style>
@endpush

@include(template().'frontend.services.partials.list_scripts')

