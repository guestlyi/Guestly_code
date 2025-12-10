@extends('admin.layouts.app')
@section('page_title',__('Destinations'))
@section('content')
    <div class="content container-fluid">
        <x-page-header menu="Destinations" :statBtn="true"/>

        <div class="row d-none" id="statsSection">
            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                <div class="card card-hover-shadow h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-3">@lang('Active Destination')</h6>
                                <h3 class="card-title js-counter" data-value="{{ $totalActiveDestination }}">{{ $totalActiveDestination }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $totalDestination }}</span>
                                    <span class="badge bg-soft-success text-success ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($totalActivePercentage, 2) }}%
                                    </span>
                                </div>
                            </div>
                            <span class="icon icon-soft-success icon-lg icon-circle ms-3">
                                <i class="bi-check2-circle fs-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                <div class="card card-hover-shadow h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-3">@lang('Inactive Destination')</h6>
                                <h3 class="card-title js-counter" data-value="{{ $totalInactiveDestination }}">{{ $totalInactiveDestination }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $totalDestination }}</span>
                                    <span class="badge bg-soft-danger text-danger ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($totalInactivePercentage, 2) }}%
                                    </span>
                                </div>
                            </div>
                            <span class="icon icon-soft-danger icon-lg icon-circle ms-3">
                                <i class="bi-x-circle fs-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                <div class="card card-hover-shadow h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-3">@lang("Created By Today")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $totalDestination }}">{{ $totalDestination }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $totalDestination }}</span>
                                    <span class="badge bg-soft-primary text-primary ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($totalTotalCreatedTodayPercentage, 2) }}%
                                    </span>
                                </div>
                            </div>
                            <span class="icon icon-soft-primary icon-lg icon-circle ms-3">
                                <i class="bi-calendar-day fs-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3 mb-3 mb-lg-4">
                <div class="card card-hover-shadow h-100 stats-card">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <h6 class="card-subtitle mb-3">@lang("Created This Month")</h6>
                                <h3 class="card-title js-counter" data-value="{{ $totalCreatedThisMonth }}">{{ $totalCreatedThisMonth }}</h3>
                                <div class="d-flex align-items-center">
                                    <span class="d-block fs-6">@lang('from') {{ $totalDestination }}</span>
                                    <span class="badge bg-soft-info text-info ms-2">
                                        <i class="bi-graph-up"></i> {{ number_format($totalTotalCreatedThisMonthPercentage, 2) }}%
                                    </span>
                                </div>
                            </div>
                            <span class="icon icon-soft-info icon-lg icon-circle ms-3">
                                <i class="bi-calendar-month fs-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header card-header-content-md-between">
                <div class="mb-2 mb-md-0">
                    <div class="input-group input-group-merge input-group-flush">
                        <div class="input-group-prepend input-group-text">
                            <i class="bi-search"></i>
                        </div>
                        <input id="datatableSearch" type="search" class="form-control" placeholder="@lang('Search Destinations')"
                               aria-label="@lang('Search Destinations')" autocomplete="off">
                    </div>
                </div>

                <div class="d-grid d-sm-flex justify-content-md-end align-items-sm-center gap-2">
                    <div id="datatableCounterInfo">
                        <div class="d-flex align-items-center">
                            <span class="fs-5 me-3">
                              <span id="datatableCounter">0</span>
                              @lang('Selected')
                            </span>
                            <a class="btn btn-outline-danger btn-sm" href="javascript:void(0)" data-bs-toggle="modal"
                               data-bs-target="#DeleteMultipleModal">
                                <i class="bi-trash"></i> @lang('Delete')
                            </a>
                        </div>
                    </div>
                    <div id="datatableCounterInfo2" class="d-none">
                        <div class="d-flex align-items-center">
                            <a class="btn btn-outline-primary btn-sm inactiveButton" id="inactiveButton" href="javascript:void(0)" data-bs-toggle="modal"
                               data-bs-target="#statusMultipleModal">
                                <i class="fal fa-gauge me-2"></i>@lang('Status Change')
                            </a>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="btn btn-white btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">
                            <i class="bi-filter me-1"></i> @lang('Filter')
                        </button>
                        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                            <div class="offcanvas-header">
                                <h5 id="offcanvasRightLabel"><i class="bi-search me-1"></i>@lang('Filter')</h5>
                                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                            </div>
                            <div class="offcanvas-body">
                                <form id="filter_form">
                                    <div class="col-12 mb-4">
                                        <span class="text-cap text-body">@lang("Destination Title")</span>
                                        <input type="text" class="form-control" id="username_filter_input" placeholder="Cape Town" autocomplete="off">
                                    </div>
                                    <div class="col-sm mb-4">
                                        <small class="text-cap text-body">@lang('Status')</small>
                                        <div class="tom-select-custom">
                                            <select
                                                class="js-select js-datatable-filter form-select form-select-sm"
                                                id="filter_status"
                                                data-target-column-index="4" data-hs-tom-select-options='{
                                                              "placeholder": "Any status",
                                                              "searchInDropdown": false,
                                                              "hideSearch": true,
                                                              "dropdownWidth": "10rem"
                                                            }'>
                                                <option value="all"
                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-secondary"></span>All Status</span>'>
                                                    @lang('All Status')
                                                </option>
                                                <option value="0"
                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-warning"></span>Inactive</span>'>
                                                    @lang('Inactive')
                                                </option>
                                                <option value="1"
                                                        data-option-template='<span class="d-flex align-items-center"><span class="legend-indicator bg-success"></span>Active</span>'>
                                                    @lang('Active')
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 mb-4">
                                            <span class="text-cap text-body">@lang('Date Range')</span>
                                            <div class="input-group mb-3 custom">
                                                <input type="text" id="filter_date_range"
                                                       class="js-flatpickr form-control"
                                                       placeholder="Select dates"
                                                       data-hs-flatpickr-options='{
                                                                 "dateFormat": "d/m/Y",
                                                                 "mode": "range"
                                                               }' aria-describedby="flatpickr_filter_date_range">
                                                <span class="input-group-text" id="flatpickr_filter_date_range">
                                                        <i class="bi bi-arrow-counterclockwise"></i>
                                                    </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row gx-2">
                                        <div class="col">
                                            <div class="d-grid">
                                                <button type="button" id="clear_filter" class="btn btn-white">@lang('Clear Filters')</button>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="d-grid">
                                                <button type="button" class="btn btn-primary" id="filter_button"><i class="bi-search"></i> @lang('Apply')</button>
                                            </div>
                                        </div>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>


                    <a class="btn btn-primary btn-sm" href="{{ route('admin.destination.add') }}">
                        <i class="bi-plus-circle me-1"></i> @lang('Add New')
                    </a>
                </div>
            </div>


            <div class=" table-responsive datatable-custom  ">
                <table id="datatable"
                       class="js-datatable table table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                       data-hs-datatables-options='{
                       "columnDefs": [{
                          "targets": [0, 8],
                          "orderable": false
                        }],
                       "order": [],
                       "info": {
                         "totalQty": "#datatableWithPaginationInfoTotalQty"
                       },
                       "search": "#datatableSearch",
                       "entries": "#datatableEntries",
                       "pageLength": 15,
                       "isResponsive": false,
                       "isShowPaging": false,
                       "pagination": "datatablePagination"
                     }'>
                    <thead class="thead-light">
                    <tr>
                        <th class="table-column-pe-0">
                            <div class="form-check">
                                <input class="form-check-input check-all tic-check" type="checkbox" name="check-all"
                                       id="datatableCheckAll">
                                <label class="form-check-label" for="datatableCheckAll"></label>
                            </div>
                        </th>
                        <th>@lang('Destination')</th>
                        <th>@lang('Properties')</th>
                        <th>@lang('Country')</th>
                        <th>@lang('State')</th>
                        <th>@lang('City')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Created At')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>

                    <tbody>

                    </tbody>
                </table>
            </div>

            <div class="card-footer">
                <div class="row justify-content-center justify-content-sm-between align-items-sm-center">
                    <div class="col-sm mb-2 mb-sm-0">
                        <div class="d-flex justify-content-center justify-content-sm-start align-items-center">
                            <span class="me-2">@lang('Showing:')</span>
                            <div class="tom-select-custom">
                                <select id="datatableEntries"
                                        class="js-select form-select form-select-borderless w-auto" autocomplete="off"
                                        data-hs-tom-select-options='{
                                            "searchInDropdown": false,
                                            "hideSearch": true
                                          }'>
                                    <option value="10">10</option>
                                    <option value="15" selected>15</option>
                                    <option value="20">20</option>
                                </select>
                            </div>
                            <span class="text-secondary me-2">of</span>
                            <span id="datatableWithPaginationInfoTotalQty"></span>
                        </div>
                    </div>
                    <div class="col-sm-auto">
                        <div class="d-flex  justify-content-center justify-content-sm-end">
                            <nav id="datatablePagination" aria-label="Activity pagination"></nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.destination.partials.modals')
