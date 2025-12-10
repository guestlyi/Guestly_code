@extends(template().'layouts.user')
@section('title',trans('Payout'))
@section('content')

    <div class="content container-fluid">
        <div class="row justify-content-lg-center">
            <div class="col-lg-10 section-payment">
                <div class="personal-info-title listing-top">
                    <div class="text-area">
                        <ul>
                            <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                            <li><i class="fa-light fa-chevron-right"></i></li>
                            <li>@lang('Payout')</li>
                        </ul>
                        <h4>@lang('Payout')</h4>
                    </div>
                </div>
                <div class="mb-4 d-flex flex-column gap-2">
                    @if(!config('withdrawaldays')[date('l')])
                        <h5 class="text-warning alert-text"><i class="fas fa-exclamation-triangle me-2"></i> @lang('Withdraw processing is off today. Please try' ) @foreach(config('withdrawaldays') as $key => $days)
                                {{$days == 1 ? $key.',':''}}
                            @endforeach
                        </h5>
                    @endif
                    <h5 class="text-warning alert-text"><i class="fas fa-exclamation-triangle me-2"></i> @lang('You current balance is: ' ) {{ currencyPosition(auth()->user()->balance) }} </h5>
                </div>


                <form action="{{ route('user.payout.request') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="payout-container">
                        <div class="row g-4">
                            <div class="col-lg-7 col-md-6">
                                <div class="card gateways">
                                    <div class="card-header payment-header">
                                        <h4 class="card-header-title">@lang('Your preferred payout method?')</h4>
                                    </div>
                                    <div class="card-body pt-0">
                                        <div class="payment-section">
                                            <ul class="payment-container-list">
                                                @foreach($payoutMethod as $key => $method)
                                                    <li class="item">
                                                        <input type="radio" class="form-check-input selectPayoutMethod"
                                                               name="payout_method_id"
                                                               id="{{ $method->name }}"
                                                               value="{{ $method->id }}"
                                                               autocomplete="off"/>
                                                        <label class="form-check-label" for="{{ $method->name }}">
                                                            <div class="image-area">
                                                                <img src="{{ getFile($method->driver, $method->logo) }}" alt="">
                                                            </div>
                                                            <div class="content-area">
                                                                <h5>{{ $method->name }}</h5>
                                                                <span>{{ $method->description }}</span>
                                                            </div>
                                                        </label>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn btn-primary w-100 d-block d-md-none" id="showGatewaysButton">
                                                {{ trans('Select Payout Method') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="payout-info-box">
                                            <div class="d-flex flex-column" >
                                                <label class="form-label mt-3" for="supported_currency">{{ trans('Select Currency') }}
                                                    <span data-bs-toggle="tooltip" data-bs-placement="top"
                                                          data-bs-title="Kindly choose the currency through which you'd like to payout using the gateway.">
                                                    <i class="fa-regular fa-circle-question"></i></span>
                                                </label>
                                                <select class="nice-select" name="supported_currency" id="supported_currency">
                                                    <option value="" selected
                                                            disabled>{{ trans('Select a payout method first') }}</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="form-label mt-3" for="">{{ trans('Enter Amount') }}</label>
                                                <input class="form-control @error('amount') is-invalid @enderror"
                                                       name="amount" type="text" id="amount"
                                                       placeholder="Enter Amount" autocomplete="off"
                                                       onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')"
                                                />
                                                <span class="invalid-feedback">@error('amount') @lang($message) @enderror</span>
                                                <span class="valid-feedback"></span>
                                            </div>
                                        </div>


                                        <div class="side-box mt-3 mb-3">
                                            <div class="showCharge">

                                            </div>
                                        </div>

                                        @php $open = config('withdrawaldays')[date('l')] ?? false; @endphp
                                        <button type="submit" class="mt-3 btn-1 w-100 submitBtn" @disabled(!$open)>
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    {{ trans('Continue') }}
                                                </div>
                                                <div class="hover-text btn-single">
                                                    {{ trans('Continue') }}
                                                </div>
                                            </div>
                                        </button>


                                        <a href="{{ route('user.dashboard') }}"  class="mt-3 btn-3 w-100">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    {{ trans('Cancel') }}
                                                </div>
                                                <div class="hover-text btn-single">
                                                    {{ trans('Cancel') }}
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal for Payout Gateways (for mobile) -->
                        <div class="modal fade" id="gatewayModal" tabindex="-1" aria-labelledby="gatewayModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="gatewayModalLabel">{{ trans('Select a Payout Gateway') }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <ul class="payment-container-list d-lg-none d-block">
                                            @foreach($payoutMethod as $key => $method)
                                                <li class="item">
                                                    <input type="radio" class="form-check-input selectPayoutMethod"
                                                           name="payout_method_id" value="{{ $method->id }}"
                                                           id="modal-{{ $method->id }}"
                                                           autocomplete="off"/>
                                                    <label class="form-check-label" for="modal-{{ $method->id }}">
                                                        <div class="image-area">
                                                            <img src="{{ getFile($method->driver, $method->logo) }}" alt="">
                                                        </div>
                                                        <div class="content-area">
                                                            <h5>{{ $method->name }}</h5>
                                                            <span>{{ $method->description }}</span>
                                                        </div>
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('style')
    <style>
        .nice-select{
            font-size: 16px;
        }
        .showCharge .list-group-item{
            padding: 10px !important;
        }
        .personal-info-title{
            margin-bottom: 0 !important;
        }
        .section-payment{
            margin-bottom: 100px;
        }
        .bg-soft-success {
            background-color: rgba(25, 135, 84, 0.1);
        }
        .btn-4{
            padding: 14px !important;
        }
        .alert-text {
            --bs-text-opacity: 1;
            color: rgba(var(--bs-warning-rgb),var(--bs-text-opacity))!important;
            border: 1px solid;
            border-radius: 12px;
            border-left: 8px solid;
            font-size: 16px;
            padding: 17px 20px !important;
        }
    </style>
@endpush

@push('script')
    <script>
        'use strict';
        let payoutOpen = @json($open);

        $('#showGatewaysButton').on('click', function () {
            $('#gatewayModal').modal('show');
        });

        function emptyInput() {
            let amountField = $('#amount');
            amountField.val('');
            $('.submitBtn').prop('disabled', true);
            $('.showCharge').html('');
            $(amountField).addClass('is-invalid');
            $(amountField).closest('div').find(".valid-feedback").html('');
            $(amountField).closest('div').find(".invalid-feedback").html('Enter your amount');
        }

        $(document).ready(function () {
            let amountField = $('#amount');
            let btnStatus = false;
            let selectedPayoutMethod = "";
            let base_currency = "{{basicControl()->base_currency}}"


            $(document).on('click', '.selectPayoutMethod', function () {
                let id = this.id;
                $('#gatewayModal').modal('hide');

                selectedPayoutMethod = $(this).val();
                supportCurrency(selectedPayoutMethod);
            });

            function supportCurrency(selectedPayoutMethod) {
                if (!selectedPayoutMethod) {
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
                    url: "{{ route('user.payout.supported.currency') }}",
                    data: {gateway: selectedPayoutMethod},
                    type: "GET",
                    success: function (data) {
                        const $select = $('#supported_currency');

                        $select.niceSelect('destroy');
                        $select.empty();

                        if (!data || data.length === 0) {
                            $select.append(`<option value="USD">USD</option>`);
                        } else {
                            $select.append('<option value="" disabled selected>Select Currency</option>');
                            $(data).each(function (index, value) {
                                $select.append(`<option value="${value}">${value}</option>`);
                            });
                        }

                        $select.niceSelect();
                    },
                    error: function (error) {
                        console.error('AJAX Error:', error);
                    }
                });
            }

            $(document).on('change input', "#amount, #supported_currency, .selectPayoutMethod", function (e) {
                let amount = amountField.val();
                let selectedCurrency = $('#supported_currency').val();
                let currency_type = 1;

                if (!isNaN(amount) && amount > 0) {
                    let fraction = amount.split('.')[1];
                    let limit = currency_type == 0 ? 8 : 2;

                    if (fraction && fraction.length > limit) {
                        amount = (Math.floor(amount * Math.pow(10, limit)) / Math.pow(10, limit)).toFixed(limit);
                        amountField.val(amount);
                    }
                    checkAmount(amount, selectedCurrency, selectedPayoutMethod)
                } else {
                    clearMessage(amountField)
                    $('.showCharge').html('')
                }
            });

            function checkAmount(amount, selectedCurrency, selectedPayoutMethod) {
                $.ajax({
                    method: "GET",
                    url: "{{ route('user.payout.checkAmount') }}",
                    dataType: "json",
                    data: {
                        'amount': amount,
                        'selected_currency': selectedCurrency,
                        'selected_payout_method': selectedPayoutMethod,
                    }
                }).done(function (response) {
                    let amountField = $('#amount');
                    clearMessage(amountField);

                    if (response.status) {
                        btnStatus = true;
                        $(amountField).addClass('is-valid');
                        $(amountField).closest('div').find(".valid-feedback").html(response.message);
                        showCharge(response, base_currency);
                    } else {
                        btnStatus = false;
                        $(amountField).addClass('is-invalid');
                        $(amountField).closest('div').find(".invalid-feedback").html(response.message);
                        $('.showCharge').html('');
                    }

                    submitButton();
                });
            }

            function submitButton() {
                if (btnStatus && payoutOpen) {
                    $('.submitBtn').prop('disabled', false);
                } else {
                    $('.submitBtn').prop('disabled', true);
                }
            }

            function clearMessage(fieldId) {
                $(fieldId).removeClass('is-valid')
                $(fieldId).removeClass('is-invalid')
                $(fieldId).closest('div').find(".invalid-feedback").html('');
                $(fieldId).closest('div').find(".is-valid").html('');
            }

            function showCharge(response, currency) {
                let amount = parseFloat(response.amount).toFixed(2);
                let charge = parseFloat(response.charge).toFixed(2);
                let netPayout = parseFloat(response.net_payout_amount).toFixed(2);
                let netBase = parseFloat(response.net_amount_in_base_currency).toFixed(2);

                let txnDetails = `
                <div class="payout-info-box">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Amount In') }} ${response.currency} </span>
                            <span class="text-success"> ${amount} ${response.currency}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Charge') }}</span>
                            <span class="text-danger">  ${charge} ${response.currency}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('Payout Amount') }}</span>
                            <span class="text-info"> ${netPayout} ${response.currency}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>{{ __('In Base Currency') }}</span>
                            <span class="text-info"> ${netBase} ${currency}</span>
                        </li>
                    </ul>
                </div>
                `;
                $('.showCharge').html(txnDetails)
            }

        });
    </script>
@endpush







