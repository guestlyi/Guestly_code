@extends(template().'layouts.affiliate')
@section('title',trans('Affiliate Dashboard'))
@section('content')
    <section class="affiliate-dashboard">
        <div class="container">
            <div class="dashboard-header mb-4">
                <div
                    class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">
                    <div>
                        <h2 class="greeting-title">@lang('Welcome back'), {{ auth('affiliate')->user()->firstname }}!
                            👋</h2>
                        <p class="greeting-subtitle text-muted">
                            @lang('Your affiliate performance summary'):
                            <span
                                class="{{ $conversionGrowthPercentage >= 0 ? 'text-success' : 'text-danger' }} ">{{ $conversionGrowthPercentage >= 0 ? '↑' : '↓' }} {{ $conversionGrowthPercentage }}%</span> @lang('from last period')
                        </p>
                    </div>
                    <div class="d-flex gap-3 mt-3 mt-md-0">
                        @if(basicControl()->stripe_connect_status)
                            @if(auth()->user()->stripe_account_id && auth()->user()->stripe_onboarded)
                                <a class="btn-ai-glow" href="{{route('stripe.dashboard')}}"
                                   target="_blank">@lang('Go to Stripe Dashboard')</a>
                            @else
                                <a class="btn-ai-glow" href="#"
                                   data-bs-target="#countrySelect"
                                   data-bs-toggle="modal">@lang('Connect with Stripe')</a>
                            @endif
                        @endif
                        <a href="{{ route('affiliate.item.list') }}" class="btn btn-primary itemListBtn">
                            <i class="fas fa-list me-2"></i> @lang('Promotable Items')
                        </a>
                    </div>
                </div>

                <div class="promo-banner mt-3">
                    <div class="promo-banner-inner d-flex align-items-center">
                        <div class="promo-icon me-3">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div class="flex-grow-1">
                            <strong>@lang('Earn up to') {{ rtrim(rtrim(number_format(basicControl()->affiliate_commission_percentage, 8, '.', ''), '0'), '.') }}
                                % @lang('commission')</strong>@lang(' on every booking')
                            . @lang('Your current conversion rate is') <strong>{{ $thisMonthConversionPercentage }}
                                %</strong>
                            ({{ $this_month_transaction_count }} @lang('conversions this month')).
                        </div>
                        <div class="ms-3">
                            <span
                                class="badge bg-warning text-dark">{{ basicControl()->affiliate_commission_percentage.'% running' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="metrics-dashboard mb-5">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="metric-card earnings-card">
                            <div class="metric-content">
                                <div class="metric-icon">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="metric-info">
                                    <h6 class="metric-label">@lang('Earnings Summary')</h6>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>@lang('Today'):</span>
                                        <strong>{{ currencyPosition($today_earning) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>@lang('This Week'):</span>
                                        <strong>{{ currencyPosition($this_week_earning) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>@lang('This Month'):</span>
                                        <strong>{{ currencyPosition($this_month_earning) }}</strong>
                                    </div>
                                    <div class="metric-trend positive mt-3">
                                        <i class="fas fa-arrow-up"></i> {{ $earning_growth_percentage }}
                                        % @lang('from last month')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="metric-card clicks-card">
                            <div class="metric-content">
                                <div class="metric-icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="metric-info">
                                    <h6 class="metric-label">@lang('Traffic Analytics')</h6>
                                    <div class="traffic-stats">
                                        <div class="traffic-source mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span>@lang('Total Clicks'):</span>
                                                <strong>{{ auth('affiliate')->user()->total_click ?? 0 }}</strong>
                                            </div>
                                            <div class="progress mt-1" style="height: 6px;">
                                                <div class="progress-bar bg-primary" style="width: 100%"></div>
                                            </div>
                                        </div>
                                        <div class="traffic-source mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span>@lang('This Month'):</span>
                                                <strong>{{ $this_month_clicks }}</strong>
                                            </div>
                                            <div class="progress mt-1" style="height: 6px;">
                                                <div class="progress-bar bg-info"
                                                     style="width: {{ $this_month_click_percentage }}%"></div>
                                            </div>
                                        </div>
                                        <div class="traffic-source">
                                            <div class="d-flex justify-content-between">
                                                <span>@lang('Conversion Rate'):</span>
                                                <strong>{{ $thisMonthConversionPercentage }}%</strong>
                                            </div>
                                            <div class="progress mt-1" style="height: 6px;">
                                                <div class="progress-bar bg-success"
                                                     style="width: {{ $thisMonthConversionPercentage }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="metric-card balance-card">
                            <div class="metric-content">
                                <div class="metric-icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <div class="metric-info">
                                    <h6 class="metric-label">@lang('Balance & Payouts')</h6>
                                    <div class="balance-summary">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>@lang('Available'):</span>
                                            <strong>{{ currencyPosition(auth('affiliate')->user()->balance) }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>@lang('Pending'):</span>
                                            <strong>{{ currencyPosition($pending_balance ?? 0) }}</strong>
                                        </div>
                                        <div class="d-flex justify-content-between mb-3">
                                            <span>@lang('Total Earned'):</span>
                                            <strong>{{ currencyPosition(auth('affiliate')->user()->pending_balance + auth('affiliate')->user()->balance) }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-lg-12">
                    <div class="chart-section">
                        <div class="section-header d-flex justify-content-between align-items-center mb-4">
                            <h3 class="section-title">@lang('Earnings Performance')</h3>
                            <div class="chart-controls">
                                <select class="form-select form-select-sm time-range-select">
                                    <option value="7">@lang('Last 7 days')</option>
                                    <option value="30" selected>@lang('Last 30 days')</option>
                                    <option value="90">@lang('Last 90 days')</option>
                                    <option value="year">@lang('This year')</option>
                                </select>
                            </div>
                        </div>
                        <div class="chart-container card">
                            <div class="card-body" style="height: 350px;">
                                <canvas id="earningChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-5 g-5">
                <div class="col-md-6">
                    <div class="quick-actions-section">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="section-title mb-0">@lang('Quick Actions')</h3>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="action-card">
                                    <div class="card-body text-center">
                                        <div class="action-icon mb-3 mx-auto">
                                            <i class="fas fa-clock"></i>
                                        </div>
                                        <h5 class="action-title">@lang('Pending Earning')</h5>
                                        <a class="btn btn-sm btn-primary w-100"
                                           href="{{ route('affiliate.pending.earning') }}">@lang('View')</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="action-card">
                                    <div class="card-body text-center">
                                        <div class="action-icon mb-3 mx-auto">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <h5 class="action-title">@lang('Payments')</h5>
                                        <a class="btn btn-sm btn-primary w-100"
                                           href="{{ route('affiliate.payments') }}">@lang('View')</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="action-card">
                                    <div class="card-body text-center">
                                        <div class="action-icon mb-3 mx-auto">
                                            <i class="fas fa-exchange-alt"></i>
                                        </div>
                                        <h5 class="action-title">@lang('Transactions')</h5>
                                        <a class="btn btn-sm btn-primary w-100"
                                           href="{{ route('affiliate.transactions') }}">@lang('View')</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="action-card">
                                    <div class="card-body text-center">
                                        <div class="action-icon mb-3 mx-auto">
                                            <i class="fas fa-chart-pie"></i>
                                        </div>
                                        <h5 class="action-title">@lang('Analytics')</h5>
                                        <a class="btn btn-sm btn-primary w-100"
                                           href="{{ route('affiliate.analytics') }}">@lang('view')</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="top-products-section">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3 class="section-title mb-0">@lang('Top Performing Products')</h3>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive scrollable-table">
                                    <table class="table table-hover mb-0" id="topProductsTable">
                                        <thead>
                                        <tr>
                                            <th>@lang('Product')</th>
                                            <th>@lang('Clicks')</th>
                                            <th>@lang('Conversions')</th>
                                            <th>@lang('Earnings')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($topProperties ?? [] as $index => $product)
                                            <tr class="product-row {{ $index >= 5 ? 'd-none' : '' }}">
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img
                                                            src="{{ getFile($product->property?->photos?->images['thumb']['driver'], $product->property?->photos?->images['thumb']['path']) }}"
                                                            alt="{{ $product->property?->title ?? '' }}"
                                                            class="product-thumb me-2">
                                                        <span>{{ Str::limit($product->property?->title , 20) }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ $product->total_clicks ?? '0' }}</td>
                                                <td>{{ $product->transaction_count ?? 0 }}</td>
                                                <td class="text-success">{{ currencyPosition($product->total_amount ?? 0) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center py-5">
                                                    <div class="empty-state">
                                                        <h5 class="text-muted">@lang('No Data to Show')</h5>
                                                        <p class="text-muted small">@lang('There are no records available at this moment.')</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if(($topProperties->count() ?? 0) > 5)
                                    <div class="text-center mt-3">
                                        <button id="loadMoreBtn" class="btn btn-primary btn-sm">
                                            <span class="btn-text">@lang('Load More')</span>
                                            <span class="spinner-border spinner-border-sm d-none" role="status"
                                                  aria-hidden="true"></span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($recentAffiliateClicks->count() > 0)
                <div class="recent-activity mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="section-title mb-0">@lang('Recent Activity')</h3>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <ul class="activity-feed list-unstyled scrollable-activity" id="recentActivityList">
                                @foreach($recentAffiliateClicks ?? [] as $index => $click)
                                    <li class="activity-item mb-3 {{ $index >= 5 ? 'd-none' : '' }}">
                                        <div class="d-flex">
                                            <div class="activity-icon me-3">
                                                <img
                                                    src="{{ getFile($click->property?->photos?->images['thumb']['driver'], $click->property?->photos?->images['thumb']['path']) }}"
                                                    alt="{{ $click->property?->title ?? '' }}"
                                                    class="product-thumb me-2">
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between flex-wrap">
                                                    <strong>{{ Str::limit($click->property?->title ?? '', 80) }}</strong>
                                                    <small
                                                        class="text-muted">{{ \Carbon\Carbon::parse($click->created_at)->diffForHumans() }}</small>
                                                </div>
                                                <p class="mb-0 text-muted small">
                                                    @lang('Click from ') {{ $click->referer ?? 'Unknown Source' }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            @if(($recentAffiliateClicks->count() ?? 0) > 5)
                                <div class="text-center mt-3">
                                    <button id="loadMoreActivityBtn" class="btn btn-primary btn-sm">
                                        <span class="btn-text">@lang('Load More')</span>
                                        <span class="spinner-border spinner-border-sm d-none" role="status"
                                              aria-hidden="true"></span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

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
@endsection

@include(template().'affiliate.partials.dash_style')
@include(template().'affiliate.partials.dash_script')

