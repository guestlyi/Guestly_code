<div class="card mb-3 mb-lg-5 bookingChartArea">
    <!-- Header -->
    <div class="card-header card-header-content-sm-between">
        <h4 class="card-header-title mb-2 mb-sm-0"><i class="fas fa-calendar-check me-2"></i>@lang('Bookings')</h4>
        <div class="d-flex filterOption gap-1">
            <select class="js-select form-select booking-select " name="host" id="host" autocomplete="off"
                    data-hs-tom-select-options='{
                    "dropdownWidth": "100%",
                    "dropdownLeft": true
                }'>
                <option value="all" {{ old('host') === null ? 'selected' : '' }}>@lang('ALL HOST')</option>
                @foreach($hosts as $host)
                    <option value="{{ $host->id }}"
                            {{ old('plan') == $host->id ? 'selected' : '' }}
                            data-item="{{ json_encode($host) }}">
                        {{ $host->firstname.' '.$host->lastname }}
                    </option>
                @endforeach
            </select>
            <button id="js-daterangepicker-predefined" class="btn btn-white btn-sm dropdown-toggle bookingToggleFilter">
                <i class="bi-calendar-week"></i>
                <span class="js-daterangepicker-predefined-preview ms-1"></span>
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="row col-lg-divider">
            <div class="col-lg-9 mb-5 mb-lg-0">
                <div class="chartjs-custom mb-4">
                    <canvas id="ecommerce-sales" height="350" class="sales-chart-height"></canvas>
                </div>
                <div class="row justify-content-center">
                    <div class="col-auto">
                        <span class="legend-indicator bg-primary"></span> @lang('Bookings')
                    </div>
                    <div class="col-auto">
                        <span class="legend-indicator"></span> @lang('Amount')
                    </div>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="row">
                    <div class="col-sm-6 col-lg-12">
                        <!-- Stats -->
                        <div class="d-flex justify-content-center flex-column aside-sales-chart-height">
                            <h6 class="card-subtitle">@lang('Revenue')</h6>
                            <span class="d-block display-4 text-dark mb-1 me-3 revenueData">0</span>
                        </div>
                        <!-- End Stats -->

                        <hr class="d-none d-lg-block my-0">
                    </div>
                    <!-- End Col -->

                    <div class="col-sm-6 col-lg-12">
                        <!-- Stats -->
                        <div class="d-flex justify-content-center flex-column aside-sales-chart-height">
                            <h6 class="card-subtitle">@lang('Bookings')</h6>
                            <span class="d-block display-4 text-dark mb-1 me-3 orderData">0</span>
                        </div>
                        <!-- End Stats -->
                    </div>
                    <!-- End Col -->
                </div>
                <!-- End Row -->
            </div>
        </div>
        <!-- End Row -->
    </div>
</div>

@push('css-lib')
    <style>
        #ecommerce-sales{
            height: 350px !important;
        }
    </style>
@endpush

@push('script')
    <script>
        let salesChart;

        $(document).on('ready', function () {
            HSCore.components.HSTomSelect.init('.js-select', {
                placeholder: 'Select one'
            });

            let start = moment().subtract(29, 'days');
            let end = moment();

            function cb(start, end) {
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
                }
            }, cb);

            cb(start, end);

            function fetchDataAndUpdateChart() {
                const dateRange = $('#js-daterangepicker-predefined').data('daterangepicker');
                const startDate = dateRange.startDate.format('YYYY-MM-DD');
                const endDate = dateRange.endDate.format('YYYY-MM-DD');
                const selectedHost = $('#host').val() ?? 'all';
                const currency_Symbol = '{{ basicControl()->currency_symbol }}';

                $.ajax({
                    url: '{{ route('admin.bookingData') }}',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate,
                        host: selectedHost
                    },
                    success: function (data) {
                        if (salesChart) {
                            salesChart.destroy();
                        }

                        const ctx = document.getElementById('ecommerce-sales').getContext('2d');
                        salesChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.labels,
                                datasets: [
                                    {
                                        label: 'Total Sales',
                                        data: data.sales ?? data.Price,
                                        backgroundColor: '#6e71ff',
                                        borderColor: '#6e71ff',
                                        borderWidth: 1,
                                        borderRadius: 4,
                                        barPercentage: 0.6,
                                        categoryPercentage: 0.6,
                                    }
                                ]
                            },
                            options: {
                                maintainAspectRatio: false,
                                animation: {
                                    duration: 1500,
                                    easing: 'easeOutBounce',
                                },
                                scales: {
                                    y: {
                                        grid: {
                                            color: "#e7eaf3",
                                            drawBorder: false,
                                            zeroLineColor: "#e7eaf3"
                                        },
                                        ticks: {
                                            beginAtZero: true,
                                            stepSize: 100,
                                            color: "#97a4af",
                                            font: {
                                                size: 12,
                                                family: "Open Sans, sans-serif"
                                            },
                                            padding: 10
                                        }
                                    },
                                    x: {
                                        grid: {
                                            display: false,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            color: "#97a4af",
                                            font: {
                                                size: 12,
                                                family: "Open Sans, sans-serif"
                                            },
                                            padding: 5
                                        },
                                        categoryPercentage: 0.5,
                                        maxBarThickness: "10"
                                    }
                                },
                                cornerRadius: 2,
                                plugins: {
                                    tooltip: {
                                        hasIndicator: true,
                                        mode: "index",
                                        intersect: false
                                    }
                                },
                                hover: {
                                    mode: "nearest",
                                    intersect: true
                                }
                            }

                        });

                        $('.revenueData').text(`${currency_Symbol}` + data.TotalPurchased.toFixed(2));
                        $('.orderData').text(data.TotalBookings);
                    },
                    error: function (error) {
                        console.error('Error fetching chart data:', error);
                    }
                });
            }

            $('#host').on('change', fetchDataAndUpdateChart);
            $('#js-daterangepicker-predefined').on('apply.daterangepicker', fetchDataAndUpdateChart);

            fetchDataAndUpdateChart();
        });
    </script>
@endpush
