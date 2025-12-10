@extends(template() . 'layouts.app')
@section('title',trans('Service Details'))
@section('content')
    <section class="service-gallery">
        <div class="container">
            <div class="service-top-meta">
                <div class="properties">
                    <a href="{{ route('services') }}"><i class="fa-light fa-angle-left"></i> @lang('See all properties')</a>
                </div>
                <div class="service-top-share ">

                    <button type="button" class="btn-3 shareBtn">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                <i class="fa-light fa-share-from-square"></i> @lang('Share')
                            </div>
                            <div class="hover-text btn-single">
                                <i class="fa-light fa-share-from-square"></i> @lang('Share')
                            </div>
                        </div>
                    </button>
                    <button type="button" class="btn-3 favouritlistBtn"  data-product_id="{{ $property->id }}">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                <i class="{{ $is_wishlisted ? 'fa-solid fa-heart' :  'fa-light fa-heart'}}"></i> @lang('Save')
                            </div>
                            <div class="hover-text btn-single">
                                <i class="{{ $is_wishlisted ? 'fa-solid fa-heart' :  'fa-light fa-heart'}}"></i> @lang('Save')
                            </div>
                        </div>
                    </button>
                </div>
            </div>
            <div class="service-gallery-container">
                @php
                    $images = array_slice($property->photos?->images['images'] ?? [], 0, 5);
                @endphp
                @if(!empty($images) && count($images) > 0)
                    <div class="service-gallery-left">
                        <a href="{{ route('service.images', $property->slug) }}" class="open-modal">
                            <img src="{{ getFile($images[0]['driver'], $images[0]['path']) }}" alt="image">
                        </a>
                    </div>
                    <div class="service-gallery-right">
                        @foreach(array_slice($images, 1) as $index => $image)
                            <div class="service-gallery-right-image">
                                <a href="{{ route('service.images', $property->slug) }}" class="open-modal">
                                    <img src="{{ getFile($image['driver'], $image['path']) }}" alt="image">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="services-overview pt-0">
        <div class="container">
            <div class="services-overview-tab">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-overview-tab" data-bs-toggle="tab" data-bs-target="#nav-overview" type="button" role="tab" aria-controls="nav-overview" aria-selected="true">@lang('Overview')</button>
                        <button class="nav-link" id="nav-amenities-tab" data-bs-toggle="tab" data-bs-target="#nav-amenities" type="button" role="tab" aria-controls="nav-amenities" aria-selected="false">@lang('Amenities')</button>
                    </div>
                </nav>
                <div class="row">
                    <div class="col-lg-7">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
                                <div class="services-overview-container">
                                    <div class="services-overview-left">
                                        <div class="services-overview-title">
                                            <h3>{!! strip_tags($property->title ?? ' ') !!}</h3>
                                        </div>
                                        <div class="services-overview-meta">
                                            <div class="rat"><i class="fa-light fa-door-open"></i> {{ $property->features?->bedrooms }} @lang(' bedrooms')</div>
                                            <div class="rat"><i class="fa-light fa-bath"></i> {{ $property->features?->bathrooms }} @lang(' bathroom')</div>
                                            <div class="rat"><i class="fa-thin fa-users"></i> @lang('max ') {{ $property->features?->max_guests }} @lang('guests')</div>
                                            <div id="others-container" class="others-wrapper">
                                                @foreach($property->features?->others as $key => $otherItem)
                                                    @if($otherItem == 1)
                                                        <div class="rat other-item d-none">
                                                            <i class="fa-light fa-thumbs-up"></i> {{ ucfirst(str_replace('_', ' ', $key)) }}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>

                                        <h6 class="service-details-review"><i class="fa-sharp fa-solid fa-star"></i>
                                            {{ number_format($property->review_avg_avg_rating, 1) ?? ''}} ({{ $property->review_count }}) @lang('reviews')</h6>
                                        <div class="services-host">
                                            <div class="bg-layer" style="background: url({{ asset(template(true).'img/background/host-bg.jpg') }});"></div>
                                            <div class="host-image">
                                                <img src="{{ getFile($property->host?->image_driver, $property->host?->image) }}" alt="image">
                                            </div>
                                            @php
                                                $created = $property->host->created_at;
                                                $diff = $created ? $created->diff(now()) : null;

                                                if ($diff) {
                                                    if ($diff->y > 0) {
                                                        $hostingTime = $diff->y . ' ' . __('years hosting');
                                                    } elseif ($diff->m > 0) {
                                                        $hostingTime = $diff->m . ' ' . __('months hosting');
                                                    } elseif ($diff->d > 0) {
                                                        $hostingTime = $diff->d . ' ' . __('days hosting');
                                                    } elseif ($diff->h > 0) {
                                                        $hostingTime = $diff->h . ' ' . __('hours hosting');
                                                    } elseif ($diff->i > 0) {
                                                        $hostingTime = $diff->i . ' ' . __('minutes hosting');
                                                    } else {
                                                        $hostingTime = $diff->s . ' ' . __('seconds hosting');
                                                    }
                                                } else {
                                                    $hostingTime = __('Just joined');
                                                }
                                            @endphp

                                            <div class="host-info">
                                                <h6>@lang('Hosted by ') {{ $property->host->firstname.' '.$property->host->lastname }}</h6>
                                                <p>{{ $hostingTime }}</p>
                                            </div>
                                        </div>
                                        <div class="host-description">
                                            <p id="property-description">
                                                <span id="description-content">
                                                    {!! Str::limit($property->description, 200) !!}
                                                </span>
                                            </p>
                                            <a href="javascript:void(0);" id="toggle-description">@lang('Show More') <i class="fa-light fa-angle-right"></i></a>
                                        </div>

                                    </div>
                                    <div class="service-amenities">
                                        <h4 class="service-details-common-title">@lang('Popular Amenities Offered')</h4>
                                        <div class="row">
                                            @if(!empty($property->amenities))
                                                @php
                                                    $filteredAmenities = collect($property->amenities)->filter(function($amenity) {
                                                        return $amenity['isInThisProperty'] == 1;
                                                    })->toArray();
                                                @endphp

                                                @if(count($filteredAmenities) > 0)
                                                    @foreach(array_chunk($filteredAmenities, ceil(count($filteredAmenities) / 2)) as $amenitiesChunk)
                                                        <div class="col-md-4">
                                                            <div class="service-amenities-list">
                                                                <ul>
                                                                    @foreach($amenitiesChunk as $amenity)
                                                                        <li><a href="#0"><i class="{{ $amenity['icon'] }}"></i> {{  $amenity['title'] }}</a></li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="nav-amenities" role="tabpanel" aria-labelledby="nav-amenities-tab">
                                <div class="services-overview-container">
                                    <div class="services-overview-left">
                                        <div class="services-overview-title">
                                            <h3>{!! strip_tags($property->title) ?? ' ' !!}</h3>
                                        </div>
                                        <div class="services-overview-meta">
                                            <div class="rat"><i class="fa-light fa-door-open"></i> {{ $property->features?->bedrooms }} @lang(' bedrooms')</div>
                                            <div class="rat"><i class="fa-light fa-bath"></i> {{ $property->features?->bathrooms }} @lang(' bathroom')</div>
                                            <div class="rat"><i class="fa-thin fa-users"></i> @lang('max ') {{ $property->features?->max_guests }} @lang('guests')</div>
                                            <div id="others-container">
                                                @foreach($property->features?->others as $key => $otherItem)
                                                    @if($otherItem == 1)
                                                        <div class="rat other-item d-none">
                                                            <i class="fa-light fa-thumbs-up"></i> {{ ucfirst(str_replace('_', ' ', $key)) }}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="service-amenities">
                                        <h4 class="service-details-common-title">@lang('Amenities')</h4>
                                        <div class="row">
                                            @if(!empty($property->amenities) && count($property->amenities) > 0)
                                                @foreach(array_chunk($property->amenities->toArray(), ceil(count($property->amenities) / 2)) as $amenitiesChunk)
                                                    <div class="col-md-4">
                                                        <div class="service-amenities-list">
                                                            <ul>
                                                                @foreach($amenitiesChunk as $amenity)
                                                                    <li>
                                                                        <a href="#0">
                                                                            <i class="{{ $amenity['icon'] }}"></i>
                                                                            @if($amenity['isInThisProperty'] == 1)
                                                                                <span>{{  $amenity['title'] }}</span>
                                                                            @else
                                                                                <span style="text-decoration: line-through;">{{  $amenity['title'] }}</span>
                                                                            @endif
                                                                        </a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <div class="services-overview-right">
                            <div class="services-overview-right-container">
                                <div class="services-overview-form">
                                    <form id="bookingForm" action="{{ route('user.booking.info.store') }}" method="post">
                                        @csrf

                                        <div class="services-overview-form-title">
                                            <h4>{{ userCurrencyPosition($property->pricing?->nightly_rate) }} <span>@lang('/ Night')</span></h4>
                                            <p>
                                                @lang($property->pricing->refundable == 1 ? 'Refundable' : 'Non-refundable')
                                                <i class="fa-thin fa-circle-exclamation refundTxtMsg"
                                                   data-bs-toggle="tooltip"
                                                   data-bs-placement="top"
                                                   title="{{ $property->pricing->refundable == 1 ? $property->pricing->refund_message : 'This product is not refundable' }}">
                                                </i>
                                            </p>
                                        </div>

                                        <div class="date">
                                            <div class="select-option">
                                                <div class="select-icon">
                                                    <i class="fa-light fa-door-open"></i>
                                                </div>
                                                <div class="select-option-content">
                                                    <h6 class="select-option-title">@lang('Check in/out')</h6>
                                                    <input type="text" id="date-picker" name="datefilter" placeholder="12/12/2024 - 14/12/2024" autocomplete="off">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="count">
                                            <input type="hidden" name="property_id" id="property_id" value="{{ $property->id }}" />
                                            <input type="hidden" name="adult_count" id="adult_count" value="0" />
                                            <input type="hidden" name="children_count" id="children_count" value="0" />
                                            <input type="hidden" name="pet_count" id="pet_count" value="0" />

                                            <div class="count-counter select-option">
                                                <div class="select-icon">
                                                    <i class="fa-light fa-user"></i>
                                                </div>
                                                <div class="select-option-content">
                                                    <h6 class="select-option-title">@lang('Guests')</h6>
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
                                                            <span class="pet">0</span>
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
                                                        <button type="button" class="decrement"><i class="fa-regular fa-minus"></i></button>
                                                        <span class="adult">0</span>
                                                        <button type="button" class="increment"><i class="fa-regular fa-plus"></i></button>
                                                    </div>
                                                </div>

                                                <div class="count-single">
                                                    <div class="count-single-text">
                                                        <h6>@lang('Children')</h6>
                                                        <p>@lang('Below 12 Years')</p>
                                                    </div>
                                                    <div class="count-single-inner">
                                                        <button type="button" class="decrementTwo"><i class="fa-regular fa-minus"></i></button>
                                                        <span class="childeren">0</span>
                                                        <button type="button" class="incrementTwo"><i class="fa-regular fa-plus"></i></button>
                                                    </div>
                                                </div>

                                                <div class="count-single">
                                                    <div class="count-single-text">
                                                        <h6>@lang('Pet')</h6>
                                                    </div>
                                                    <div class="count-single-inner">
                                                        <button type="button" class="decrementThree"><i class="fa-regular fa-minus"></i></button>
                                                        <span class="pet">0</span>
                                                        <button type="button" class="incrementThree"><i class="fa-regular fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="services-overview-form-list">
                                            <ul>
                                                <li>
                                                    <span class="item">{{ userCurrencyPosition($property->pricing?->nightly_rate) }}  x <span id="night-count">3</span> <small>Nights</small></span>
                                                    <span class="amount" id="total-night-price">{{ userCurrencyPosition($property->pricing?->nightly_rate * 3) }} </span>
                                                </li>
                                                <li>
                                                    <span class="amount">@lang('Total')</span>
                                                    <span>
                                                        <span class="amount" id="final-total">{{ userCurrencyPosition($property->pricing?->nightly_rate * 3) }}</span>
                                                        <span class="amount final-initial-parent text-danger d-none">
                                                            <del id="final-initial-total">0</del>
                                                        </span>
                                                    </span>
                                                </li>
                                                <li>
                                                    <p>@lang('Tax and fees included')</p>
                                                    <a href="#0" id="price-details-link">@lang('Price details')</a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="services-overview-form-btn">
                                            <button type="submit"
                                                    class="btn-1"
                                                    id="bookNowBtn"
                                                    @if(auth('affiliate')->id() && !auth()->id())
                                                        data-affiliate="1"
                                                @endif
                                            >
                                                <div class="btn-wrapper">
                                                    <div class="main-text btn-single">
                                                        @lang('Book Now')
                                                    </div>
                                                    <div class="hover-text btn-single">
                                                        @lang('Book Now')
                                                    </div>
                                                </div>
                                            </button>

                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="services-overview-report mb-5">
                                <a href="#0" data-bs-target="#reportModal" data-bs-toggle="modal"><i class="fa-light fa-flag"></i> @lang('Report this listing')</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include(template().'frontend.services.partials.review')
        </div>
    </section>
    @include(template().'frontend.services.partials.ratings')

    <section class="service-details-map pt-0">
        <div class="container">
            <div class="service-details-map-container">
                <div class="row g-4">
                    <div class="col-lg-6">
                        <div class="service-details-host">
                            <h4 class="service-details-common-title"> @lang('Meet your Host')</h4>
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="rating-host-info">
                                        <a href="{{ route('service.hosts', $property->host?->username) }}" class="host-link">
                                            <div class="host-image">
                                                <img src="{{ getFile($property->host?->image_driver, $property->host?->image) }}" alt="image">
                                            </div>
                                            <h6 class="mb-0 mt-2 text-dark">{{ $property->host?->firstname.' '. $property->host?->lastname }}</h6>
                                        </a>
                                        @if($property->host?->vendorInfo?->badge)
                                            <p><i class="fa-light fa-user"></i> {{ $property->host?->vendorInfo?->badge }}</p>
                                        @endif

                                        <div class="host-info-list">
                                            <ul>
                                                <li>
                                                    <h6>{{ $host_review_count }}</h6>
                                                    <span>@lang('Reviews')</span>
                                                </li>
                                                <li>
                                                    <h6>{{ $property->host?->vendorInfo?->avg_rating }}</h6>
                                                    <span>@lang('Rating')</span>
                                                </li>
                                                @php
                                                    use Carbon\Carbon;

                                                    $createdAt = Carbon::parse($property->host?->created_at);
                                                    $now = Carbon::now();
                                                    $diffInDays = $createdAt->diffInDays($now);
                                                    $diffInYears = round($diffInDays / 365.25, 1);
                                                @endphp

                                                <li>
                                                    <h6>{{ $diffInYears }}</h6>
                                                    <span>@lang('Years')</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="host-right-info">
                                        <div class="host-right-info-box">
                                            <h6>@lang('About')</h6>
                                            <p>{{ Str::limit($property->host?->vendorInfo?->intro, 90, '...') }}</p>
                                        </div>
                                        <div class="host-right-info-box">
                                            <h6>@lang('Host details')</h6>
                                            <p id="response-rate"></p>
                                            <p id="response-time"></p>
                                        </div>
                                        <div class="host-right-info-btn">
                                            <a class="btn-1" href="{{ route('user.messages', ['property_slug' => $property->slug]) }}">
                                                <div class="btn-wrapper">
                                                    <div class="main-text btn-single">
                                                        @lang('Message Here')
                                                    </div>
                                                    <div class="hover-text btn-single">
                                                        @lang('Message Here')
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="host-right-info-list">
                                <ul>
                                    <li>@lang('Profession : '){{ $property->host?->vendorInfo?->my_work ? $property->host?->vendorInfo?->my_work : 'Not Specified' }}</li>
                                    <li>@lang('Favourite Music : ') {{ $property->host?->vendorInfo?->music ? $property->host?->vendorInfo?->music : 'Not Specified' }}</li>
                                    <li>@lang('Pets : ') {{ $property->host?->vendorInfo?->pets ? 'yes' : 'No' }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="service-details-map-right">
                            <h4 class="service-details-common-title">@lang('Explore the area')</h4>
                            <div class="map position-relative">
                                <div id="mapSearchWrapper" class="map-search-wrapper">
                                    <button id="mapSearchToggle" class="map-search-toggle">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <div id="mapSearchContainer" class="map-search-container hidden">
                                        <div class="map-search-card">
                                            <input type="text" id="mapSearchInput" placeholder="Search location..." />
                                            <div id="mapSearchResults" class="map-search-results"></div>
                                        </div>
                                    </div>
                                </div>
                                <button id="expandMapBtn" class="expand-map-btn">
                                    <i class="fas fa-arrow-up-right-and-arrow-down-left-from-center"></i>
                                </button>

                                <div id="googleMap" class="map-container rounded"></div>
                            </div>
                            @php
                                $nearestPlaces = is_string($property->nearest_places)
                                    ? json_decode($property->nearest_places, true)
                                    : $property->nearest_places;
                            @endphp

                            <h6 class="mt-4 fw-bold">@lang('Near This Area')</h6>

                            <div class="near-this-area">
                                <ul>
                                    @foreach($nearestPlaces ?? [] as $index => $place)
                                        @php
                                            $lat = $place['lat'] ?? null;
                                            $lng = $place['lng'] ?? null;
                                            $title = $place['title'] ?? '-';
                                            $distance = $place['distance'] ?? '-';
                                        @endphp
                                        <li class="{{ $index >= 2 ? 'd-none' : '' }}">
                                            <span>
                                                <i class="fa-light fa-location-dot text-danger"></i>
                                                {{ $title }}
                                            </span>
                                            <span>
                                                {{ $distance }} km
                                                @if($lat && $lng)
                                                    <button type="button"
                                                            class="btn-3 view-map-icon"
                                                            data-lat="{{ $lat }}"
                                                            data-long="{{ $lng }}">
                                                        <div class="btn-wrapper">
                                                            <div class="main-text btn-single">
                                                                <i class="fa-regular fa-compass"></i>
                                                            </div>
                                                            <div class="hover-text btn-single">
                                                                <i class="fa-regular fa-compass"></i>
                                                            </div>
                                                        </div>

                                                    </button>
                                                @endif
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>

                                @if(count($nearestPlaces ?? []) > 2)
                                    <div class="text-center mt-2">
                                        <button id="togglePlacesBtn" class="btn btn-outline-secondary btn-sm">
                                            @lang('Show More')
                                        </button>
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include(template().'frontend.services.partials.things_to_know')
    @include(template().'frontend.services.partials.modals')
@endsection
@include(template().'frontend.services.partials.style')
@include(template().'frontend.services.partials.scripts')
