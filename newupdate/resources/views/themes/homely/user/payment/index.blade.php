@extends(template().'layouts.user')
@section('title',trans('Dashboard'))
@section('content')

    <section class="listing">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('Payment History')</li>
                    </ul>
                    <h4>@lang('Payment History')</h4>
                </div>
                <div class="reservations-top">
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
            </div>
            <div class="listing-container">
                <div class="shop-view-content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="list-view-wrapper">
                            <div class="table-responsive d-flex flex-column-reverse">
                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th scope="col">@lang('Method')</th>
                                        <th scope="col">@lang('Transaction')</th>
                                        <th scope="col">@lang('Amount')</th>
                                        <th scope="col">@lang('Status')</th>
                                        <th scope="col">@lang('Action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($payments as $key => $value)
                                        <tr>
                                            <td data-label="@lang('Method')">{{ __(optional($value->gateway)->name) ?? __('N/A') }}</td>
                                            <td data-label="@@lang('Transaction')">{{ __($value->trx_id) }}</td>
                                            <td data-label="Amount">{{ currencyPosition($value->payable_amount_in_base_currency) }}</td>
                                            <td data-label="Status">
                                                @if($value->status == 1)
                                                    <span class="badge bg-soft-success text-success">@lang('Success')</span>
                                                @elseif($value->status == 0)
                                                    <span class="badge bg-soft-warning text-warning">@lang('Pending')</span>
                                                @elseif($value->status == 2)
                                                    <span class="badge bg-soft-info text-info">@lang('Requested')</span>
                                                @elseif($value->status == 3)
                                                    <span class="badge bg-soft-danger text-danger">@lang('Rejected')</span>
                                                @else
                                                    <span class="badge bg-soft-secondary text-secondary">@lang('Unknown')</span>
                                                @endif
                                            </td>
                                            <td data-label="@lang('Action')" class="text-center">
                                                @if($value->gateway->id > 999)
                                                    @php
                                                        $details = null;
                                                        if ($value->information) {
                                                            $details = [];
                                                            foreach ($value->information as $k => $v) {
                                                                if ($v->type == "file") {
                                                                    $details[kebab2Title($k)] = [
                                                                        'type' => $v->type,
                                                                        'field_name' => $v->field_name,
                                                                        'field_value' => getFile(config('filesystems.default'), $v->field_value),
                                                                    ];
                                                                } else {
                                                                    $details[kebab2Title($k)] = [
                                                                        'type' => $v->type,
                                                                        'field_name' => $v->field_name,
                                                                        'field_value' => @$v->field_value ?? $v->field_name
                                                                    ];
                                                                }
                                                            }
                                                        }
                                                    @endphp

                                                    <a class="btn-3 other_btn2 bookingView edit_btn" data-bs-target="#accountInvoiceReceiptModal" href="javascript:void(0)"
                                                       data-details_info= '{{ json_encode($details)  }}'
                                                       data-feedback="{{ e($value->note) }} "
                                                       data-gatewayimage="{{ getFile(optional($value->gateway)->driver, optional($value->gateway)->image) }}"
                                                       data-method="{{ optional($value->gateway)->name }} "
                                                       data-amount=" {{ currencyPosition(getAmount($value->payable_amount_in_base_currency)) }} "
                                                       data-status="{{ $value->status }}"
                                                       data-datepaid="{{ dateTime($value->created_at) }} "
                                                       data-bs-toggle="modal"
                                                       data-bs-original-title="Booking Details"
                                                    >
                                                        <div class="btn-wrapper">
                                                            <div class="main-text btn-single">
                                                                <i class="far fa-eye"></i>
                                                            </div>
                                                            <div class="hover-text btn-single">
                                                                <i class="far fa-eye"></i>
                                                            </div>
                                                        </div>
                                                    </a>
                                                @else
                                                    <span>-</span>
                                                @endif
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
            <h5 class="offcanvas-title" id="offcanvasRightLabel">@lang('Payment Filter')</h5>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('user.payment.history') }}" method="get">
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
                            id="paymentDateFilter"
                            placeholder="12/12/2024 - 14/12/2024"
                            autocomplete="off"
                            value=""
                        >
                    </div>
                    <button class="btn-1" type="submit">
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
    <div class="modal fade" id="accountInvoiceReceiptModal" tabindex="-1" role="dialog" aria-hidden="true"
         data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form role="form" method="POST" class="actionRoute" action="" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="text-center mb-5">
                            <h3 class="mb-1">@lang('Payment Information')</h3>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-cap text-secondary mb-0">@lang('Amount paid:')</small>
                                <h5 class="amount"></h5>
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0 showInfDate">
                                <small class="text-cap text-secondary mb-0">@lang('Date paid:')</small>
                                <span class="text-dark date"></span>
                            </div>

                            <div class="col-md-4">
                                <small class="text-cap text-secondary mb-0">@lang('Payment method:')</small>
                                <div class="d-flex align-items-center mt-1">
                                    <img class="avatar avatar-xss me-2 gateway_modal_image" src="" alt="Image Description">
                                    <span class="text-dark method"></span>
                                </div>
                            </div>
                        </div>

                        <small class="text-cap mb-2">@lang('Summary')</small>
                        <ul class="list-group mb-4 payment_information">
                        </ul>

                        <div class="get-feedback">


                        </div>



                        <div class="modal-footer-text mt-3">
                            <div class="d-flex justify-content-end gap-3 status-buttons">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
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
        .listing-top {
            padding: 100px 0 23px;
        }
        .reservations-date{
            cursor: pointer;
        }
        .reservations-date-icon{
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .list-group-item.listItem{
            padding: 10px !important;
        }
        .date::before{
            display: none;
        }
        .showInfDate{
            display: flex;
            flex-direction: column;
        }
        .showInfDate .date{
            padding: 0 !important;
        }
        .gateway_modal_image{
            width: 30px;
            border-radius: 4px;
        }
    </style>
@endpush
@push('script')
    <script>
        $(document).on("click", '.edit_btn', function (e) {
            let id = $(this).data('id');
            let status = $(this).data('status');
            let feedback = $(this).data('feedback');

            $('.gateway_modal_image').attr('src', $(this).data('gatewayimage'));

            if (status == 1) {
                $(".status-buttons button[name='status']").hide();
            }

            $(".action_id").val(id);
            $(".actionRoute").attr('action', $(this).data('action'));

            // Parse the details_info safely
            let rawDetails = $(this).attr('data-details_info');
            let details = [];

            try {
                details = Object.entries(JSON.parse(rawDetails));
            } catch (error) {
                console.error("Invalid JSON in data-details_info", error);
            }

            let list = details.map(([key, value]) => {
                let field_name = value.field_name;
                let field_value = value.field_value;
                let field_name_text = field_name.replace(/_/g, ' ');

                if (value.type === 'file') {
                    return `<li class="list-group-item text-dark listItem">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-capitalize">${field_name_text}</span>
                            <a href="${field_value}" target="_blank">
                                <img src="${field_value}" alt="Image Description" class="rounded-1" width="100">
                            </a>
                        </div>
                    </li>`;
                } else {
                    return `<li class="list-group-item text-dark listItem">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-capitalize">${field_name_text}</span>
                            <span>${field_value}</span>
                        </div>
                    </li>`;
                }
            });

            let feedbackField = "";
            if (!feedback || feedback.trim() === '') {
                feedbackField = `
                <div class="mb-3">
                    <small class="text-cap mb-2">@lang('Feedback')</small>
                    <textarea name="feedback" class="form-control feedback" placeholder="Feedback" rows="3" readonly>{{ old('feedback') }}</textarea>
                    @error('feedback')
                    <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                    </div>`;
                } else {
                    feedbackField = `
                <div class="mb-3">
                    <small class="text-cap mb-2">@lang('Feedback')</small>
                    <p>${feedback}</p>
                </div>`;
            }

            $('.get-feedback').html(feedbackField);
            $('.payment_information').html(list);
            $('.image').html(list);
            $('.amount').html($(this).data('amount'));
            $('.method').html($(this).data('method'));
            $('.date').html($(this).data('datepaid'));
        });


        $('#paymentDateFilter').daterangepicker({
            autoUpdateInput: false,
            locale: {
            cancelLabel: 'Clear'
        }
        });

        $('#paymentDateFilter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(
                picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY')
            );
        });

        $('#paymentDateFilter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

    </script>
@endpush