@endsection

@push('script')
    <script>
        HSCore.components.HSFlatpickr.init('.js-flatpickr')

        $(document).on('ready', function () {
            new HSCounter('.js-counter')
            new HSFileAttach('.js-file-attach')
            HSCore.components.HSTomSelect.init('.js-select', {
                maxOptions: 250,
            })

            HSCore.components.HSDatatables.init($('#datatable'), {
                processing: true,
                serverSide: true,
                ordering: false,
                ajax: {
                    url: "{{ route("admin.all.destination.search") }}",

                },
                columns: [
                    {data: 'checkbox', name: 'checkbox'},
                    {data: 'destination', name: 'destination'},
                    {data: 'properties', name: 'properties'},
                    {data: 'country', name: 'country'},
                    {data: 'state', name: 'state'},
                    {data: 'city', name: 'city'},
                    {data: 'status', name: 'status'},
                    {data: 'create-at', name: 'created-at'},
                    {data: 'action', name: 'action'},
                ],
                select: {
                    style: 'multi',
                    selector: 'td:first-child input[type="checkbox"]',
                    classMap: {
                        checkAll: '#datatableCheckAll',
                        counter: '#datatableCounter',
                        counterInfo: '#datatableCounterInfo'
                    }
                },
                language: {
                    zeroRecords: `<div class="text-center p-4">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                    <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                    <p class="mb-0">No data to show</p>
                    </div>`,
                    processing: `<div><div></div><div></div><div></div><div></div></div>`
                },
            })

            $('#datatable').on('select.dt deselect.dt', function () {
                var selectedRows = $('#datatable').DataTable().rows({ selected: true }).count();

                if (selectedRows > 0) {
                    $('#datatableCounterInfo').removeClass('d-none');
                    $('#datatableCounterInfo2').removeClass('d-none');
                } else {
                    $('#datatableCounterInfo').addClass('d-none');
                    $('#datatableCounterInfo2').addClass('d-none');
                }
            });
            $(document).on('click', '#filter_button', function () {
                let filterName = $('#username_filter_input').val();
                let filterDate = $('#filter_date_range').val();
                let filterStatus = $('#filter_status').val();

                const datatable = HSCore.components.HSDatatables.getItem(0);

                let url = "{{ route('admin.all.destination.search') }}";
                url += "?filterName=" + encodeURIComponent(filterName);
                url += "&filterDate=" + encodeURIComponent(filterDate);
                url += "&filterStatus=" + encodeURIComponent(filterStatus);

                datatable.ajax.url(url).load();
            });

            $.fn.dataTable.ext.errMode = 'throw';


            $(document).on('click', '#datatableCheckAll', function () {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });

            $(document).on('change', ".row-tic", function () {
                let length = $(".row-tic").length;
                let checkedLength = $(".row-tic:checked").length;
                if (length == checkedLength) {
                    $('#check-all').prop('checked', true);
                } else {
                    $('#check-all').prop('checked', false);
                }
            });
        });
    </script>

@endpush



