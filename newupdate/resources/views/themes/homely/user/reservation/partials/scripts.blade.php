@push('script')
    <script src="{{ asset('assets/global/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

    <script>
        tinymce.init({
            selector: '#feedbackText',
            height: 250,
            menubar: false,
            plugins: 'link lists code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link | code',
            branding: false,
            license_key: 'gpl',
        });
        document.getElementById('sendFeedbackForm').addEventListener('submit', function (e) {
            tinymce.triggerSave();

            const content = document.getElementById('feedbackText').value.trim();
            if (content === '') {
                e.preventDefault();
                Notiflix.Notify.failure('Feedback field is required.');
            }
        });
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('exportCsvBtn').addEventListener('click', function () {
                const table = document.querySelector('.listing-container table');
                if (!table) return;

                let csv = '';
                const rows = table.querySelectorAll('tr');

                rows.forEach(row => {
                    const cols = row.querySelectorAll('th, td');
                    let rowData = [];
                    cols.forEach(col => {
                        let data = col.innerText.replace(/\n/g, ' ').replace(/,/g, '');
                        rowData.push(`"${data.trim()}"`);
                    });
                    csv += rowData.join(',') + '\n';
                });

                const blob = new Blob([csv], { type: 'text/csv' });
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.setAttribute('href', url);
                a.setAttribute('download', 'bookings.csv');
                a.click();
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.confirmReservation').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const booking = JSON.parse(this.dataset.booking);
                    const currencySymbol = '{{ basicControl()->currency_symbol }}';

                    document.getElementById('bookingUid').value = booking.uid;

                    const infoContainer = document.querySelector('.booking-information');

                    const guestName = booking.guest
                        ? `${booking.guest.firstname ?? ''} ${booking.guest.lastname ?? ''}`.trim()
                        : 'N/A';

                    const propertyTitle = booking.property?.title ?? 'N/A';

                    const infoHTML = `
                        <div class="booking-header border-bottom pb-2 mb-3">
                            <h5 class="modal-title fw-bold">@lang('Booking Information')</h5>
                        </div>
                        <div class="booking-details row g-3">
                            <div class="col-md-6">
                                <p><strong>@lang('Property'):</strong><br> <span class="text-muted">${propertyTitle}</span></p>
                                <p><strong>@lang('Guest'):</strong><br> <span class="text-muted">${guestName}</span></p>
                                <p><strong>@lang('Check-in'):</strong><br> <span class="text-success">${booking.check_in_date ?? 'N/A'}</span></p>
                                <p><strong>@lang('Check-out'):</strong><br> <span class="text-danger">${booking.check_out_date ?? 'N/A'}</span></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>@lang('Total Amount'):</strong><br> <span class="text-dark fw-bold">${currencySymbol}${booking.total_amount}</span></p>
                                <p><strong>@lang('Amount without Discount'):</strong><br> <span class="text-decoration-line-through text-muted">${currencySymbol}${booking.amount_without_discount}</span></p>
                                <p><strong>@lang('Discount'):</strong><br> <span class="text-success">- ${currencySymbol}${booking.discount_amount}</span></p>
                            </div>
                            <div class="col-12">
                                <hr>
                                <div class="d-flex justify-content-around text-center mb-3">
                                    <div>
                                        <strong>@lang('Adults')</strong><br>
                                        <span class="badge bg-primary">${booking.information?.adults ?? 0}</span>
                                    </div>
                                    <div>
                                        <strong>@lang('Children')</strong><br>
                                        <span class="badge bg-info">${booking.information?.children ?? 0}</span>
                                    </div>
                                    <div>
                                        <strong>@lang('Pets')</strong><br>
                                        <span class="badge bg-warning">${booking.information?.pets ?? 0}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    infoContainer.innerHTML = infoHTML;
                });
            });
        });

        $('#reservationDateFilter').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#reservationDateFilter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(
                picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY')
            );
        });

        $('#reservationDateFilter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        document.addEventListener('DOMContentLoaded', function () {
            const completeLinks = document.querySelectorAll('.completeReservation');
            const bookingUidInput = document.getElementById('completedBookingUid');

            completeLinks.forEach(link => {
                link.addEventListener('click', function () {
                    const booking = JSON.parse(this.getAttribute('data-booking'));
                    bookingUidInput.value = booking.uid;
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            const refundedLinks = document.querySelectorAll('.refundedReservation');
            const refundedUidInput = document.getElementById('refundedBookingUid');

            refundedLinks.forEach(link => {
                link.addEventListener('click', function () {
                    const booking = JSON.parse(this.getAttribute('data-booking'));
                    refundedUidInput.value = booking.uid;
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
                    case 0: return `<span class="badge bg-warning">@lang('Pending')</span>`;
                    case 1: return `<span class="badge bg-primary">@lang('Confirmed')</span>`;
                    case 2: return `<span class="badge bg-danger">@lang('Cancelled')</span>`;
                    case 3: return `<span class="badge bg-success">@lang('Completed')</span>`;
                    case 4: return `<span class="badge bg-info">@lang('Paid')</span>`;
                    case 5: return `<span class="badge bg-secondary">@lang('Refunded')</span>`;
                    default: return `<span class="badge bg-dark">@lang('Unknown')</span>`;
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
                    <div style="font-family: Arial, sans-serif; padding: 20px; color: #333;">
                        <h2 style="text-align: center; margin-bottom: 30px;">Booking Details</h2>

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
                    margin:       10,
                    filename:     `booking_details_${Date.now()}.pdf`,
                    image:        { type: 'jpeg', quality: 0.98 },
                    html2canvas:  { scale: 2 },
                    jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' }
                };
                html2pdf().set(opt).from(htmlContent).save();
            });
        });
    </script>
@endpush
