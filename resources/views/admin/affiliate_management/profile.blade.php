@extends('admin.layouts.app')
@section('page_title', __('Affiliate Profile'))
@section('content')
    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10">
                @include('admin.affiliate_management.partials.header_affiliate_profile')

                <div class="row">
                    <div class="col-lg-4">
                        <div class="card mb-3 mb-lg-5">
                            <div class="card-header card-header-content-between">
                                <h4 class="card-header-title">@lang('Profile')</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled list-py-2 text-dark mb-0">
                                    <li class="pb-0"><span class="card-subtitle">@lang('About')</span></li>
                                    <li><i class="bi-person dropdown-item-icon"></i>{{ $affiliate->firstname.' '.$affiliate->lastname }}</li>
                                    @php
                                        $locationParts = array_filter([
                                            $affiliate->city,
                                            $affiliate->state,
                                            $affiliate->country
                                        ]);
                                    @endphp

                                    @if(count($locationParts))
                                        <li>
                                            <i class="bi-geo-alt dropdown-item-icon"></i>
                                            {{ implode(', ', $locationParts) }}
                                        </li>
                                    @endif

                                    <li class="pt-4 pb-0"><span class="card-subtitle">@lang('Contacts')</span></li>
                                    <li><i class="bi-at dropdown-item-icon"></i>{{ $affiliate->email }}</li>
                                    <li><i class="bi-phone dropdown-item-icon"></i> {{ $affiliate->phone_code.$affiliate->phone }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="dashboard-summary-cards">
                            <div class="summary-card">
                                <div class="card-icon">
                                    <img src="{{ asset('assets/admin/img/oc-megaphone.svg') }}" alt="Earnings" class="light-icon" data-hs-theme-appearance="default">
                                    <img src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}" alt="Earnings" class="dark-icon" data-hs-theme-appearance="dark">
                                </div>
                                <div class="card-content">
                                    <p class="card-label">@lang('Today Earning')</p>
                                    <h3 class="card-value">{{ currencyPosition($today_earning) }}</h3>
                                </div>
                            </div>

                            <div class="summary-card">
                                <div class="card-icon">
                                    <img src="{{ asset('assets/admin/img/oc-hi-five.svg') }}" alt="Clicks" class="light-icon" data-hs-theme-appearance="default">
                                    <img src="{{ asset('assets/admin/img/oc-hi-five-light.svg') }}" alt="Clicks" class="dark-icon" data-hs-theme-appearance="dark">
                                </div>
                                <div class="card-content">
                                    <p class="card-label">@lang('Total Clicked')</p>
                                    <h3 class="card-value">{{ $total_click }}</h3>
                                </div>
                            </div>

                            <div class="summary-card">
                                <div class="card-icon">
                                    <img src="{{ asset('assets/admin/img/oc-money-profits.svg') }}" alt="Balance" class="light-icon" data-hs-theme-appearance="default">
                                    <img src="{{ asset('assets/admin/img/oc-money-profits-light.svg') }}" alt="Balance" class="dark-icon" data-hs-theme-appearance="dark">
                                </div>
                                <div class="card-content">
                                    <p class="card-label">@lang('Balance')</p>
                                    <h3 class="card-value">{{ currencyPosition($balance) }}</h3>
                                </div>
                            </div>
                        </div>

                        <div class="card card-centered mb-3 mb-lg-5">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h4 class="card-header-title">@lang('Earnings')</h4>
                                    <div class="d-flex align-items-center">
                                        <div id="js-daterangepicker-predefined" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi-calendar me-1"></i>
                                            <span class="js-daterangepicker-predefined-preview"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="chartjs-custom mb-4">
                                <canvas id="affiliateEarningChart" class="sales-chart-height"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.affiliate_management.partials.modals')
@endsection

