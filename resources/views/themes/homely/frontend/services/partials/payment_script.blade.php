@push('script')
    <script src="{{ asset(template(true).'js/flatpickr.min.js') }}"></script>
    <script>
        const userInfoRoute = '{{ route('user.booking.guest.info', $booking->uid) }}'
        const currencySymbol = '{{ basicControl()->currency_symbol }}';
        const booking_uid = '{{ $booking->uid }}';

        flatpickr("#check_in_date", {
            dateFormat: "Y-m-d",
            minDate: "today"
        });

        flatpickr("#check_out_date", {
            dateFormat: "Y-m-d",
            minDate: "today"
        });

        $(document).ready(function () {
            $('.change-btn').on('click', function () {
                $('#editBookingModal').modal('show');
            });

            $('#editBookingForm').on('submit', function (e) {
                e.preventDefault();

                const data = $(this).serialize();

                Notiflix.Loading.standard('Updating...');

                $.ajax({
                    url: '{{ route("user.booking.update", $booking->uid) }}',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    method: 'POST',
                    data: data,
                    success: function (response) {
                        Notiflix.Loading.remove();
                        Notiflix.Notify.success('Booking updated successfully!');
                        $('#editBookingModal').modal('hide');

                        const b = response.booking;

                        $('#booking-dates').text(`${b.check_in_date} - ${b.check_out_date}`);

                        $('#booking-guests .adult-result').text(`${b.adults} ${b.adults > 1 ? 'adults' : 'adult'}`);
                        $('#booking-guests .children-result').text(`${b.children} ${b.children > 1 ? 'children' : 'child'}`);
                        $('#booking-guests .pets-status').text(`${b.pets} ${b.pets > 1 ? 'pets' : 'pet'}`);

                        $('.night-count').text(`${b.nights} ${b.nights > 1 ? 'nights' : 'night'}`);

                        $('.total-amount').text(`Total: ${currencySymbol} ${b.total_amount}`);

                        if (parseFloat(b.total_amount_without_discount) > parseFloat(b.total_amount)) {
                            $('.amount-without-discount')
                                .text(`${currencySymbol} ${b.total_amount_without_discount}`)
                                .show();
                        } else {
                            $('.amount-without-discount').hide();
                        }
                        $('#amount').val(b.total_amount);

                        setTimeout(() => {
                            window.location.href = userInfoRoute;
                        }, 1000);
                    },
                    error: function (xhr) {
                        Notiflix.Loading.remove();
                        if (xhr.status === 422 && xhr.responseJSON?.error) {
                            Notiflix.Notify.failure(xhr.responseJSON.error);
                        } else {
                            let message = xhr.responseJSON?.message || 'Failed to update booking.';
                            Notiflix.Notify.failure(message);
                        }
                    }
                });
            });
            initializePaymentSection();

            function initializePaymentSection() {
                let amountField = $('#amount');
                let amountStatus = false;
                let selectedGateway = "";
                let baseCurrency = "{{basicControl()->currency_symbol}}";

                function clearMessage(fieldId) {
                    $(fieldId).removeClass('is-valid')
                    $(fieldId).removeClass('is-invalid')
                    $(fieldId).closest('div').find(".invalid-feedback").html('');
                    $(fieldId).closest('div').find(".is-valid").html('');
                }

                calculateAmount();

                $(document).on('click', '.select-payment-method', function () {
                    calculateAmount();
                });

                function calculateAmount() {

                    $('.showCharge').html(`${baseCurrency}0.00`);
                    selectedGateway = $('.select-payment-method:checked').val();
                    let updatedWidth = window.innerWidth;
                    window.addEventListener('resize', () => {
                        updatedWidth = window.innerWidth;
                    });

                    let html = `
                    <div class="card bookingPayment">
                        <div class="card-body">
                            <div class="row g-2 mb-3">
                                <div class="col-md-12">
                                    <input type="number" class="form-control" name="amount" id="amount" placeholder="0.00" step="0.0000000001" value="{{ $booking->total_amount }}" autocomplete="off" hidden=""/>
                                </div>

                                <div class="col-md-12 fiat-currency">
                                    <label class="form-label">@lang("Supported Currency")</label>
                                    <select class="nice-select" name="supported_currency" id="supported_currency">
                                        <option value="" disabled selected>@lang("Select Currency")</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>

                                <div class="col-md-12 crypto-currency"></div>
                            </div>
                            <div class="payment-summary">
                                <ul class="show-deposit-summery"></ul>
                                <div class="checkBox">
                                    <input class="form-check-input agree-checked" type="checkbox" value="" id="Yes, i have confirmed the order!" required>
                                    <label class="form-check-label2 mb-2" for="Yes, i have confirmed the order!">
                                        @lang("I agree to the") <a href="{{ route('page','terms-and-conditions') }}" class="link">@lang("terms and conditions.")</a>
                                    </label>
                                </div>
                                <button type="submit" class="btn-3 confirmBtn mt-4">
                                    <div class="btn-wrapper">
                                        <div class="main-text btn-single">
                                            @lang("confirm and continue")
                                        </div>
                                        <div class="hover-text btn-single">
                                                @lang("confirm and continue")
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>`;

                    if (updatedWidth <= 991) {
                        $('.side-bar').html('');
                        $('#paymentModalBody').html(html);
                        let paymentModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
                        paymentModal.show();
                    } else {
                        $('.side-bar').html(html);
                    }

                    $('#supported_currency').niceSelect('destroy');
                    $('#supported_currency').niceSelect();

                    supportCurrency(selectedGateway);
                }


                function supportCurrency(selectedGateway) {
                    if (!selectedGateway) {
                        console.error('Selected Gateway is undefined or null.');
                        return;
                    }
                    $('#supported_currency').empty();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{ route('supported.currency') }}",
                        data: {
                            gateway: selectedGateway,
                            uid : booking_uid,
                        },
                        type: "GET",
                        success: function (response) {
                            $('#supported_currency').empty();

                            if (response.data === "") {
                                $('#supported_currency').append(`<option value="USD">USD</option>`);
                            } else {
                                let markup = '<option value="" disabled selected>@lang("Select Currency")</option>';
                                $('#supported_currency').append(markup);

                                if (response.currencyType == 1) {
                                    $('.fiat-currency').show();
                                    $('.crypto-currency').hide();

                                    $(response.data).each(function (index, value) {
                                        let selected = index === 0 ? ' selected' : '';
                                        $('#supported_currency').append(`<option value="${value}"${selected}>${value}</option>`);
                                    });

                                    $('#supported_currency').niceSelect('destroy').niceSelect();

                                    let amount = $('#amount').val();
                                    let selectedCurrency = $('#supported_currency').val();

                                    checkAmount(amount, selectedCurrency, selectedGateway);
                                }

                                if (response.currencyType === 0) {
                                    $('.fiat-currency').hide();
                                    $('.crypto-currency').show();

                                    let markupCrypto = `
                                    <label class="form-label">@lang("Select Crypto Currency")</label>
                                    <select class="form-control nice-select" name="supported_crypto_currency" id="supported_crypto_currency">
                                        <option value="">@lang("Selected Crypto Currency")</option>
                                    </select>`;
                                    $('.crypto-currency').html(markupCrypto);

                                    $(response.data).each(function (index, value) {
                                        let selected = index === 0 ? ' selected' : '';
                                        $('#supported_crypto_currency').append(`<option value="${value}"${selected}>${value}</option>`);
                                    });

                                    $('#supported_crypto_currency').niceSelect('destroy').niceSelect();

                                    let amount = $('#amount').val();
                                    let selectedCurrency = $('#supported_crypto_currency').val();
                                    checkAmount(amount, selectedCurrency, selectedGateway, selectedCurrency);
                                }
                            }
                        },
                        error: function (error) {
                            console.error('AJAX Error:', error);
                        }
                    });
                }

                $(document).on('change input', '#amount, #supported_currency, .select-payment-method, #supported_crypto_currency', function (e) {

                    var amount = $('#amount').val();
                    let selectedCurrency = $('#supported_currency').val() ?? 'USD';
                    let selectedCryptoCurrency = $('#supported_crypto_currency').val();
                    let selectedGateway = $('.select-payment-method:checked').val();
                    let currency_type = 1;

                    if (!isNaN(amount) && amount > 0) {
                        let fraction = amount.split('.')[1];
                        let limit = currency_type == 0 ? 8 : 2;

                        if (fraction && fraction.length > limit) {
                            amount = (Math.floor(amount * Math.pow(10, limit)) / Math.pow(10, limit)).toFixed(limit);
                            $('#amount').val(amount);
                        }

                        checkAmount(amount, selectedCurrency, selectedGateway, selectedCryptoCurrency);
                    } else {
                        clearMessage(amountField);
                    }
                });


                function checkAmount(amount, selectedCurrency, selectGateway, selectedCryptoCurrency = null) {

                    $.ajax({
                        method: "GET",
                        url: "{{ route('deposit.checkAmount') }}",
                        dataType: "json",
                        data: {
                            'amount': amount,
                            'selected_currency': selectedCurrency,
                            'select_gateway': selectGateway,
                            'selectedCryptoCurrency': selectedCryptoCurrency,
                            'amountType': 'yes',
                            'uid' : booking_uid,
                        }
                    }).done(function (response) {
                        let amountField = $('#amount');
                        if (response.status) {

                            clearMessage(amountField);
                            $(amountField).addClass('is-valid');
                            $(amountField).closest('div').find(".valid-feedback").html(response.message);

                            $('.confirmBtn').removeClass('d-none').addClass('d-block');
                            $('.form-check').removeClass('d-none').addClass('d-block');
                            amountStatus = true;
                            let base_currency = "{{ basicControl()->base_currency }}"
                            showSummery(response, base_currency);
                        } else {
                            amountStatus = false;
                            clearMessage(amountField);
                            $(amountField).addClass('is-invalid');

                            Notiflix.Notify.failure(response.message);
                        }


                    });
                }


                function showSummery(response, currency) {
                    let formattedAmount = response.amount;
                    let formattedChargeAmount = response.charge;
                    let formattedPayableAmount = response.payable_amount;
                    let payableAmountInBase = response.payable_amount_baseCurrency;
                    let baseCurrencySymbol = "{{ basicControl()->base_currency }}";

                    let paymentSummery = `
                    <h5>@lang("Payment Summery")</h5>
                    <li>
                        <strong>Amount</strong> ${formattedAmount} ${response.currency}
                    </li>
                    <li class="text-danger">
                        <strong>Charge</strong>
                        <span class="item-value">${formattedChargeAmount} ${response.currency}</span>
                    </li>
                    <li>
                        <strong>Payable Amount</strong>${formattedPayableAmount} ${response.currency}
                   </li>
                   <li>
                        <strong>Payable Amount <sub>(in base currency)</sub></strong>${payableAmountInBase} ${baseCurrencySymbol}
                   </li>`;
                    $('.show-deposit-summery').html(paymentSummery)
                }
            }
        });

        isAgree();

        function isAgree() {
            const isAgreeChecked = $(".agree-checked").is(":checked");
            const isPaymentMethodSelected = $(".select-payment-method").is(":checked");

            if (isAgreeChecked && isPaymentMethodSelected) {
                $('.payment-btn-group .btn-3').attr('disabled', false);
            } else {
                $('.payment-btn-group .btn-3').attr('disabled', true);
            }
        }

        $(document).on('click', '.select-payment-method, .agree-checked', function () {
            isAgree();
        });
    </script>
@endpush
