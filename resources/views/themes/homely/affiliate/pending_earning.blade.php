@extends(template().'layouts.affiliate')
@section('title',trans('Pending Earning'))
@section('content')
    <section class="listing">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('affiliate.dashboard') }}">@lang('Dashboard')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('Earnings')</li>
                    </ul>
                    <h4>@lang('Earnings')</h4>
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
                                        <th scope="col">@lang('Property')</th>
                                        <th scope="col">@lang('Amount')</th>
                                        <th scope="col">@lang('Release Date')</th>
                                        <th scope="col">@lang('Status')</th>
                                        <th scope="col">@lang('Created At')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($pendings ?? [] as $item)
                                        <tr>
                                            <td data-label="Listing">
                                                <div class="listing-image-container">
                                                    <div class="listing-image">
                                                        @if($item->property?->photos?->images['thumb'])
                                                            <img src="{{ getFile($item->property?->photos?->images['thumb']['driver'], $item->property?->photos?->images['thumb']['path']) }}" alt="{{ optional($item->property)->title ?? '' }}">
                                                        @else
                                                            <img src="{{ asset(template(true).'img/no_image.png') }}" alt="@lang('No image available')">
                                                        @endif
                                                    </div>
                                                    <h6>{{ optional($item->property)->title ?? '' }}</h6>
                                                </div>
                                            </td>
                                            <td data-label="Amount">
                                                {{ currencyPosition($item->amount) }}
                                            </td>
                                            <td data-label="Release Date">
                                                <span>{{ dateTime($item->payment_release_date) }}</span>
                                            </td>
                                            <td data-label="Status">
                                                @if($item->status == 0)
                                                    <span class="badge bg-warning-subtle text-warning">@lang('Pending')</span>
                                                @elseif($item->status == 1)
                                                    <span class="badge bg-success-subtle text-success">@lang('Released')</span>
                                                @else
                                                    <span class="badge bg-secondary-subtle text-secondary">@lang('Unknown')</span>
                                                @endif
                                            </td>
                                            <td data-label="created_at">
                                                <span>{{ dateTime($item->created_at) }}</span>
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
            <form action="{{ route('affiliate.pending.earning') }}" method="get">
                <div class="listing-offcanvas-form">

                    <div class="listing-offcanvas-search">
                        <label for="search">@lang('Property')</label>
                        <input
                            type="search"
                            class="form-control"
                            name="property"
                            id="search"
                            placeholder="e.g. Boat in Tha Kradan"
                            value="{{ request()->get('property') }}"
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

    </script>
@endpush
