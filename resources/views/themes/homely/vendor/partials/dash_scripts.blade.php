@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const ctx = document.getElementById('bookingChart').getContext('2d');
        let bookingChart = null;
        const currencySymbol = "{{ basicControl()->currency_symbol }}";

        function fetchChartData(range = 30) {
            fetch(`{{ route('user.booking.chart.fetch') }}?range=${range}&series=earning`)
                .then(res => res.json())
                .then(data => {
                    if (!data || !data.labels || !data.values || data.labels.length === 0) {
                        console.warn("No chart data available:", data);
                        return;
                    }

                    const chartData = {
                        labels: data.labels,
                        datasets: [{
                            label: 'Earnings',
                            data: data.values,
                            borderColor: '#2bcbba',
                            backgroundColor: 'rgba(43,203,186,0.1)',
                            fill: true,
                            tension: 0.3
                        }]
                    };

                    const chartOptions = {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: ctx => currencySymbol + ctx.raw
                                }
                            },
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: {
                                    maxTicksLimit: window.innerWidth < 500 ? 4 : 10
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { display: false }
                            }
                        }
                    };

                    if (bookingChart) {
                        bookingChart.data = chartData;
                        bookingChart.options = chartOptions;
                        bookingChart.update();
                    } else {
                        bookingChart = new Chart(ctx, {
                            type: 'line',
                            data: chartData,
                            options: chartOptions
                        });
                    }
                })
                .catch(err => console.error("Failed to fetch chart data:", err));
        }


        document.addEventListener("DOMContentLoaded", function() {
            fetchChartData(30);

            document.querySelectorAll('.btn-range').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.querySelectorAll('.btn-range').forEach(b => b.classList.remove('active'));
                    this.classList.add('active');

                    fetchChartData(this.dataset.range);
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            function safeParse(str) {
                try {
                    return JSON.parse(str);
                } catch (e) {
                    return {};
                }
            }

            function getStatusBadge(status) {
                switch (parseInt(status)) {
                    case 0: return `<span class="badge bg-warning">Pending</span>`;
                    case 1: return `<span class="badge bg-primary">Confirmed</span>`;
                    case 2: return `<span class="badge bg-danger">Cancelled</span>`;
                    case 3: return `<span class="badge bg-success">Completed</span>`;
                    case 4: return `<span class="badge bg-info">Paid</span>`;
                    case 5: return `<span class="badge bg-secondary">Refunded</span>`;
                    default: return `<span class="badge bg-dark">Unknown</span>`;
                }
            }

            document.querySelectorAll(".details").forEach(button => {
                button.addEventListener("click", function () {
                    const uid = this.dataset.uid;
                    const propertyTitle = this.dataset.property_title;
                    const checkInDate = this.dataset.check_in_date;
                    const checkOutDate = this.dataset.check_out_date;
                    const totalAmount = this.dataset.total_amount;
                    const amountWithoutDiscount = this.dataset.amount_without_discount;
                    const discountAmount = this.dataset.discount_amount;
                    const siteCharge = this.dataset.site_charge;
                    const hostReceived = this.dataset.host_received;
                    const status = this.dataset.status;

                    const information = safeParse(this.dataset.information);
                    const userInfo = safeParse(this.dataset.user_info);
                    const appliedDiscount = safeParse(this.dataset.applied_discount);

                    document.getElementById("propertyTitle").innerHTML = propertyTitle || "-";
                    document.getElementById("bookingId").innerHTML = uid || "-";
                    document.getElementById("checkInDate").innerHTML = checkInDate || "-";
                    document.getElementById("checkOutDate").innerHTML = checkOutDate || "-";

                    const guestsWrapper = document.getElementById("guestsSummary");
                    guestsWrapper.innerHTML = "";
                    if (information.adults) {
                        guestsWrapper.innerHTML += `<span class="badge bg-primary">${information.adults} Adult(s)</span>`;
                    }
                    if (information.children) {
                        guestsWrapper.innerHTML += `<span class="badge bg-info">${information.children} Child(ren)</span>`;
                    }
                    if (information.pets) {
                        guestsWrapper.innerHTML += `<span class="badge bg-secondary">${information.pets} Pet(s)</span>`;
                    }

                    const guestsContainer = document.getElementById("guestCardsContainer");
                    guestsContainer.innerHTML = "";

                    if (userInfo) {
                        if (Array.isArray(userInfo.adult)) {
                            userInfo.adult.forEach((guest, index) => {
                                guestsContainer.innerHTML += `
                                    <div class="guest-card">
                                        <div class="guest-avatar bg-primary">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="guest-details">
                                            <h6>Adult ${index + 1}: ${guest.firstname || ''} ${guest.lastname || ''}</h6>
                                            <div class="guest-info">
                                                ${guest.country ? `<span class="guest-meta"><i class="fas fa-flag"></i> ${guest.country}</span>` : ''}
                                                ${guest.gender ? `<span class="guest-meta"><i class="fas fa-venus-mars"></i> ${guest.gender}</span>` : ''}
                                                ${guest.email ? `<span class="guest-meta"><i class="fas fa-envelope"></i> ${guest.email}</span>` : ''}
                                                ${guest.phone ? `<span class="guest-meta"><i class="fas fa-phone"></i> ${guest.phone_code || ''} ${guest.phone}</span>` : ''}
                                                ${guest.birth_date ? `<span class="guest-meta"><i class="fas fa-birthday-cake"></i> ${guest.birth_date}</span>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        }

                        if (Array.isArray(userInfo.children)) {
                            userInfo.children.forEach((guest, index) => {
                                guestsContainer.innerHTML += `
                                    <div class="guest-card">
                                        <div class="guest-avatar bg-info">
                                            <i class="fas fa-child"></i>
                                        </div>
                                        <div class="guest-details">
                                            <h6>Child ${index + 1}: ${guest.firstname || ''} ${guest.lastname || ''}</h6>
                                            <div class="guest-info">
                                                ${guest.country ? `<span class="guest-meta"><i class="fas fa-flag"></i> ${guest.country}</span>` : ''}
                                                ${guest.gender ? `<span class="guest-meta"><i class="fas fa-venus-mars"></i> ${guest.gender}</span>` : ''}
                                                ${guest.birth_date ? `<span class="guest-meta"><i class="fas fa-birthday-cake"></i> ${guest.birth_date}</span>` : ''}
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                    }

                    document.getElementById("amountWithoutDiscount").innerHTML = amountWithoutDiscount || "-";
                    document.getElementById("discountAmount").innerHTML = discountAmount || "-";
                    document.getElementById("siteCharge").innerHTML = siteCharge || "-";
                    document.getElementById("totalAmount").innerHTML = totalAmount || "-";
                    document.getElementById("hostReceived").innerHTML = hostReceived || "-";
                    document.getElementById("status").innerHTML = getStatusBadge(status);
                });
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            const downloadBtn = document.querySelector(".detailPrintBtn");

            downloadBtn.addEventListener("click", function () {
                const propertyTitle = document.getElementById("propertyTitle").innerText;
                const bookingId = document.getElementById("bookingId").innerText;
                const checkInDate = document.getElementById("checkInDate").innerText;
                const checkOutDate = document.getElementById("checkOutDate").innerText;
                const guestsSummary = Array.from(document.querySelectorAll("#guestsSummary .badge"))
                    .map(b => b.innerText).join(", ");

                const guestCards = document.querySelectorAll("#guestCardsContainer .guest-card");
                let guestHTML = "";
                guestCards.forEach((card, index) => {
                    const name = card.querySelector("h6").innerText;
                    const guestType = name.includes("Adult") ? "Adult" : "Child";
                    const infos = Array.from(card.querySelectorAll(".guest-meta"))
                        .map(span => {
                            const icon = span.querySelector("i")?.outerHTML || "";
                            const text = span.innerText;
                            return `<span style="margin-right:5px;">${icon} ${text}</span>`;
                        })
                        .join("<br>");

                    const rowBg = index % 2 === 0 ? "#f9f9f9" : "#ffffff";

                    guestHTML += `
                        <tr style="background-color: ${rowBg};">
                            <td style="padding:10px; vertical-align:top;">
                                <span style="display:inline-block; padding:4px 8px; background-color: ${guestType === 'Adult' ? '#0d6efd' : '#0dcaf0'}; color:#fff; border-radius:4px; font-size:12px; margin-bottom:4px;">${guestType}</span>
                                <br>${name}
                            </td>
                            <td style="padding:10px; vertical-align:top;">
                                ${infos || '-'}
                            </td>
                        </tr>
                    `;
                });

                const amountWithoutDiscount = document.getElementById("amountWithoutDiscount").innerText;
                const discountAmount = document.getElementById("discountAmount").innerText;
                const siteCharge = document.getElementById("siteCharge").innerText;
                const totalAmount = document.getElementById("totalAmount").innerText;
                const hostReceived = document.getElementById("hostReceived").innerText;
                const status = document.getElementById("status").innerHTML;

                const htmlContent = `
            <div style="font-family: Arial, sans-serif; margin:0; padding:0 20px 20px 20px; width:100%; color:#333;">
                <h2 style="text-align: center; margin: 10px 0 30px 0;">Booking Details</h2>

                <div class="section" style="margin-bottom: 30px;">
                    <h4 style="color: #0d6efd; margin-bottom: 10px;">Booking Info</h4>
                    <table style="width:100%; border-collapse: collapse;">
                        <tr><th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Property</th><td style="border:1px solid #ddd; padding:10px;">${propertyTitle}</td></tr>
                        <tr><th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Booking ID</th><td style="border:1px solid #ddd; padding:10px;">${bookingId}</td></tr>
                        <tr><th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Check-In</th><td style="border:1px solid #ddd; padding:10px;">${checkInDate}</td></tr>
                        <tr><th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Check-Out</th><td style="border:1px solid #ddd; padding:10px;">${checkOutDate}</td></tr>
                        <tr><th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Guests Summary</th><td style="border:1px solid #ddd; padding:10px;">${guestsSummary}</td></tr>
                    </table>
                </div>

                <div class="section" style="margin-bottom: 30px;">
                    <h4 style="color: #0d6efd; margin-bottom: 10px;">Guests</h4>
                    <table style="width:100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Name</th>
                                <th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${guestHTML || '<tr><td colspan="2" style="border:1px solid #ddd; padding:10px;">No guests</td></tr>'}
                        </tbody>
                    </table>
                </div>

                <div class="section" style="margin-bottom: 30px;">
                    <h4 style="color: #0d6efd; margin-bottom: 10px;">Payment</h4>
                    <table style="width:100%; border-collapse: collapse;">
                        <tr><th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Amount without Discount</th><td style="border:1px solid #ddd; padding:10px;">${amountWithoutDiscount}</td></tr>
                        <tr><th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Discount Amount</th><td style="border:1px solid #ddd; padding:10px;">${discountAmount}</td></tr>
                        <tr><th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Site Charge</th><td style="border:1px solid #ddd; padding:10px;">${siteCharge}</td></tr>
                        <tr><th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Total Amount</th><td style="border:1px solid #ddd; padding:10px;">${totalAmount}</td></tr>
                        <tr><th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Host Received</th><td style="border:1px solid #ddd; padding:10px;">${hostReceived}</td></tr>
                        <tr><th style="border:1px solid #ddd; padding:10px; background:#f8f9fa;">Status</th><td style="border:1px solid #ddd; padding:10px;">${status}</td></tr>
                    </table>
                </div>
            </div>
        `;

                const opt = {
                    margin: [5, 10, 10, 10],
                    filename: `booking_details_${Date.now()}.pdf`,
                    image: { type: 'jpeg', quality: 0.98 },
                    html2canvas: { scale: 2, scrollY: 0 }, // ensure capture from top
                    jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
                    pagebreak: { mode: ['avoid-all', 'css', 'legacy'] }
                };

                html2pdf().set(opt).from(htmlContent).save();
            });
        });

        $(document).ready(function() {
            $('#stripeCountry').select2({
                placeholder: "@lang('Select Country')",
                width: '100%',
                dropdownParent: $('#stripeCountry').closest('.modal'),
                minimumResultsForSearch: 5,
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const transactionsList = document.getElementById('transactionsList');
            const rangeSelect = document.getElementById('transactionRangeSelect');
            const currencySymbol = '{{ basicControl()->currency_symbol }}';

            function formatDate(dateStr) {
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                return new Date(dateStr).toLocaleDateString(undefined, options);
            }

            function renderTransactions(transactions) {
                transactionsList.innerHTML = '';

                if (!transactions || transactions.length === 0) {
                    transactionsList.innerHTML = `<p class="text-center">@lang('No transactions found').</p>`;
                    return;
                }

                transactions.forEach(tx => {
                    const card = document.createElement('div');
                    card.className = 'transaction-card';

                    card.innerHTML = `
                        <div class="transaction-icon"><i class="fas fa-receipt"></i></div>
                        <div class="transaction-details">
                            <div class="transaction-header">
                                <div>
                                    <h4>Transaction</h4>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h4>Trx: #${tx.trx_id}</h4>
                                        <span class="transaction-status badge-success">
                                            <i class="fas fa-check-circle"></i>
                                            @lang('success')
                                        </span>
                                    </div>
                                    <div class="transaction-date">${formatDate(tx.created_at)}</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-start">
                                    <span class="amount">${currencySymbol}${Number(tx.amount).toFixed(2)}</span>
                                </div>
                            </div>
                            <div class="transaction-meta">
                                <div class="meta-item">
                                    <span class="meta-label">Charge:</span>
                                    <span class="meta-value text-danger">${currencySymbol}${Number(tx.charge).toFixed(2)}</span>
                                        </div>
                                        <div class="meta-item">
                                            <span class="meta-label">Type:</span>
                                            <span class="meta-value">${tx.trx_type}</span>
                                        </div>
                                        <div class="meta-item">
                                            <span class="meta-label">For:</span>
                                            <span class="meta-value">${tx.for_transaction}</span>
                                        </div>
                                    </div>
                                    <div class="transaction-remarks">
                                        <p>${tx.remarks}</p>
                                    </div>
                                </div>
                            `;
                    transactionsList.appendChild(card);
                });
            }

            function fetchTransactions() {
                const range = rangeSelect.value;

                transactionsList.innerHTML = `<p class="text-center">@lang('Loading transactions...')</p>`;

                fetch(`{{ route('user.host.dash.transaction') }}?range=${encodeURIComponent(range)}`)
                    .then(res => res.json())
                    .then(data => {
                        renderTransactions(data.transactions);
                    })
                    .catch(err => {
                        console.error(err);
                        transactionsList.innerHTML = `<p class="text-center text-danger">@lang('Failed to load transactions.')</p>`;
                    });
            }

            fetchTransactions();

            rangeSelect.addEventListener('change', fetchTransactions);
        });
    </script>
@endpush
