@extends(template().'layouts.affiliate')
@section('title',trans('Transactions'))
@section('content')
    <section class="listing">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('affiliate.dashboard') }}">@lang('Dashboard')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('Payments')</li>
                    </ul>
                    <h4>@lang('Payments')</h4>
                </div>
                <div class="reservations-date"
                     data-bs-toggle="offcanvas"
                     data-bs-target="#offcanvasRight"
                     aria-controls="offcanvasRight">
                    <div class="reservations-date-icon">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 32 32"
                             aria-hidden="true"
                             role="presentation"
                             focusable="false"
                             style="display: block; fill: none; height: 16px; width: 16px; stroke: currentcolor; stroke-width: 2; overflow: visible;">
                            <path fill="none"
                                  d="M7 16H3m26 0H15M29 6h-4m-8 0H3m26 20h-4M7 16a4 4 0 1 0 8 0 4 4 0 0 0-8 0zM17 6a4 4 0 1 0 8 0 4 4 0 0 0-8 0zm0 20a4 4 0 1 0 8 0 4 4 0 0 0-8 0zm0 0H3"></path>
                        </svg>
                        @lang('Filter')
                    </div>
                </div>
            </div>
            <div class="listing-container">
                <div class="shop-view-content">
                    <div class="tab-content" id="nav-tabContent">
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
                                        <th scope="col">@lang('Download')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($payments as $item)
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

                                                <td data-label="download" class="text-center">
                                                    <a href="javascript:void(0)"
                                                        class="btn-3 btn-3 other_btn2 downloadData"
                                                        data-trx="{{ $item->trx_id }}"
                                                        data-amount="{{ currencyPosition($item->amount) }}"
                                                        data-balance="{{ currencyPosition($item->balance) }}"
                                                        data-charge="{{ currencyPosition($item->charge) }}"
                                                        data-trx_type="{{ $item->trx_type }}"
                                                        data-remarks="{{ $item->remarks }}"
                                                        data-created="{{ dateTime($item->created_at) }}">
                                                        <div class="btn-wrapper">
                                                            <div class="main-text btn-single">
                                                                <i class="far fa-download"></i>
                                                            </div>
                                                            <div class="hover-text btn-single">
                                                                <i class="far fa-download"></i>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            @include('empty')
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="offcanvas listing-offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-light fa-arrow-right-from-line"></i></button>
            <h5 class="offcanvas-title" id="offcanvasRightLabel">@lang('Transation Filter')</h5>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('affiliate.payments') }}" method="get">
                <div class="listing-offcanvas-form">
                    <div class="listing-offcanvas-search">
                        <label for="search">@lang('Transaction ID')</label>
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
                            id="transactionDateFilter"
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
@endsection
@push('style')
    <style>
        .select2-container .select2-selection--single {
            height: 43px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 41px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }
        .listing-top {
            padding: 99px 0 63px;
        }
        .personal-info-title {
            margin-bottom: 0 !important;
        }

    </style>
@endpush
@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        $('#transactionDateFilter').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#transactionDateFilter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(
                picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY')
            );
        });

        $('#transactionDateFilter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
        document.addEventListener("click", function (e) {
            if (e.target.closest(".downloadData")) {
                e.preventDefault();
                let btn = e.target.closest(".downloadData");

                let trx = btn.dataset.trx;
                let amount = btn.dataset.amount;
                let balance = btn.dataset.balance;
                let charge = btn.dataset.charge;
                let trx_type = btn.dataset.trx_type;
                let remarks = btn.dataset.remarks;
                let created = btn.dataset.created;

                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                doc.setFontSize(16);
                doc.text("Payment Details", 20, 20);

                doc.setFontSize(12);
                doc.text("Transaction ID: " + trx, 20, 40);
                doc.text("Amount: " + trx_type + " " + amount, 20, 50);
                doc.text("Balance: " + balance, 20, 60);
                doc.text("Charge: " + charge, 20, 70);
                doc.text("Remarks: " + remarks, 20, 80);
                doc.text("Date: " + created, 20, 90);

                doc.save("payment-" + trx + ".pdf");
            }
        });
    </script>
@endpush
