<div class="row">
    <div class="col-md-6">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-header-title">@lang("Payment Summary")</h4>
                <button id="js-daterangepicker-deposit" class="btn btn-white btn-sm dropdown-toggle">
                    <i class="bi-calendar-week"></i>
                    <span class="js-daterangepicker-preview ms-1"></span>
                </button>
            </div>
            <div class="card-body" data-block="depositBlock">
                <div class="chartjs-custom" style="height: 20rem;">
                    <canvas id="depositChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-header-title">@lang("Withdraw Summary")</h4>
                <button id="js-daterangepicker-payout" class="btn btn-white btn-sm dropdown-toggle">
                    <i class="bi-calendar-week"></i>
                    <span class="js-daterangepicker-preview ms-1"></span>
                </button>
            </div>
            <div class="card-body" data-block="payoutBlock">
                <div class="chartjs-custom" style="height: 20rem;">
                    <canvas id="payoutChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>

        function createVerticalGradient(context, rgb) {
            const gradient = context.chart.ctx.createLinearGradient(0, 0, 0, context.chart.height);
            gradient.addColorStop(0, `rgba(${rgb},0.5)`);
            gradient.addColorStop(1, `rgba(${rgb},0)`);
            return gradient;
        }

        let depositChart;
        function createDepositChart(data) {
            const ctx = document.getElementById('depositChart').getContext('2d');

            depositChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.dates,
                    datasets: [{
                        label: "Total Payment",
                        data: data.totalDeposit,
                        borderColor: "#6371f8",
                        borderWidth: 2,
                        pointRadius: 3,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: function(context) {
                            return createVerticalGradient(context, "55, 125, 255");
                        }
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            ticks: {
                                beginAtZero: true,
                                callback: value => currencySymbol + value.toFixed(2)
                            },
                            grid: { color: "#e7eaf3" }
                        },
                        x: { grid: { display: false } }
                    },
                    plugins: {
                        tooltip: {
                            mode: "index",
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    let value = context.raw;
                                    return `Total: ${currencySymbol}${value.toFixed(2)}`;
                                }
                            }
                        }
                    }
                }
            });
        }

        async function updateDepositChart(startDate, endDate) {
            try {
                const response = await axios.get("{{ route('admin.get.deposit.chart') }}", {
                    params: {
                        start: startDate.format('YYYY-MM-DD'),
                        end: endDate.format('YYYY-MM-DD')
                    }
                });
                if (depositChart) depositChart.destroy();
                createDepositChart(response.data);
            } catch (error) {
                console.error('Error fetching deposit chart data:', error);
            }
        }

        let payoutChart;

        function createPayoutChart(data) {
            const ctx = document.getElementById('payoutChart').getContext('2d');

            payoutChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.dates,
                    datasets: [{
                        label: "Total Payout",
                        data: data.totalPayout,
                        borderColor: "#e15454",
                        borderWidth: 2,
                        pointRadius: 3,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: function(context) {
                            return createVerticalGradient(context, "218,115,115");
                        }
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            ticks: {
                                beginAtZero: true,
                                callback: value => currencySymbol + value.toFixed(2)
                            },
                            grid: { color: "#e7eaf3" }
                        },
                        x: { grid: { display: false } }
                    },
                    plugins: {
                        tooltip: {
                            mode: "index",
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    let value = Number(context.raw);
                                    if (isNaN(value)) value = 0;
                                    return `Total: ${currencySymbol}${value.toFixed(2)}`;
                                }
                            }
                        }
                    }
                }
            });
        }

        async function updatePayoutChart(startDate, endDate) {
            try {
                const response = await axios.get("{{ route('admin.get.payout.chart') }}", {
                    params: {
                        start: startDate.format('YYYY-MM-DD'),
                        end: endDate.format('YYYY-MM-DD')
                    }
                });
                if (payoutChart) payoutChart.destroy();
                createPayoutChart(response.data);
            } catch (error) {
                console.error('Error fetching payout chart data:', error);
            }
        }

        function initDateRangePicker(selector, updateFunction) {
            let start = moment().subtract(6, 'days');
            let end = moment();

            function cb(start, end) {
                $(selector + ' .js-daterangepicker-preview').html(
                    start.format('MMM D') + ' - ' + end.format('MMM D, YYYY')
                );
                updateFunction(start, end);
            }

            $(selector).daterangepicker({
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
        }

        initDateRangePicker('#js-daterangepicker-deposit', updateDepositChart);
        initDateRangePicker('#js-daterangepicker-payout', updatePayoutChart);

    </script>
@endpush
