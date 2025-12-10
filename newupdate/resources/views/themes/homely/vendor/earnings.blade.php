@extends(template().'layouts.user')
@section('title',trans('Earnings'))
@section('content')
    <section class="earning">
        <div class="container">
            <div class="listing-top">
                <h3>@lang('Earnings')</h3>

            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="earning-left">
                        <div class="chart-box">
                            <canvas id="earning"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1">
                    <div class="personal-info-right">
                        <div class="personal-info-sidebar">
                            <div class="personal-info-sidebar-content">
                                <h5>@lang('This Year Summery')</h5>
                                <p class="summary-date">Jan 1 – Jul 21, 2025</p>
                                <div class="earning-list">
                                    <ul>
                                        <li>@lang('Gross earnings') <span class="gross-earnings">$0.00</span></li>
                                        <li>@lang('Discounts') <span class="discounts">$0.00</span></li>
                                        <li>@lang('Vibestay service fee') <span class="service-fee">$0.00</span></li>
                                        <li>@lang('Host received') <span class="received">$0.00</span></li>
                                        <li class="earning-list-border"></li>
                                        <li>@lang('Total') <span class="total-earned">$0.00</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 earning-data">
                    <div class="d-flex flex-wrap align-items-center justify-content-between">
                        <h3 class="mb-3">@lang('Earnings History')</h3>
                        <form id="earningsForm" action="{{ route('user.earnings') }}" method="GET">
                            <div class="d-flex align-items-center justify-content-end gap-3">
                                <select class="form-select" name="month" id="month">
                                    <option value="All Months">{{ __('All Months') }}</option>
                                    @foreach($months as $key => $month)
                                        <option value="{{ $key }}" {{ $key == request('month') ? 'selected' : '' }}>{{ $month }}</option>
                                    @endforeach
                                </select>

                                <select class="form-select" name="year" id="year">
                                    <option value="All Years">{{ __('All Years') }}</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ $year == request('year') ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="list-view-wrapper">
                        <div class="table-responsive d-flex flex-column-reverse">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col">@lang('Trx ID')</th>
                                        <th scope="col">@lang('Amount')</th>
                                        <th scope="col">@lang('Charge')</th>
                                        <th scope="col">@lang('Remark')</th>
                                        <th scope="col">@lang('Created At')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($earnings as $item)
                                        <tr>
                                            <td data-label="trx_id">
                                                <div class="listing-image-container">
                                                    <h6>{{ $item->trx_id }}</h6>
                                                </div>
                                            </td>
                                            <td data-label="Amount">
                                                @php
                                                    if ($item->host_id) {
                                                        $currentTrxType = ($item->host_id == auth()->id()) ? '+' : '-';
                                                        $currentStatusClass = ($item->host_id == auth()->id()) ? 'text-success' : 'text-danger';
                                                    } else {
                                                        $currentTrxType = $item->trx_type;
                                                        $currentStatusClass = ($item->trx_type == '+') ? 'text-success' : 'text-danger';
                                                    }
                                                @endphp
                                                <span class="{{ $currentStatusClass }}">{{ $currentTrxType .' '. currencyPosition($item->amount) }}</span>
                                            </td>
                                            <td data-label="Charge" class="text-danger">{{ currencyPosition($item->charge) }}</td>
                                            <td data-label="Remark">
                                                <span>{{ $item->remarks }}</span>
                                            </td>
                                            <td data-label="created_at">
                                                <span>{{ dateTime($item->created_at) }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        @include('empty')
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $earnings->appends(request()->query())->links(template().'partials.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    <style>
        .earning-data{
            margin-top: 80px;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset(template(true) . 'js/apexcharts.min.js') }}"></script>
    <script>
        const currency_symbols = '{{ basicControl()->currency_symbol }}';
        $(document).ready(function () {
            fetchEarningsData();

            function fetchEarningsData() {
                $.ajax({
                    url: '{{ route('user.earnings.data') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        renderChart(response.labels, response.data);
                    },
                    error: function () {
                        Notiflix.Notify.failure('Failed to load earnings data.');
                    }
                });
            }

            function renderChart(labels, data) {
                var canvas = $("#earning");
                if (canvas.length) {
                    var ctx = canvas[0].getContext("2d");

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Earn per month',
                                data: data,
                                borderWidth: 1,
                                backgroundColor: [
                                    'rgba(255,99,132, 1)',
                                    'rgba(54,162,235, 1)',
                                    'rgba(255,206,86, 1)',
                                    'rgba(75,192,192, 1)',
                                    'rgba(253,102,64, 1)',
                                    'rgba(255,159,132, 1)'
                                ],
                            }]
                        },
                        options: {
                            responsive: true,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function (value) {
                                            return currency_symbols + value;
                                        }
                                    }
                                }
                            },
                            plugins: {
                                tooltip: {
                                    callbacks: {
                                        label: function (context) {
                                            let label = context.dataset.label || '';
                                            if (label) label += ': ';
                                            label += currency_symbols + context.parsed.y;
                                            return label;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }

            fetchEarningsSummary();

            function fetchEarningsSummary() {
                $.ajax({
                    url: '{{ route('user.earnings.summary') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function (res) {
                        $('.summary-date').text(res.date_range);
                        $('.gross-earnings').text(currency_symbols + res.gross_earnings);
                        $('.discounts').text(currency_symbols + res.discounts);
                        $('.service-fee').text(currency_symbols + res.service_fee);
                        $('.received').text(currency_symbols + res.received);
                        $('.total-earned').text(currency_symbols + res.total);
                    }
                });
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
            const earningsForm = document.getElementById('earningsForm');
            const monthSelect = document.getElementById('month');
            const yearSelect = document.getElementById('year');
            let timeout;

            function submitForm() {
                clearTimeout(timeout);
                timeout = setTimeout(() => earningsForm.submit(), 700);
            }

            monthSelect.addEventListener('change', submitForm);
            yearSelect.addEventListener('change', submitForm);
        });
    </script>
@endpush
