@extends(template().'layouts.affiliate')
@section('title',trans('Affiliate Item List'))
@section('content')
<section class="listing">
    <div class="container">
        <div class="personal-info-title listing-top">
            <div class="text-area">
                <ul class="breadcrumb">
                    <li><a href="{{ route('affiliate.dashboard') }}"><i class="fas fa-home"></i> @lang('Dashboard')</a></li>
                    <li><i class="fas fa-chevron-right"></i></li>
                    <li class="active">@lang('Analytics')</li>
                </ul>
                <h2 class="page-title">@lang('Analytics Overview')</h2>
                <p class="page-subtitle">@lang('Track and analyze your traffic sources and user behavior')</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-primary">
                        <i class="fas fa-globe"></i>
                    </div>
                    <div class="stat-content">
                        <h5>@lang('Total Visits')</h5>
                        <h3>{{ $totalVisitsThisWeek }}</h3>
                        <p class="{{ $percentageChange > 0 ? 'text-success' : 'text-danger' }}"><i class="{{ $percentageChange > 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down' }}"></i> {{ $percentageChange }}% @lang('from last week')</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-success">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content">
                        <h5>@lang('Unique Visitors')</h5>
                        <h3>{{ $uniqueVisitors }}</h3>
                        <p class="{{ $uniqueVisitorsChange > 0 ? 'text-success' : 'text-danger' }}"><i class="{{ $uniqueVisitorsChange > 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down' }}"></i> {{ $uniqueVisitorsChange }}% @lang('from last week')</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h5>@lang('Avg. Session')</h5>
                        <h3>{{  $avgSessionTime }}</h3>
                        <p class="{{ $percentageChangeSession > 0 ? 'text-success' : 'text-danger' }}"><i class="{{ $percentageChangeSession > 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down' }}"></i> {{ $percentageChangeSession }}% @lang('from last week')</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon bg-info">
                        <i class="fas fa-arrow-pointer"></i>
                    </div>
                    <div class="stat-content">
                        <h5>@lang('Bounce Rate')</h5>
                        <h3>{{ $avgBounce }}%</h3>
                        <p class="{{ $percentageBounceChange > 0 ? 'text-success' : 'text-danger' }}"><i class="{{ $percentageBounceChange > 0 ? 'fas fa-arrow-up' : 'fas fa-arrow-down' }}"></i> {{ $percentageBounceChange }}% @lang('from last week')</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Charts -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="chart-card">
                    <div class="chart-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-link me-2"></i>@lang('Referer Statistics')</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                @lang('Last 7 days')
                            </button>
                            <ul class="dropdown-menu" id="refererRange">
                                <li><a class="dropdown-item" href="#" data-range="today">@lang('Today')</a></li>
                                <li><a class="dropdown-item" href="#" data-range="last7">@lang('Last 7 days')</a></li>
                                <li><a class="dropdown-item" href="#" data-range="last30">@lang('Last 30 days')</a></li>
                                <li><a class="dropdown-item" href="#" data-range="thisYear">@lang('This year')</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="refererChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="chart-card">
                    <div class="chart-header d-flex justify-content-between align-items-center">
                        <h5><i class="fas fa-map-marker-alt me-2"></i>@lang('Country Statistics')</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                @lang('Top 10')
                            </button>
                            <ul class="dropdown-menu" id="countryRange">
                                <li><a class="dropdown-item" href="#">@lang('Top 5')</a></li>
                                <li><a class="dropdown-item" href="#">@lang('Top 10')</a></li>
                                <li><a class="dropdown-item" href="#">@lang('All Countries')</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="chart-body">
                        <canvas id="countryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-12">
                <div class="chart-card">
                    <div class="chart-header">
                        <h5><i class="fas fa-table me-2"></i>@lang('Detailed Analytics Data')</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover analytics-table">
                            <thead>
                            <tr>
                                <th>@lang('Property')</th>
                                <th>@lang('Referer')</th>
                                <th>@lang('Device')</th>
                                <th>@lang('OS')</th>
                                <th>@lang('Browser')</th>
                                <th>@lang('Date')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($AffiliateClick ?? [] as $item)
                                <tr>
                                    <td>
                                        @if(!empty($item['propertyData']))
                                            <div class="d-flex align-items-center table-data">
                                                @if($item['propertyData']['thumb'])
                                                    <img src="{{ $item['propertyData']['thumb'] }}" alt="{{ $item['propertyData']['title'] }}" style="width: 50px; height: 35px; object-fit: cover; margin-right: 10px;">
                                                @endif
                                                <div>
                                                    <a href="{{ route('service.details', $item['propertyData']['slug']) }}" target="_blank">
                                                        {{ $item['propertyData']['title'] }}
                                                    </a>
                                                    <div class="text-muted" style="font-size: 0.85em;">
                                                        @lang('Price'): {{ currencyPosition($item['propertyData']['price']) }}
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <em>@lang('No property')</em>
                                        @endif
                                    </td>
                                    <td>{{ $item['referer'] }}</td>
                                    <td>{{ $item['device'] }}</td>
                                    <td>{{ $item['os'] }}</td>
                                    <td>{{ $item['browser'] }}</td>
                                    <td>{{ $item['date'] }}</td>
                                </tr>
                            @empty
                                @include('empty')
                            @endforelse

                            </tbody>
                        </table>
                        {{ $AffiliateClick->appends(request()->query())->links(template().'partials.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@push('style')
    <style>
        .personal-info-title ul{
            justify-content: start;
        }
        .listing {
            padding: 30px 0;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
            margin-bottom: 10px;
        }

        .breadcrumb li {
            display: inline;
            font-size: 14px;
        }

        .breadcrumb li a {
            color: #6c757d;
            text-decoration: none;
        }

        .breadcrumb li.active {
            color: #495057;
        }

        .page-title {
            font-weight: 600;
            color: #343a40;
            margin-bottom: 5px;
        }

        .page-subtitle {
            color: #6c757d;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            height: 100%;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 20px;
        }

        .stat-icon.bg-primary { background: #4361ee; }
        .stat-icon.bg-success { background: #38b000; }
        .stat-icon.bg-warning { background: #ff9e00; }
        .stat-icon.bg-info { background: #3a86ff; }

        .stat-content h5 {
            font-size: 14px;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .stat-content h3 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .stat-content p {
            font-size: 12px;
            margin-bottom: 0;
        }

        .chart-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 20px;
            height: 100%;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .chart-header h5 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 0;
            color: #495057;
        }

        .chart-legend {
            display: flex;
            justify-content: center;
            margin-top: 15px;
            flex-wrap: wrap;
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin: 0 10px;
            font-size: 12px;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 3px;
            margin-right: 5px;
        }

        .legend-color.mobile { background: #4361ee; }
        .legend-color.desktop { background: #38b000; }
        .legend-color.tablet { background: #ff9e00; }

        .analytics-table {
            font-size: 14px;
        }

        .analytics-table th {
            background: #f8f9fa;
            color: #495057;
            font-weight: 600;
            border-top: none;
        }

        .flag-icon {
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            .stat-card {
                margin-bottom: 15px;
            }
        }
        .listing-top{
            margin-bottom: 0 !important;
        }
        .error{
            z-index: 1 !important;
        }
        .table-responsive{
            display: flex;
            flex-direction: column-reverse;
            margin-top: 20px;
        }
        .error-container .error-image {
            height: 275px;
            border-radius: 10px;
        }
        .chart-body {
            position: relative;
            width: 100%;
            height: 350px;
        }

        @media (max-width: 992px) {
            .chart-body {
                height: 300px;
            }
        }

        @media (max-width: 576px) {
            .chart-body {
                height: 250px;
            }
        }
    </style>
@endpush

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
    <script>
        let refererChart, countryChart;

        function showEmptyChart(ctxId, horizontal = false) {
            const ctx = document.getElementById(ctxId).getContext('2d');

            if (ctxId === "refererChart" && refererChart) refererChart.destroy();
            if (ctxId === "countryChart" && countryChart) countryChart.destroy();

            const config = {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Visits',
                        data: [],
                        backgroundColor: [],
                        borderRadius: 6,
                        barThickness: 18,
                        maxBarThickness: 20
                    }]
                },
                options: {
                    indexAxis: horizontal ? 'y' : 'x',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false },
                        datalabels: { display: false }
                    },
                    scales: {
                        x: { grid: { display: true }, ticks: { precision: 0 } },
                        y: { grid: { display: true }, ticks: { precision: 0 } }
                    }
                },
                plugins: [ChartDataLabels]
            };

            if (ctxId === "refererChart") refererChart = new Chart(ctx, config);
            else if (ctxId === "countryChart") countryChart = new Chart(ctx, config);
        }

        function loadRefererChart(range) {
            fetch("{{ route('affiliate.fetchReferData') }}?range=" + range)
                .then(res => res.json())
                .then(data => {
                    if (!data || !data.data || data.data.length === 0) {
                        showEmptyChart("refererChart");
                        return;
                    }

                    if (refererChart) refererChart.destroy();

                    refererChart = new Chart(document.getElementById('refererChart'), {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Visits',
                                data: data.data,
                                backgroundColor: data.labels.map((_, i) => `hsl(${i * 40}, 70%, 55%)`),
                                borderRadius: 6,
                                barThickness: 18,
                                maxBarThickness: 20
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: (context) => `${context.raw} visits`
                                    }
                                },
                                datalabels: {
                                    anchor: 'end',
                                    align: 'top',
                                    formatter: (value) => value,
                                    font: { size: 12, weight: 'bold' }
                                }
                            },
                            scales: {
                                x: { grid: { display: false }, ticks: { font: { size: 12 } } },
                                y: { grid: { display: false }, ticks: { precision: 0 } }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });
                })
                .catch(() => showEmptyChart("refererChart"));
        }

        function loadCountryChart(limit = null) {
            let url = "{{ route('affiliate.fetchCountryData') }}";
            if (limit) url += "?limit=" + limit;

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    if (!data || !data.data || data.data.length === 0) {
                        showEmptyChart("countryChart", true);
                        return;
                    }

                    if (countryChart) countryChart.destroy();

                    const total = data.data.reduce((a, b) => a + b, 0);

                    countryChart = new Chart(document.getElementById('countryChart'), {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Visits',
                                data: data.data,
                                backgroundColor: data.labels.map((_, i) => `hsl(${i * 40}, 70%, 55%)`),
                                borderRadius: 6,
                                barThickness: 18,
                                maxBarThickness: 20
                            }]
                        },
                        options: {
                            indexAxis: 'y',
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    callbacks: {
                                        label: (context) => {
                                            const val = context.raw;
                                            const percent = ((val / total) * 100).toFixed(1);
                                            return `${val} visits (${percent}%)`;
                                        }
                                    }
                                },
                                datalabels: {
                                    anchor: 'end',
                                    align: 'right',
                                    formatter: (value) => value,
                                    font: { size: 12, weight: 'bold' }
                                }
                            },
                            scales: {
                                x: { grid: { display: false }, ticks: { precision: 0 } },
                                y: { grid: { display: false }, ticks: { font: { size: 12 } } }
                            }
                        },
                        plugins: [ChartDataLabels]
                    });
                })
                .catch(() => showEmptyChart("countryChart", true));
        }

        document.addEventListener('DOMContentLoaded', function () {
            loadRefererChart('last7');   // default
            loadCountryChart(10);        // default Top 10

            document.querySelectorAll('#refererRange .dropdown-item').forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    const range = this.getAttribute('data-range');
                    loadRefererChart(range);

                    const btn = this.closest('.dropdown').querySelector('button');
                    btn.textContent = this.textContent;
                });
            });

            document.querySelectorAll('#countryRange .dropdown-item').forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    const text = this.textContent.trim().toLowerCase();
                    let limit = null;
                    if (text.includes('5')) limit = 5;
                    else if (text.includes('10')) limit = 10;
                    loadCountryChart(limit);

                    const btn = this.closest('.dropdown').querySelector('button');
                    btn.textContent = this.textContent;
                });
            });
        });
    </script>
@endpush
