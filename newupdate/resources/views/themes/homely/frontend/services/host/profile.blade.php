@extends(template() . 'layouts.app')
@section('title',trans('Service host'))
@section('content')
    <section class="user-profile">
        <div class="container">
            <div class="user-profile-container">
                <div class="row g-4">
                    <div class="col-lg-3 offset-lg-2">
                        <a href="#0" class="user-profile-name">
                            <div class="user-profile-content">
                                <div class="user-profile-photo">
                                    <img src="{{ getFile($host->image_driver, $host->image) }}"
                                         alt="{{ $host->firstname.' '.$host->lastname }}">
                                </div>
                                <h4>{{ $host->firstname.' '.$host->lastname }}</h4>
                                @if($host->vendorInfo?->badge)
                                    <h6>{{ $host->vendorInfo?->badge }}</h6>
                                @endif
                            </div>
                            <div class="user-profile-info">
                                <div class="user-profile-info-list">
                                    <h4>{{ $host->host_review_count ?? 0 }}</h4>
                                    <span>@lang('Reviews')</span>
                                </div>
                                <div class="user-profile-info-list">
                                    <h4>{{ $host->vendorInfo?->avg_rating ?? 0 }}</h4>
                                    <span>@lang('Rating')</span>
                                </div>
                                @php
                                    use Carbon\Carbon;
                                    use Illuminate\Support\Str;

                                    $hostingDate = $host->vendorInfo->created_at;
                                    $now = Carbon::now();

                                    $diffInDays = $hostingDate->diffInDays($now);
                                    $diffInMonths = $hostingDate->diffInMonths($now);
                                    $diffInYears = $hostingDate->diffInYears($now);

                                    if ($diffInYears >= 1) {
                                        $years = $hostingDate->floatDiffInRealYears($now);
                                        $duration = number_format($years, 1);
                                         $type = 'Years';
                                    } elseif ($diffInMonths >= 1) {
                                        $months = $hostingDate->floatDiffInMonths($now);
                                        $duration = number_format($months, 1);
                                        $type = 'Months';
                                    } else {
                                        $days = $hostingDate->floatDiffInDays($now);
                                        $duration = number_format($days, 1);
                                         $type = 'Days';
                                    }
                                @endphp

                                <div class="user-profile-info-list">
                                    <h4>{{ $duration }}</h4>
                                    <span>{{ $type }} @lang('Hosting')</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-7">
                        <div class="right-side ps-4">
                            <div class="user-about">
                                <h3>{{ $host->firstname.' '.$host->lastname."'s" }} @lang('Details')</h3>
                                <ul class="user-about-list">
                                    <li><i class="fa-light fa-briefcase"></i> <strong>@lang('My work'): </strong>{{ $host->vendorInfo->my_work ?? 'Not Specified' }}</li>
                                    <li><i class="fa-light fa-music"></i> <strong>@lang('Music'): </strong> {{ $host->vendorInfo->music }}</li>
                                    <li><i class="fa-light fa-cat"></i> <strong>@lang('Pets'): </strong> {{ $host->vendorInfo->pets ? 'Yes' : 'No' }}</li>
                                    <li><i class="fa-solid fa-stars"></i>
                                        {{ $host->vendorInfo->intro }}
                                    </li>
                                </ul>
                                @if(isset($host->vendorInfo->facebook) || isset($host->vendorInfo->twitter) || isset($host->vendorInfo->linkedin) || isset($host->vendorInfo->instagram))
                                    <div class="social-media mt-4">
                                        <h5>{{ $host->firstname.' '.$host->lastname."'s" }} @lang('Media')</h5>
                                        <ul>
                                            @if(isset($host->vendorInfo->facebook))
                                                <li><a href="{{ $host->vendorInfo?->facebook }}"><i class="fa-brands fa-facebook-f"></i></a></li>
                                            @endif
                                            @if(isset($host->vendorInfo->twitter))
                                                <li><a href="{{ $host->vendorInfo?->twitter }}"><i class="icon-twiter"></i></a></li>
                                            @endif
                                            @if(isset($host->vendorInfo->instagram))
                                                <li><a href="{{ $host->vendorInfo?->instagram }}"><i class="fa-brands fa-instagram"></i></a></li>
                                            @endif
                                            @if(isset($host->vendorInfo->linkedin))
                                                <li><a href="{{ $host->vendorInfo?->linkedin }}"><i class="fa-brands fa-linkedin-in"></i></a></li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="user-profile-container">
                <div class="user-reviews">
                    <h4 class="mb-4">{{ $host->firstname.' '.$host->lastname."'s" }} @lang('reviews')</h4>
                    <div class="row g-4">
                        @foreach($host->hostReview->take(3) ?? [] as $hrv)
                            <div class="col-lg-4">
                                <div class="user-reviews-content">
                                    <div class="user-review-image">
                                        <img src="{{ getFile($hrv->guest->image_driver, $hrv->guest->image) }}" alt="{{ $hrv->guest?->firstname . ' '. $hrv->guest?->lastname }}">
                                        <p>{{ $hrv->guest?->firstname . ' '. $hrv->guest?->lastname }}</p>
                                    </div>
                                    @php
                                        $rating = round($hrv->avg_rating, 1);
                                        $fullStars = floor($rating);
                                        $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                                        $emptyStars = 5 - $fullStars - $halfStar;
                                    @endphp

                                    <div class="user-rating">
                                        <ul class="d-flex">
                                            @for ($i = 0; $i < $fullStars; $i++)
                                                <li><i class="fa-solid fa-star text-warning"></i></li>
                                            @endfor

                                            @if ($halfStar)
                                                <li><i class="fa-solid fa-star-half-stroke text-warning"></i></li>
                                            @endif

                                            @for ($i = 0; $i < $emptyStars; $i++)
                                                <li><i class="fa-regular fa-star text-warning"></i></li>
                                            @endfor
                                        </ul>
                                        @php
                                            $date = Carbon::parse($hrv->created_at);
                                        @endphp
                                        <span>{{ $date->diffForHumans() }}</span>
                                    </div>
                                    <p class="user-description mt-3">{{ $hrv->comment ?? 'Not Available Comment' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <a class="btn-1 mt-4" href="#0" data-bs-target="#allReviewModal" data-bs-toggle="modal">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                @lang('Show all') {{ $host->host_review_count }} @lang('reviews')
                            </div>
                            <div class="hover-text btn-single">
                                @lang('Show all') {{ $host->host_review_count }} @lang('reviews')
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="user-profile-container host-item-part">
                <div class="head-area d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h4 class="mb-0">{{ $host->firstname.' '.$host->lastname."'s" }} @lang('properties')</h4>
                    <div class="property-sort">
                        <select id="propertySort" class="nice-select w-auto">
                            <option value="newest">@lang('Newest')</option>
                            <option value="oldest">@lang('Oldest')</option>
                            <option value="price_asc">@lang('Price (Low to High)')</option>
                            <option value="price_desc">@lang('Price (High to Low)')</option>
                        </select>
                    </div>
                </div>

                <div class="row g-4 mt-4" id="property-list"></div>

                <div class="text-center mt-4 d-none" id="loadMoreWrapperTwo">
                    <button id="loadMoreBtn" data-page="2" class="btn btn-primary">
                        @lang('Load More')
                    </button>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="allReviewModal" tabindex="-1" aria-labelledby="allReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xxl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allReviewModalLabel">{{ $host->firstname.' '.$host->lastname."'s" }} @lang('All Reviews')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <div id="all-reviews-list" class="row g-4"></div>

                        <div class="text-center mt-4" id="loadMoreWrapper">
                            <button id="loadMoreReviews" class="btn btn-primary" data-page="1" data-host-id="{{ $host->id }}">
                                @lang('Load More Reviews')
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .user-reviews .user-reviews-content .user-rating ul li{
            color: rgba(var(--bs-warning-rgb),var(--bs-text-opacity))!important;
        }

        .loading-dots {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }
        .loading-dots .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: var(--primary-color);
            animation: dotPulse 1.4s infinite ease-in-out both;
        }
        .loading-dots .dot:nth-child(2) { animation-delay: -0.16s; }
        .loading-dots .dot:nth-child(3) { animation-delay: -0.32s; }

        @keyframes dotPulse {
            0%, 80%, 100% { transform: scale(0); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }

        .loading-dots span {
            animation: blink 1.4s infinite both;
            font-weight: bold;
            font-size: 1.2em;
        }
        .loading-dots span:nth-child(2) { animation-delay: 0.2s; }
        .loading-dots span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes blink {
            0%, 80%, 100% { opacity: 0; }
            40% { opacity: 1; }
        }

        .host-item-part{
            border-bottom: none !important;
        }
        #allReviewModal .modal-dialog {
            max-width: 1100px !important;
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            $('#allReviewModal').on('shown.bs.modal', function () {
                $('#all-reviews-list').html('');
                $('#loadMoreReviews').data('page', 1).trigger('click');
            });

            $('#loadMoreReviews').on('click', function () {
                let button = $(this);
                let page = button.data('page');
                let hostId = button.data('host-id');

                button.text('Loading...');

                $.ajax({
                    url: "{{ route('host.reviews.load') }}",
                    method: 'GET',
                    data: { host_id: hostId, page: page },
                    success: function (response) {
                        $('#all-reviews-list').append(response.html);
                        button.data('page', page + 1);
                        button.text('Load More Reviews');
                        if (!response.hasMore) $('#loadMoreWrapper').hide();
                    },
                    error: function () {
                        button.text('Error! Try Again');
                    }
                });
            });


            function loadProperties(page = 1, append = false, userLat = null, userLng = null) {
                const hostId = "{{ $host->id }}";
                const loadMoreBtn = document.getElementById('loadMoreBtn');
                const loadMoreWrapperTwo = document.getElementById('loadMoreWrapperTwo');
                const sortBy = $('#propertySort').val();

                $.ajax({
                    url: "{{ route('host.properties.load') }}",
                    method: 'GET',
                    data: {
                        host_id: hostId,
                        page: page,
                        sort_by: sortBy,
                        user_lat: userLat,
                        user_lng: userLng
                    },
                    beforeSend: function () {
                        loadMoreWrapperTwo.classList.add('d-none');
                        if (!append) {
                            $('#property-list').html(`
                            <div class="loading-dots text-center py-5">
                                <div class="dot"></div>
                                <div class="dot"></div>
                                <div class="dot"></div>
                            </div>
                        `);
                        }
                    },
                    success: function (response) {
                        if (append) {
                            $('#property-list').append(response.html);
                        } else {
                            $('#property-list').html(response.html);
                        }

                        if (response.hasMore) {
                            loadMoreBtn.setAttribute('data-page', page + 1);
                            loadMoreBtn.innerHTML = '@lang("Load More")';
                            loadMoreWrapperTwo.classList.remove('d-none');
                        } else {
                            loadMoreWrapperTwo.classList.add('d-none');
                        }
                    },
                    error: function () {
                        loadMoreBtn.textContent = '@lang("Error! Try Again")';
                    }
                });
            }

            function initPropertyLoad(userLat = null, userLng = null) {
                loadProperties(1, false, userLat, userLng);

                const loadMoreBtn = document.getElementById('loadMoreBtn');
                loadMoreBtn?.addEventListener('click', function () {
                    const page = parseInt(this.getAttribute('data-page'), 10);

                    this.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        @lang('Loading...')
                    `;
                    this.disabled = true;

                    loadProperties(page, true, userLat, userLng);

                });

                $('#propertySort').on('change', function () {
                    loadProperties(1, false, userLat, userLng);
                });
            }

            navigator.geolocation.getCurrentPosition(
                function (position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    initPropertyLoad(lat, lng);
                },
                function (error) {
                    console.warn("Geolocation error:", error.message);
                    initPropertyLoad();
                },
                {
                    enableHighAccuracy: true,
                    timeout: 5000,
                    maximumAge: 0
                }
            );

        });
    </script>
@endpush
