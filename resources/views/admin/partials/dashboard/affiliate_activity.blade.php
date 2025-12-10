<div class="card mb-3 mb-lg-5 affiliate-activity">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="card-header-title">
            <i class="fas fa-chart-line me-2"></i> @lang('Affiliate Activity')
        </h4>
        <button id="affiliate-daterangepicker" class="btn btn-white btn-sm">
            <i class="bi-calendar-week"></i>
            <span class="js-daterangepicker-predefined-preview ms-1"></span>
        </button>
    </div>
    <div class="card-body">
        <canvas id="affiliateActivityChart" height="350"></canvas>
    </div>
</div>

@push('script')
    <script>
        const currencySymbol = "{{ basicControl()->currency_symbol ?? '$' }}";

        $(document).ready(function () {
            let start = moment().subtract(6, 'days');
            let end = moment();
            let affiliateActivityChart;
            const ctx = document.getElementById("affiliateActivityChart").getContext("2d");

            function isDarkMode() {
                if (document.documentElement.classList.contains('dark-mode') ||
                    document.documentElement.classList.contains('dark') ||
                    document.body.classList.contains('dark-mode')) {
                    return true;
                }
                if (document.documentElement.getAttribute('data-theme') === 'dark' ||
                    document.documentElement.getAttribute('data-bs-theme') === 'dark') {
                    return true;
                }

                const bgColor = getComputedStyle(document.body).backgroundColor;
                const rgb = bgColor.match(/\d+/g);
                if (rgb) {
                    const brightness = (parseInt(rgb[0]) * 299 + parseInt(rgb[1]) * 587 + parseInt(rgb[2]) * 114) / 1000;
                    return brightness < 128;
                }

                return false;
            }

            function getChartColors() {
                if (isDarkMode()) {
                    return {
                        gridColor: 'rgba(255, 255, 255, 0.1)',
                        textColor: '#ffffff',
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#6e71ff',
                        tooltipBackground: '#1a1a1a',
                        tooltipBorder: '#333333',
                        areaFill: 'rgba(68, 90, 131, 0.8)'
                    };
                }
                return {
                    gridColor: 'rgba(0, 0, 0, 0.05)',
                    textColor: '#666666',
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#6e71ff',
                    tooltipBackground: '#ffffff',
                    tooltipBorder: '#e0e0e0',
                    areaFill: 'rgba(231, 231, 255, 1)'
                };
            }

            function updateDateRangePreview(start, end) {
                $('#affiliate-daterangepicker .js-daterangepicker-predefined-preview')
                    .html(start.format('MMM D') + ' - ' + end.format('MMM D, YYYY'));
            }

            function loadAffiliateActivity(startDate = null, endDate = null) {
                let url = "{{ route('admin.affiliate.activity.data') }}";
                if (startDate && endDate) {
                    url += `?start_date=${startDate}&end_date=${endDate}`;
                }

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (affiliateActivityChart) {
                            affiliateActivityChart.destroy();
                        }

                        const colors = getChartColors();

                        affiliateActivityChart = new Chart(ctx, {
                            type: "line",
                            data: {
                                labels: data.labels,
                                datasets: [{
                                    label: "Total Clicks",
                                    data: data.topAffiliates.map(a => a.total_click),
                                    borderColor: "#6e71ff",
                                    backgroundColor: colors.areaFill,
                                    tension: 0.4,
                                    fill: true,
                                    pointBackgroundColor: colors.pointBackgroundColor,
                                    pointBorderColor: colors.pointBorderColor,
                                    pointBorderWidth: 2,
                                    pointHoverRadius: 6,
                                    pointHoverBackgroundColor: '#6e71ff'
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                interaction: { mode: "nearest", intersect: true },
                                plugins: {
                                    legend: {
                                        display: true,
                                        position: "top",
                                        labels: {
                                            color: colors.textColor,
                                            usePointStyle: true,
                                            pointStyle: 'rect',
                                            padding: 15
                                        }
                                    },
                                    tooltip: {
                                        backgroundColor: colors.tooltipBackground,
                                        titleColor: colors.textColor,
                                        bodyColor: colors.textColor,
                                        borderColor: colors.tooltipBorder,
                                        borderWidth: 1,
                                        padding: 12,
                                        callbacks: {
                                            title: function (context) {
                                                return context[0].label;
                                            },
                                            label: function (context) {
                                                const affiliate = data.topAffiliates[context.dataIndex];
                                                if (!affiliate) return "No data";
                                                return [
                                                    `Total Clicks: ${affiliate.total_click}`,
                                                    `Pending Earnings: ${currencySymbol}${affiliate.pending_earnings}`,
                                                    `Completed Earnings: ${currencySymbol}${affiliate.completed_earnings}`,
                                                    `Total Earnings: ${currencySymbol}${affiliate.total_earnings}`,
                                                    `Country: ${affiliate.country}`,
                                                    `Phone: ${affiliate.phone}`,
                                                    `Balance: ${currencySymbol}${affiliate.balance}`,
                                                ];
                                            }
                                        }
                                    }
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        title: {
                                            display: true,
                                            text: "Total Clicks",
                                            color: colors.textColor
                                        },
                                        grid: {
                                            color: colors.gridColor
                                        },
                                        ticks: {
                                            color: colors.textColor,
                                            padding: 8
                                        }
                                    },
                                    x: {
                                        grid: {
                                            color: colors.gridColor,
                                            drawBorder: false
                                        },
                                        ticks: {
                                            color: colors.textColor,
                                            padding: 8
                                        }
                                    }
                                }
                            }
                        });
                    })
                    .catch(error => {
                        console.error('Error loading affiliate activity:', error);
                    });
            }

            $('#affiliate-daterangepicker').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [
                        moment().subtract(1, 'month').startOf('month'),
                        moment().subtract(1, 'month').endOf('month')
                    ]
                }
            }, function (startDate, endDate) {
                start = startDate;
                end = endDate;
                updateDateRangePreview(start, end);
                loadAffiliateActivity(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            });

            updateDateRangePreview(start, end);
            loadAffiliateActivity(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));

            function reloadChartOnThemeChange() {
                loadAffiliateActivity(start.format('YYYY-MM-DD'), end.format('YYYY-MM-DD'));
            }

            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.attributeName === 'class') {
                        setTimeout(reloadChartOnThemeChange, 100);
                    }
                });
            });

            observer.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>
@endpush
