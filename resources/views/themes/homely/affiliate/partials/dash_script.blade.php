@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const currencySymbol = "{{ basicControl()->currency_symbol }}";
        let chart;

        function fetchChartData(range = 30) {
            fetch(`{{ route('affiliate.chart') }}?range=${range}`)
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('earningChart').getContext('2d');

                    const chartData = {
                        labels: data.labels,
                        datasets: [{
                            label: 'Earnings',
                            data: data.earning,
                            borderColor: '#2bcbba',
                            backgroundColor: 'rgba(43, 203, 186, 0.2)',
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#2bcbba',
                            pointBorderColor: '#fff',
                            pointHoverRadius: 6,
                            pointRadius: 4,
                        }]
                    };

                    const chartOptions = {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: `Affiliate Earnings - Last ${range === 'year' ? 'Year' : range + ' Days'}`,
                                font: {size: 16, weight: 'bold'},
                                align: 'start',
                                color: '#333'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: function (context) {
                                        return currencySymbol + parseFloat(context.raw).toFixed(2);
                                    },
                                    title: function (context) {
                                        const value = context[0].label;
                                        if (range === 'year' || range > 30) {
                                            return new Date(value).toLocaleDateString('en-US', {
                                                year: 'numeric',
                                                month: 'short',
                                                day: 'numeric'
                                            });
                                        }
                                        return value;
                                    }
                                }
                            },
                            legend: {display: false}
                        },
                        scales: {
                            x: {
                                grid: {display: false},
                                ticks: {maxRotation: 45, minRotation: 0}
                            },
                            y: {
                                grid: {display: false},
                                title: {display: true, text: 'Total Earnings'},
                                ticks: {
                                    callback: function (value) {
                                        return currencySymbol + parseFloat(value).toFixed(2);
                                    }
                                }
                            }
                        }
                    };

                    if (chart) {
                        chart.destroy();
                    }

                    chart = new Chart(ctx, {
                        type: 'line',
                        data: chartData,
                        options: chartOptions
                    });
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            const select = document.querySelector('.time-range-select');
            fetchChartData(select.value);

            select.addEventListener('change', () => {
                fetchChartData(select.value);
            });
        });


        /**
         * Generic Load More handler
         * @param {string} containerSelector - parent container selector
         * @param {string} itemSelector - hidden item selector
         * @param {string} btnSelector - load more button selector
         * @param {number} itemsPerPage - how many items per click
         */
        function initLoadMore(containerSelector, itemSelector, btnSelector, itemsPerPage = 5) {
            let container = document.querySelector(containerSelector);
            if (!container) return;

            let rows = container.querySelectorAll(itemSelector);
            let loadMoreBtn = document.querySelector(btnSelector);
            if (!loadMoreBtn) return;

            let currentCount = itemsPerPage;

            loadMoreBtn.addEventListener("click", function () {
                let btnText = loadMoreBtn.querySelector(".btn-text");
                let spinner = loadMoreBtn.querySelector(".spinner-border");

                btnText.classList.add("d-none");
                spinner.classList.remove("d-none");

                setTimeout(() => {
                    let hiddenRows = Array.from(rows).slice(currentCount, currentCount + itemsPerPage);
                    hiddenRows.forEach(row => row.classList.remove("d-none"));
                    currentCount += itemsPerPage;

                    if (currentCount >= rows.length) {
                        loadMoreBtn.style.display = "none";
                    } else {
                        btnText.classList.remove("d-none");
                        spinner.classList.add("d-none");
                    }
                }, 600);
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            initLoadMore("#topProductsTable", ".product-row", "#loadMoreBtn", 5);
            initLoadMore("#recentActivityList", ".activity-item", "#loadMoreActivityBtn", 5);
        });

        $(document).ready(function() {
            $('#stripeCountry').select2({
                placeholder: "@lang('Select Country')",
                width: '100%',
                dropdownParent: $('#stripeCountry').closest('.modal'),
                minimumResultsForSearch: 5,
            });
        });

    </script>
@endpush
