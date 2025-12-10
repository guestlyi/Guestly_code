@extends(template().'layouts.user')
@section('title',trans('Dashboard'))
@section('content')
    <div class="container">
        <main class="main-content">
            <div class="header listing-top">
                <div class="welcome">
                    <h2>@lang('Welcome back'), {{ auth()->user()->firstname }}!</h2>
                    <p>@lang("Here's your hosting performance overview from ")  {{ $as_vendorDated }}</p>
                </div>
                @if(basicControl()->stripe_connect_status)
                    <div class="header-actions">
                        @if(auth()->user()->stripe_account_id && auth()->user()->stripe_onboarded)
                            <a class="btn-ai-glow" href="{{route('stripe.dashboard')}}"
                               target="_blank">@lang('Go to Stripe Dashboard')</a>
                        @else
                            <a class="btn-ai-glow" href="#"
                               data-bs-target="#countrySelect" data-bs-toggle="modal">@lang('Connect with Stripe')</a>
                        @endif
                    </div>
                @endif
            </div>

            <div class="row g-3 mb-3">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card stat-card">
                        <div class="stat-icon booking-icon"><i class="fas fa-calendar-check"></i></div>
                        <div class="stat-info">
                            <h3>{{ $upcomingBookings }}</h3>
                            <p>@lang('Upcoming Stays')</p>
                            <div class="trend {{ $upcomingPercentage > 0 ? 'up' : 'down' }}"><i
                                    class="fas {{ $upcomingPercentage > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i><span>{{ number_format($upcomingPercentage, 2) }}% @lang('from last month')</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card stat-card">
                        <div class="stat-icon revenue-icon"><i class="fas fa-dollar-sign"></i></div>
                        <div class="stat-info">
                            <h3>{{ currencyPosition($thisMonthHostReceived) }}</h3>
                            <p>@lang('This Month Earning')</p>
                            <div class="trend {{ $hostReceivedGrowth > 0 ? 'up' : 'down' }}"><i
                                    class="fas {{ $hostReceivedGrowth > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i><span>{{ number_format($hostReceivedGrowth, 2) }}% @lang('from last month')</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card stat-card">
                        <div class="stat-icon occupancy-icon"><i class="fas fa-wallet"></i></div>
                        <div class="stat-info">
                            <h3>{{ currencyPosition(auth()->user()->balance) }}</h3>
                            <p>@lang('Balance')</p>
                            <div class="trend up"><i
                                    class="fas fa-arrow-up"></i><span>100% @lang('from last month')</span></div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                    <div class="card stat-card">
                        <div class="stat-icon rating-icon"><i class="fas fa-star"></i></div>
                        <div class="stat-info">
                            <h3>{{ number_format(auth()->user()->vendorInfo->avg_rating ?? 0, 2) }}</h3>
                            <p>@lang('Average Rating')</p>
                            <div class="trend up"><i
                                    class="fas fa-arrow-up"></i><span>@lang('Average Rating for all time')</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metrics -->
            <div class="metrics-container">
                <div class="metric-card">
                    <div class="metric-header">
                        <i class="fa-regular fa-receipt hint" title="Replies within 24 hours"></i>
                        <h3>@lang('Refund Booking')</h3>
                    </div>
                    <div class="metric-value">{{ $refundedBookings ?? 0 }}</div>
                    <div class="metric-comparison">@lang('Percentage'): {{ number_format($refundedPercentage, 2) }}%
                    </div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <i class="fa-solid fa-cart-arrow-down hint" title="Inquiries that turned into bookings"></i>
                        <h3>@lang('Completed Booking')</h3>
                    </div>
                    <div class="metric-value">{{ $completedBookings ?? 0 }}</div>
                    <div class="metric-comparison">@lang('Percentage')
                        : {{ number_format($completedPercentage, 2) }}%</div>
                </div>

                <div class="metric-card">
                    <div class="metric-header">
                        <i class="fa-regular fa-circle-xmark hint" title="Canceled bookings ratio"></i>
                        <h3>@lang('Cancelled Booking')</h3>
                    </div>
                    <div class="metric-value text-danger">{{ $cancelledBookings ?? 0 }}</div>
                    <div class="metric-comparison">@lang('Percentage')
                        : {{ number_format($cancelledPercentage, 2) }}%</div>
                </div>
            </div>

            <div class="charts-container">
                <div class="chart-card">
                    <div class="chart-header">
                        <h3>@lang('Booking Chart')</h3>
                        <div class="chart-actions">
                            <button class="btn-range" data-range="7">@lang('Last 7 Days')</button>
                            <button class="btn-range active" data-range="30">@lang('Last 30 Days')</button>
                            <button class="btn-range" data-range="90">@lang('Last 90 Days')</button>
                        </div>
                    </div>
                    <div class="chart-content">
                        <canvas id="bookingChart" height="350"></canvas>
                    </div>
                </div>

