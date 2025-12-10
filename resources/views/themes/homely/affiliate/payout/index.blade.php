@extends(template().'layouts.affiliate')
@section('title',__('Affiliate Payout History'))

@section('content')
    <section class="listing">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('Payout History')</li>
                    </ul>
                    <h4>@lang('Payout History')</h4>
                </div>
                <div class="d-flex align-items-center justify-content-end gap-3">
                    <a href="{{ route('affiliate.payout.now') }}" class="btn-3 other_btn">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                @lang('Payout Now')
                            </div>
                            <div class="hover-text btn-single">
                                @lang('Payout Now')
                            </div>
                        </div>
                    </a>
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
                                            <th>@lang('Transaction ID')</th>
                                            <th>@lang('Amount')</th>
                                            <th>@lang('Amount ')<sub>(@lang('In Base'))</sub></th>
                                            <th>@lang('Status')</th>
                                            <th>@lang('Created time')</th>
                                            <th>@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($payouts as $key => $value)
                                        <tr>
                                            <td data-label="@lang('Transaction ID')">{{ __($value->trx_id) }}</td>
                                            <td data-label="@lang('Amount')">{{ (getAmount($value->amount)).' '.$value->payout_currency_code }}</td>
                                            <td data-label="@lang('Amount in base')">{{ currencyPosition($value->net_amount_in_base_currency) }}</td>
                                            <td data-label="@lang('Status')">
                                                @php
                                                    $statusMap = [
                                                        0 => ['label' => 'Pending', 'bg' => 'bg-warning-subtle', 'text' => 'text-warning'],
                                                        1 => ['label' => 'Generated', 'bg' => 'bg-info-subtle', 'text' => 'text-info'],
                                                        2 => ['label' => 'Success', 'bg' => 'bg-success-subtle', 'text' => 'text-success'],
                                                        3 => ['label' => 'Canceled', 'bg' => 'bg-danger-subtle', 'text' => 'text-danger'],
                                                    ];
                                                    $status = $statusMap[$value->status] ?? ['label' => 'Unknown', 'bg' => 'bg-secondary-subtle', 'text' => '#666'];
                                                @endphp

                                                <span class="{{ $status['bg'] }} px-2 py-1 rounded-pill {{ $status['text'] }}" >
                                                    {{ __($status['label']) }}
                                                </span>
                                            </td>
                                            <td data-label="@lang('Created time')"> {{ dateTime($value->created_at)}} </td>
                                            <td data-label="@lang('Action')" class="text-center">

                                                @php
                                                    $details = null;
                                                    if ($value->information) {
                                                        $details = [];
                                                        foreach ($value->information as $k => $v) {
                                                            if ($v->type == "file") {
                                                                $details[kebab2Title($k)] = [
                                                                    'type' => $v->type,
                                                                    'field_name' => $v->field_name,
                                                                    'field_value' => getFile(config('filesystems.default'), @$v->field_value ?? $v->field_name),
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

                                                    $statusColor = '';
                                                    $statusText = '';

                                                    if ($value->status == 0) {
                                                        $statusColor = 'badge bg-soft-warning text-warning';
                                                        $statusText = 'Pending';
                                                    } else if ($value->status == 1) {
                                                        $statusColor = 'badge bg-soft-info text-info';
                                                        $statusText = 'Generated';
                                                    } else if ($value->status == 2) {
                                                        $statusColor = 'badge bg-soft-success text-success';
                                                        $statusText = 'Success';
                                                    } else if ($value->status == 3) {
                                                        $statusColor = 'badge bg-soft-danger text-danger';
                                                        $statusText = 'Cancel';
                                                    }
                                                @endphp

                                                <button class="btn-sm btn-3 other_btn2 viewBtn"
                                                    data-bs-target="#detailsModal"
                                                    data-bs-toggle="modal"
                                                    data-id='{{ $value->id }}'
                                                    data-info='{{ json_encode($details) }}'
                                                    data-userid=' {{ optional($value->affiliates)->id }} '
                                                    data-sendername='{{ optional($value->affiliates)->firstname. ' ' . optional($value->affiliates)->lastname}}'
                                                    data-transactionid='{{ $value->trx_id }}'
                                                    data-feedback='{{ $value->feedback }}'
                                                    data-amount='{{ getAmount($value->amount).$value->payout_currency_code }}'
                                                    data-method='{{ optional($value->method)->name }}'
                                                    data-gatewayimage='{{ getFile(optional($value->method)->driver, optional($value->method)->logo) }}'
                                                    data-datepaid='{{ dateTime($value->created_at) }}'
                                                    data-status='{{ $value->status }}'
                                                    data-status_color='{{ $statusColor }}'
                                                    data-status_text='{{ $statusText }}'
                                                    data-username='{{ optional($value->affiliates)->username }}'>
                                                    <div class="btn-wrapper">
                                                        <div class="main-text btn-single">
                                                            <i class="fal fa-eye"></i>
                                                        </div>
                                                        <div class="hover-text btn-single">
                                                            <i class="fal fa-eye"></i>
                                                        </div>
                                                    </div>
                                                </button>


                                            </td>
                                        </tr>
                                    @empty
                                        @include('empty')
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $payouts->appends($_GET)->links(template().'partials.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="offcanvas listing-offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
        <div class="offcanvas-header">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"><i class="fa-light fa-arrow-right-from-line"></i></button>
            <h5 class="offcanvas-title" id="offcanvasRightLabel">@lang('Payout Filter')</h5>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('affiliate.payouts') }}" method="get">
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
                        <label for="payoutDateFilter">@lang('Select Date')</label>
                        <input
                            type="text"
                            class="form-control"
                            name="datefilter"
                            id="payoutDateFilter"
                            placeholder="12/12/2024 - 14/12/2024"
                            autocomplete="off"
                            value=""
                        >
                    </div>

                    <button type="submit" class="btn-1">
                        @lang('Filter')
                    </button>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <form role="form" method="POST" class="actionRoute" action="" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="modal-body">
                        <div class="text-center mb-5">
                            <h3 class="mb-1">@lang('Withdraw Information')</h3>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-cap text-secondary mb-0">@lang('Sender Name:')</small>
                                <h5 class="text-dark sender_name"></h5>
                                <input type="hidden" name="user_id" class="user-id">
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-cap text-secondary mb-0">@lang('Transaction Id:')</small>
                                <span class="text-dark transaction_id"></span>
                            </div>

                            <div class="col-md-4">
                                <small class="text-cap text-secondary mb-0">@lang('Payment method:')</small>
                                <div class="d-flex align-items-center">
                                    <img class="avatar avatar-xss me-2 gateway_modal_image" src="" alt="Image Description">
                                    <span class="text-dark method"></span>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-6">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-cap text-secondary mb-0">@lang('Amount paid:')</small>
                                <h5 class="text-dark amount"></h5>
                            </div>

                            <div class="col-md-4 mb-3 mb-md-0">
                                <small class="text-cap text-secondary mb-0">@lang('Date paid:')</small>
                                <span class="text-dark date"></span>
                            </div>

                            <div class="col-md-4">
                                <small class="text-cap text-secondary mb-0">@lang('Status:')</small>
                                <div class="d-flex align-items-center">
                                    <span id="status" class="status"></span>
                                </div>
                            </div>
                        </div>

                        <small class="text-cap mb-2">@lang('Summary')</small>
                        <ul class="list-group mb-4 payment_information">
                        </ul>
                        <div class="get-feedback">

                        </div>
                        <div class="modal-footer-text mt-2">
                            <div class="d-flex justify-content-end gap-3 status-buttons">
                                <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
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
        .personal-info-title{
            margin-bottom: 0 !important;
        }
        #detailsModal .list-group-item{
            padding: 20px !important;
        }
        #detailsModal .gateway_modal_image{
            width: 25px;
        }
    </style>
@endpush

@push('script')
    <script>
        $('#payoutDateFilter').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear'
            }
        });

        $('#payoutDateFilter').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(
                picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY')
            );
        });

        $('#payoutDateFilter').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });

        $(document).on("click", '.viewBtn', function (e) {
            let id = $(this).data('id');
            let amount = $(this).data('amount');
            let status = $(this).data('status');
            let method = $(this).data('method');
            let date = $(this).data('datepaid');
            let senderName = $(this).data('sendername');
            let transactionID = $(this).data('transactionid');
            let userId = $(this).data('userid');
            let status_color = $(this).data('status_color');
            let status_text = $(this).data('status_text');


            $('.user-id').val(userId);
            $('.sender_name').html(senderName);
            $('.transaction_id').html(transactionID);
            $('.amount').html(amount);
            $('.method').html(method);
            $('.date').html(date);

            $("#status").attr('class', status_color);
            $("#status").text(status_text);

            if (status == 2 || status == 3) {
                $(".status-buttons button[name='status']").hide();
            } else if (status == 1) {
                $(".status-buttons button[name='status']").show();
            }

            let feedback = $(this).data('feedback');
            let gatewayImage = $(this).data('gatewayimage');
            $('.gateway_modal_image').attr('src', gatewayImage)


            $(".action_id").val(id);
            $(".actionRoute").attr('action', $(this).data('action'));

            let details = Object.entries($(this).data('info'));


            let list = details.map(([key, value]) => {

                let field_name = value.field_name;
                let field_value = value.field_value;
                let field_name_text = field_name.replace(/_/g, ' ');

                if (value.type == 'file') {
                    return `<li class="list-group-item text-dark">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-capitalize">${field_name_text}</span>
                                        <a href="${field_value}" target="_blank"><img src="${field_value}" alt="Image Description" class="rounded-1" width="100"></a>
                                    </div>
                                </li>`;
                } else {
                    return `<li class="list-group-item text-dark">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-capitalize">${field_name_text}</span>
                                        <span>${field_value}</span>
                                    </div>
                                </li>`;
                }
            })

            let feedbackField = "";
            if (feedback == '') {
                feedbackField = `<div class="mb-3">
                                        <small class="text-cap mb-2">@lang('Send You Feedback')</small>
                                        <textarea name="feedback" class="form-control" placeholder="Feedback" rows="3">{{old('feedback')}}</textarea>
                                     </div>`;

            } else {
                feedbackField = `<div class="mb-3">
                                        <small class="text-cap mb-2">@lang('Feedback')</small>
                                        <p>${feedback}</p>
                                     </div>`;

            }

            $('.get-feedback').html(feedbackField)

            $('.payment_information').html(list);
            $('.image').html(list);

        });
    </script>
@endpush


