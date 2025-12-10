<div class="modal fade" id="sendFeedback" tabindex="-1" aria-labelledby="sendFeedbackLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="sendFeedbackForm" method="post" action="{{ route('user.feedback.store') }}">
            @csrf

            <div class="modal-content">
                <div class="modal-header">
                    <h5>@lang('Submit feedback')</h5>
                </div>
                <div class="modal-body">
                    <div class="feedback-information w-100">
                        <label for="feedbackText" class="form-label">@lang('Your Feedback')</label>
                        <textarea class="form-control" name="feedback" id="feedbackText" rows="5" placeholder="@lang('Write your feedback here...')"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn-primary">@lang('Submit')</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="offcanvas listing-offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-light fa-arrow-right-from-line"></i></button>
        <h5 class="offcanvas-title" id="offcanvasRightLabel">@lang('Reservation Filter')</h5>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('user.transaction') }}" method="get">
            <div class="listing-offcanvas-form">

                <div class="listing-offcanvas-search">
                    <label for="search">@lang('Transaction Id')</label>
                    <input
                        type="search"
                        class="form-control"
                        name="transaction_id"
                        id="search"
                        placeholder="e.g. D315809740157"
                        value="{{ request()->get('transaction_id') }}"
                    >
                </div>

                <div class="select-option-content">
                    <label for="datefilter">@lang('Select Date')</label>
                    <input
                        type="text"
                        class="form-control"
                        name="datefilter"
                        id="reservationDateFilter"
                        placeholder="12/12/2024 - 14/12/2024"
                        autocomplete="off"
                        value=""
                    >
                </div>

                <button type="submit" class="btn-1">
                    <div class="btn-wrapper">
                        <div class="main-text btn-single">
                            @lang('Filter')
                        </div>
                        <div class="hover-text btn-single">
                            @lang('Filter')
                        </div>
                    </div>
                </button>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="confirmReservation" tabindex="-1" aria-labelledby="confirmReservationLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="confirmBookingForm" method="post" action="{{ route('user.booking.confirm') }}">
            @csrf
            <input type="hidden" name="booking_uid" id="bookingUid" value="">
            <div class="modal-content">
                <div class="modal-header modalHead">
                    <div class="booking-information">

                    </div>
                </div>
                <div class="modal-footer bx-shadow-0">
                    <button type="submit" class="btn btn-secondary" name="agree" value="0">@lang('Reject')</button>
                    <button type="submit" class="btn btn-primary" name="agree" value="1">@lang('Confirm')</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="completedReservation" tabindex="-1" aria-labelledby="completedReservationLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="completeReservation" method="post" action="{{ route('user.booking.completed') }}">
            @csrf
            <input type="hidden" name="booking_uid" id="completedBookingUid" value="">
            <div class="modal-content">
                <div class="modal-body">
                    @lang('Are you sure about make completed this booking?')
                </div>
                <div class="modal-footer bx-shadow-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Reject')</button>
                    <button type="submit" class="btn btn-primary" >@lang('Confirm')</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="refundedReservation" tabindex="-1" aria-labelledby="refundedReservationLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="refundedReservation" method="post" action="{{ route('user.booking.refunded') }}">
            @csrf
            <input type="hidden" name="booking_uid" id="refundedBookingUid" value="">
            <div class="modal-content">
                <div class="modal-body">
                    @lang('Are you sure about make refunded this booking?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Reject')</button>
                    <button type="submit" class="btn btn-" >@lang('Confirm')</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header position-relative">
                <h5 class="modal-title">
                    <i class="fas fa-calendar-check me-2"></i>@lang('Booking Details')
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <div class="booking-nav-tabs">
                    <div class="nav-tabs-container">
                        <ul class="nav nav-tabs nav-fill" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="booking-tab" data-bs-toggle="tab" data-bs-target="#booking" type="button" role="tab">
                                    <i class="fas fa-info-circle me-2"></i>@lang('Booking Info')
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="guests-tab" data-bs-toggle="tab" data-bs-target="#guests" type="button" role="tab">
                                    <i class="fas fa-users me-2"></i>@lang('Guests')
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">
                                    <i class="fas fa-credit-card me-2"></i>@lang('Payment')
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="tab-content p-4">
                    <div class="tab-pane fade show active" id="booking" role="tabpanel">
                        <div class="booking-info-grid">
                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-hotel"></i>
                                </div>
                                <div class="info-content">
                                    <label>@lang('Property')</label>
                                    <p id="propertyTitle">-</p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <div class="info-content">
                                    <label>@lang('Booking ID')</label>
                                    <p id="bookingId">-</p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <div class="info-content">
                                    <label>@lang('Check-in')</label>
                                    <p id="checkInDate">-</p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <div class="info-content">
                                    <label>@lang('Check-out')</label>
                                    <p id="checkOutDate">-</p>
                                </div>
                            </div>

                            <div class="info-item">
                                <div class="info-icon">
                                    <i class="fas fa-user-friends"></i>
                                </div>
                                <div class="info-content">
                                    <label>@lang('Guests')</label>
                                    <div class="guest-badges" id="guestsSummary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="guests" role="tabpanel">
                        <div class="guest-cards-container" id="guestCardsContainer">
                        </div>
                    </div>

                    <div class="tab-pane fade" id="payment" role="tabpanel">
                        <div class="payment-details">
                            <div class="payment-item">
                                <span class="payment-label">@lang('Amount without Discount')</span>
                                <span class="payment-value" id="amountWithoutDiscount">-</span>
                            </div>

                            <div class="payment-item discount">
                                <span class="payment-label">@lang('Discount Amount')</span>
                                <span class="payment-value" id="discountAmount">-</span>
                            </div>

                            <div class="payment-item">
                                <span class="payment-label">@lang('Site Charge')</span>
                                <span class="payment-value" id="siteCharge">-</span>
                            </div>

                            <div class="payment-divider"></div>

                            <div class="payment-item total">
                                <span class="payment-label">@lang('Total Amount')</span>
                                <span class="payment-value" id="totalAmount">-</span>
                            </div>

                            <div class="payment-item">
                                <span class="payment-label">@lang('Host Received')</span>
                                <span class="payment-value" id="hostReceived">-</span>
                            </div>

                            <div class="payment-item status">
                                <span class="payment-label">@lang('Status')</span>
                                <span id="status">-</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer bx-shadow-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                <button type="button" class="btn-3 other_btn detailPrintBtn">
                    <i class="fas fa-download me-2"></i>@lang('Download')
                </button>
            </div>
        </div>
    </div>
</div>