{{--                <div class="transactions-section">--}}
{{--                    <div class="section-header">--}}
{{--                        <h3>@lang('Transactions')</h3>--}}
{{--                    </div>--}}

{{--                    <div class="transactions-list">--}}
{{--                        @foreach($transactions as $transaction)--}}
{{--                            <div class="transaction-card">--}}
{{--                                <div class="transaction-icon">--}}
{{--                                    <i class="fas fa-receipt"></i>--}}
{{--                                </div>--}}
{{--                                <div class="transaction-details">--}}
{{--                                    <div class="transaction-header">--}}
{{--                                        <h4>@lang('Transaction') #{{ $transaction->trx_id }}</h4>--}}
{{--                                        <span class="amount">{{ currencyPosition($transaction->amount) }}</span>--}}
{{--                                    </div>--}}
{{--                                    <div class="transaction-meta">--}}
{{--                                        <div class="meta-item">--}}
{{--                                            <span class="meta-label text-danger">@lang('Charge'):</span>--}}
{{--                                            <span--}}
{{--                                                class="meta-value text-danger">{{ currencyPosition($transaction->charge) }}</span>--}}
{{--                                        </div>--}}
{{--                                        <div class="meta-item">--}}
{{--                                            <span class="meta-label">@lang('Type'):</span>--}}
{{--                                            <span--}}
{{--                                                class="meta-value {{ $transaction->trx_type == '+' ? 'text-success' : 'text-danger' }}">{{ $transaction->trx_type ?: '-' }}</span>--}}
{{--                                        </div>--}}
{{--                                        <div class="meta-item">--}}
{{--                                            <span class="meta-label">@lang('Date'):</span>--}}
{{--                                            <span class="meta-value">{{ dateTime($transaction->created_at) }}</span>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="transaction-remarks">--}}
{{--                                        <p>{{ $transaction->remarks }}</p>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                    <div class="btn-area d-flex justify-content-center justify-content-center mb-4">--}}
{{--                        <a href="{{ route('user.transaction') }}" class="btn-3 other_btn">--}}
{{--                            <div class="btn-wrapper">--}}
{{--                                <div class="main-text btn-single">--}}
{{--                                    @lang('View All')--}}
{{--                                </div>--}}
{{--                                <div class="hover-text btn-single">--}}
{{--                                    @lang('View All')--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="transactions-section">
                    <div class="section-header">
                        <h3>@lang('Transactions')</h3>
                        <div class="filter-options">
                            <select id="transactionRangeSelect">
                                <option value="last30">@lang('Last 30 Days')</option>
                                <option value="last7">@lang('Last 7 Days')</option>
                                <option value="last90">@lang('Last 90 Days')</option>
                            </select>
                        </div>
                    </div>

                    <div class="transactions-list" id="transactionsList">

                    </div>

                    <div class="btn-area">
                        <a href="{{ route('user.transaction') }}" class="btn-3 other_btn">
                            <div class="btn-wrapper">
                                <div class="main-text btn-single">
                                    @lang('View All Transactions')
                                </div>
                                <div class="hover-text btn-single">
                                    @lang('View All Transactions')
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @if(!empty($upComingBookings) && $upComingBookings->count() > 0)
                <div class="booking-list">
                    <div class="booking-list-header">
                        <h3>@lang('Upcoming Check-Ins')</h3>
                        <a href="{{ route('user.reservations') }}" class="btn-3 other_btn">
                            <div class="btn-wrapper">
                                <div class="main-text btn-single">
                                    @lang('View All')
                                </div>
                                <div class="hover-text btn-single">
                                    @lang('View All')
                                </div>
                            </div>
                        </a>
                    </div>

                    @foreach($upComingBookings ?? [] as $upcoming)
                        <div class="booking-item">
                            <div class="booking-avatar"><img
                                    src="{{ getFile(optional($upcoming->guest)->image_driver, optional($upcoming->guest)->image) }}">
                            </div>
                            <div class="booking-info">
                                <h4>{{ optional($upcoming->guest)->firstname.' '.optional($upcoming->guest)->lastname }}</h4>
                                <p>
                                    {{ \Carbon\Carbon::parse($upcoming->check_in_date)->format('j M') }}
                                    -
                                    {{ \Carbon\Carbon::parse($upcoming->check_out_date)->format('j M') }},
                                    {{ $upcoming->information['adults'] + $upcoming->information['children'] }} @lang('guests')
                                    •
                                    {{ currencyPosition($upcoming->total_amount) }}
                                </p>
                            </div>
                            <span class="booking-status status-confirmed">@lang('Upcoming')</span>
                            <div class="booking-actions">
                                @php
                                    $userInfoWithUrls = $upcoming->user_info ?? [];

                                    if(!empty($userInfoWithUrls['adult']) && is_array($userInfoWithUrls['adult'])) {
                                        foreach($userInfoWithUrls['adult'] as &$adult) {
                                            if(!empty($adult['image']) && !empty($adult['image']['driver']) && !empty($adult['image']['path'])) {
                                                $adult['image_url'] = getFile($adult['image']['driver'], $adult['image']['path']);
                                            } else {
                                                $adult['image_url'] = null;
                                            }
                                        }
                                    }

                                    if(!empty($userInfoWithUrls['children']) && is_array($userInfoWithUrls['children'])) {
                                        foreach($userInfoWithUrls['children'] as &$child) {
                                            if(!empty($child['image']) && !empty($child['image']['driver']) && !empty($child['image']['path'])) {
                                                $child['image_url'] = getFile($child['image']['driver'], $child['image']['path']);
                                            } else {
                                                $child['image_url'] = null;
                                            }
                                        }
                                    }
                                @endphp
                                <button class="btn-mini details"
                                        data-uid="{{ $upcoming->uid }}"
                                        data-check_in_date="{{ dateTime($upcoming->check_in_date) }}"
                                        data-check_out_date="{{ dateTime($upcoming->check_out_date) }}"
                                        data-information='@json($upcoming->information)'
                                        data-user_info='@json($upcoming->user_info)'
                                        data-total_amount="{{ currencyPosition($upcoming->total_amount) }}"
                                        data-amount_without_discount="{{ currencyPosition($upcoming->amount_without_discount) }}"
                                        data-discount_amount="{{ currencyPosition($upcoming->discount_amount) }}"
                                        data-applied_discount="@json($upcoming->applied_discount)"
                                        data-site_charge="{{ currencyPosition($upcoming->site_charge) }}"
                                        data-host_received="{{ currencyPosition($upcoming->host_received) }}"
                                        data-status="{{ $upcoming->status }}"
                                        data-property_title="{{ optional($upcoming->property)->title }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#bookingDetailsModal"
                                >
                                    @lang('Details')
                                </button>
                                <a href="{{ route('user.messages', ['booking_uid' => $upcoming->uid]) }}"
                                   class="btn-mini btn-primary text-white">@lang('Message')</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </main>
    </div>

    <div class="modal fade" id="countrySelect" tabindex="-1" aria-labelledby="regenerateModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="regenerateModalLabel">@lang('Country Confirmation')</h4>
                    <button type="button" class="close-btn" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fal fa-times"></i>
                    </button>
                </div>
                <form action="{{route('stripe.connect')}}" method="get" target="_blank">
                    @csrf
                    <div class="modal-body">
                        <label class="form-label">@lang('Stripe Account Operate Country')</label>
                        <select id="stripeCountry" class="form-control select2" name="country">
                            @forelse(config('country') as $country)
                                <option
                                    value="{{ $country['code'] }}" {{$country['code'] == auth()->user()->country_code ? 'selected':''}}>@lang($country['name'])</option>
                            @empty
                            @endforelse
                        </select>
                    </div>
                    <div class="modal-footer stripModalBtn bx-shadow-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('No')</button>
                        <button class="btn btn-primary" type="submit">@lang('Confirm')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header position-relative">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar-check me-2"></i>@lang('Booking Details')
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <div class="modal-body p-0">
                    <div class="booking-nav-tabs">
                        <div class="nav-tabs-container">
                            <ul class="nav nav-tabs nav-fill" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="booking-tab" data-bs-toggle="tab"
                                            data-bs-target="#booking" type="button" role="tab">
                                        <i class="fas fa-info-circle me-2"></i>@lang('Booking Info')
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="guests-tab" data-bs-toggle="tab"
                                            data-bs-target="#guests" type="button" role="tab">
                                        <i class="fas fa-users me-2"></i>@lang('Guests')
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="payment-tab" data-bs-toggle="tab"
                                            data-bs-target="#payment" type="button" role="tab">
                                        <i class="fas fa-credit-card me-2"></i>@lang('Payment')
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="tab-content p-4">
                        <div class="tab-pane fade show active" id="booking" role="tabpanel">
                            <div class="booking-info-grid">
                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-hotel"></i>
                                    </div>
                                    <div class="info-content">
                                        <label>@lang('Property')</label>
                                        <p id="propertyTitle">-</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-tag"></i>
                                    </div>
                                    <div class="info-content">
                                        <label>@lang('Booking ID')</label>
                                        <p id="bookingId">-</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </div>
                                    <div class="info-content">
                                        <label>@lang('Check-in')</label>
                                        <p id="checkInDate">-</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-sign-out-alt"></i>
                                    </div>
                                    <div class="info-content">
                                        <label>@lang('Check-out')</label>
                                        <p id="checkOutDate">-</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    <div class="info-icon">
                                        <i class="fas fa-user-friends"></i>
                                    </div>
                                    <div class="info-content">
                                        <label>@lang('Guests')</label>
                                        <div class="guest-badges" id="guestsSummary">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="guests" role="tabpanel">
                            <div class="guest-cards-container" id="guestCardsContainer">
                            </div>
                        </div>

                        <div class="tab-pane fade" id="payment" role="tabpanel">
                            <div class="payment-details">
                                <div class="payment-item">
                                    <span class="payment-label">@lang('Amount without Discount')</span>
                                    <span class="payment-value" id="amountWithoutDiscount">-</span>
                                </div>

                                <div class="payment-item discount">
                                    <span class="payment-label">@lang('Discount Amount')</span>
                                    <span class="payment-value" id="discountAmount">-</span>
                                </div>

                                <div class="payment-item">
                                    <span class="payment-label">@lang('Site Charge')</span>
                                    <span class="payment-value" id="siteCharge">-</span>
                                </div>

                                <div class="payment-divider"></div>

                                <div class="payment-item total">
                                    <span class="payment-label">@lang('Total Amount')</span>
                                    <span class="payment-value" id="totalAmount">-</span>
                                </div>

                                <div class="payment-item">
                                    <span class="payment-label">@lang('Host Received')</span>
                                    <span class="payment-value" id="hostReceived">-</span>
                                </div>

                                <div class="payment-item status">
                                    <span class="payment-label">@lang('Status')</span>
                                    <span id="status">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer bx-shadow-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="button" class="btn-3 other_btn detailPrintBtn">
                        <i class="fas fa-download me-2"></i>@lang('Download')
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@include(template().'vendor.partials.dash_styles')
@include(template().'vendor.partials.dash_scripts')