@push('style')
    <style>
        .dashboard-summary-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(273px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .summary-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(70, 90, 230, 0.1);
        }

        .card-icon img {
            width: 32px;
            height: 32px;
            object-fit: contain;
        }

        .card-content {
            text-align: center;
        }

        .card-label {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .card-value {
            color: #1e293b;
            font-size: 1.75rem;
            margin: 0;
            font-weight: 700;
        }

        [data-hs-theme-appearance="dark"] .summary-card {
            background: #1e293b;
            border-color: rgba(255, 255, 255, 0.05);
        }

        [data-hs-theme-appearance="dark"] .card-value {
            color: #f8fafc;
        }

        [data-hs-theme-appearance="dark"] .card-label {
            color: #94a3b8;
        }

        [data-hs-theme-appearance="dark"] .card-icon {
            background: rgba(165, 180, 252, 0.1);
        }
    </style>

    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/global/css/daterangepicker.css') }}">
    <style>
        #js-daterangepicker-predefined{
            width: 125%;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/chart.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            let salesChart;
            const currencySymbol = '{{ basicControl()->currency_symbol }}';

            const start = moment().subtract(29, 'days');
            const end = moment();

            function updateDateRangePreview(start, end) {
                $('#js-daterangepicker-predefined .js-daterangepicker-predefined-preview')
                    .html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
            }

            $('#js-daterangepicker-predefined').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                opens: 'left',
                locale: { format: 'MMM D, YYYY' }
            }, updateDateRangePreview);

            updateDateRangePreview(start, end);

            function getChartOptions() {
                return {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        'y-units': {
                            type: 'linear',
                            position: 'left',
                            beginAtZero: true,
                            ticks: { stepSize: 1, color: '#377dff', font: { size: 12 } },
                            grid: { color: '#e7eaf3', drawBorder: false }
                        },
                        'y-amount': {
                            type: 'linear',
                            position: 'right',
                            beginAtZero: true,
                            ticks: {
                                color: '#e7eaf3',
                                font: { size: 12 },
                                callback: function(v){ return currencySymbol + v; }
                            },
                            grid: { drawOnChartArea: false }
                        },
                        x: {
                            ticks: { color: '#97a4af', font: { size: 12 }, padding: 5 },
                            categoryPercentage: 0.5,
                            maxBarThickness: 10,
                            grid: { display: false, drawBorder: false }
                        }
                    },
                    plugins: {
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(ctx){
                                    let l = ctx.dataset.label || '';
                                    if(l) l += ': ';
                                    l += ctx.datasetIndex === 1 ? currencySymbol + ctx.raw.toFixed(2) : ctx.raw;
                                    return l;
                                }
                            }
                        },
                        legend: { position:'top', align:'end', labels:{ usePointStyle:true, padding:20, font:{ size:12 } } }
                    },
                    hover: { mode:'nearest', intersect:true }
                };
            }

            function initializeEmptyChart() {
                const canvas = document.getElementById('affiliateEarningChart');
                canvas.style.width = '750px';
                canvas.style.height = '350px';

                const ctx = canvas.getContext('2d');
                salesChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: [],
                        datasets:[
                            { label:'Transaction', data:[], backgroundColor:'#377dff', borderWidth:1, yAxisID:'y-units' },
                            { label:'Amount', data:[], backgroundColor:'#e7eaf3', borderWidth:1, yAxisID:'y-amount' }
                        ]
                    },
                    options: getChartOptions()
                });
            }

            function fetchDataAndUpdateChart() {
                const dateRange = $('#js-daterangepicker-predefined').data('daterangepicker');
                const startDate = dateRange.startDate.format('YYYY-MM-DD');
                const endDate = dateRange.endDate.format('YYYY-MM-DD');

                $.ajax({
                    url: '{{ route("admin.affiliate.profile.earnings", $affiliate->id) }}',
                    method: 'GET',
                    data: { start_date: startDate, end_date: endDate },
                    success: function(data) {
                        if(salesChart) salesChart.destroy();

                        const canvas = document.getElementById('affiliateEarningChart');
                        canvas.style.width = '750px';
                        canvas.style.height = '350px';
                        const ctx = canvas.getContext('2d');

                        salesChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets:[
                                    { label:'Transaction', data: data.Unit, backgroundColor:'#377dff', borderWidth:1, yAxisID:'y-units' },
                                    { label:'Amount', data: data.Price, backgroundColor:'#e7eaf3', borderWidth:1, yAxisID:'y-amount' }
                                ]
                            },
                            options: getChartOptions()
                        });

                        $('.revenueData').text(currencySymbol + parseFloat(data.TotalPriceInRange).toFixed(2));
                        $('.orderData').text(data.TotalUnitsInRange);
                    },
                    error: function() {
                        console.error('Error fetching chart data');
                        initializeEmptyChart();
                    }
                });
            }

            $('#js-daterangepicker-predefined').on('apply.daterangepicker', fetchDataAndUpdateChart);

            initializeEmptyChart();
            fetchDataAndUpdateChart();
        });
    </script>
@endpush
