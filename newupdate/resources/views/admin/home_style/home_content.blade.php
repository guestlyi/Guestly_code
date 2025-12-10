@extends('admin.layouts.app')
@section('page_title', __('Home Contents'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Home Content")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Home Content Manage")</h1>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-9 mb-3 mb-lg-0">
                <form action="{{ route('admin.set.home.section.page') }}" method="post" enctype="multipart/form-data" id="setHomeForm">
                    @csrf

                    <div class="card mb-3 mb-lg-5">
                        <div class="card-header">
                            <h5 class="fw-semibold"><i class="bi bi-house-door me-1"></i> @lang('Set Home Section')</h5>
                        </div>

                        <div class="card-body">
                            <div class="mb-3">
                                <label for="destinations" class="form-label">@lang('Select Destination')</label>
                                <select name="destination" id="destinations" class="form-select">
                                    @foreach ($destinations as $destination)
                                        <option value="{{ $destination->id }}" data-home="{{ $destination->show_on_home }}" data-home-section-type="{{ $destination->home_section_type }}">
                                            {{ $destination->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="show_on_home" class="form-label">@lang('Show on Home Page')</label>
                                <select name="show_on_home" id="show_on_home" class="form-select">
                                    <option value="1">@lang('Yes')</option>
                                    <option value="0">@lang('No')</option>
                                </select>
                            </div>

                            <div id="homeSectionFields" style="display: none;">
                                <div class="mb-3">
                                    <label for="home_section_type" class="form-label">@lang('Home Section Type')</label>
                                    <select name="home_section_type" id="home_section_type" class="form-select">
                                        <option value="0">@lang('Popular homes in')</option>
                                        <option value="1">@lang('Available next month in')</option>
                                        <option value="2">@lang('Stay in')</option>
                                        <option value="3">@lang('Homes in')</option>
                                        <option value="4">@lang('Place to stay in')</option>
                                        <option value="5">@lang('Checkout homes in')</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">@lang('Sort Order')</label>
                                    <p class="text-muted small">@lang('Drag to reorder destinations. The top one will appear first on home.')</p>
                                    <ul id="sortOrderList" class="list-group mb-2"></ul>
                                    <input type="hidden" name="sort_order_data" id="sort_order_data">
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">@lang('Save Changes')</button>
                        </div>
                    </div>

                </form>
            </div>
            <div class="col-lg-3 mb-3 mb-lg-0">
                <div class="card">
                    <div class="card-header">
                        <h6 class="fw-semibold mb-0"><i class="bi bi-list-task me-1"></i> @lang('Currently on Home')</h6>
                    </div>
                    <div class="card-body">
                        @if(!empty($currentHomeDestinations) && count($currentHomeDestinations))
                            <ul class="list-group">
                                @foreach ($currentHomeDestinations as $home)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $home->title }}</span>
                                        <span class="badge bg-soft-info text-info">
                                            @switch($home->home_section_type)
                                                @case(0) @lang('Popular homes in') @break
                                                @case(1) @lang('Available next month in') @break
                                                @case(2) @lang('Stay in') @break
                                                @case(3) @lang('Homes in') @break
                                                @case(4) @lang('Place to stay in') @break
                                                @case(5) @lang('Checkout homes in') @break
                                                @default @lang('N/A')
                                            @endswitch
                                        </span>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0">@lang('No destinations currently displayed on home page.')</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
@endpush
@push('js-lib')
    <script src="{{ asset("assets/admin/js/hs-counter.min.js") }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function () {
            const initTomSelect = (selector, placeholder) => {
                const element = document.querySelector(selector);
                if (element) {
                    HSCore.components.HSTomSelect.init(selector, {
                        maxOptions: 250,
                        placeholder: placeholder
                    });
                }
            };

            initTomSelect('#show_on_home', 'Select an option');
            initTomSelect('#destinations', 'Select a destination');
            initTomSelect('#home_section_type', 'Select an option');

            const route = "{{ route('admin.set.home.section.page') }}";
            document.getElementById('setHomeForm').setAttribute('action', route);

            const homeSettings = @json($currentHomeDestinations);

            const destinationsSelect = document.getElementById('destinations');
            const showOnHomeSelect = document.getElementById('show_on_home');
            const homeSectionTypeSelect = document.getElementById('home_section_type');
            const homeSectionFields = document.getElementById('homeSectionFields');
            const sortOrderList = $('#sortOrderList');
            const sortOrderData = $('#sort_order_data');

            function renderHomeDestinations() {
                sortOrderList.empty();

                if (homeSettings.length) {
                    homeSettings.forEach((dest, index) => {
                        sortOrderList.append(`
                            <li class="list-group-item d-flex align-items-center justify-content-between" data-id="${dest.id}">
                                <span>${dest.title}</span>
                                <i class="bi bi-grip-vertical text-muted cursor-pointer"></i>
                            </li>
                        `);
                    });

                    updateSortOrderData();

                    if (!sortOrderList.hasClass('sortable-initialized')) {
                        Sortable.create(sortOrderList[0], {
                            animation: 150,
                            handle: '.bi-grip-vertical',
                            onEnd: updateSortOrderData
                        });
                        sortOrderList.addClass('sortable-initialized');
                    }
                } else {
                    sortOrderList.append(`<li class="list-group-item text-muted">@lang('No destinations currently shown on home')</li>`);
                    sortOrderData.val('[]');
                }
            }

            function updateSortOrderData() {
                const newOrder = [];
                sortOrderList.find('li[data-id]').each(function (index) {
                    newOrder.push({ id: $(this).data('id'), sort_order: index + 1 });
                });
                sortOrderData.val(JSON.stringify(newOrder));
            }

            function populateForm() {
                const selectedOption = destinationsSelect.selectedOptions[0];
                const showOnHome = selectedOption.dataset.home === "1" ? 1 : 0;
                const sectionType = selectedOption.dataset.homeSectionType || 0;

                if (showOnHomeSelect.tomselect) {
                    showOnHomeSelect.tomselect.setValue(showOnHome);
                } else {
                    showOnHomeSelect.value = showOnHome;
                }

                if (homeSectionTypeSelect.tomselect) {
                    homeSectionTypeSelect.tomselect.setValue(sectionType);
                } else {
                    homeSectionTypeSelect.value = sectionType;
                }

                homeSectionFields.style.display = showOnHome ? 'block' : 'none';
            }

            destinationsSelect.addEventListener('change', populateForm);

            $(showOnHomeSelect).on('change', function () {
                const value = $(this).val();
                const selectedOption = destinationsSelect.selectedOptions[0];
                const destinationId = selectedOption.value;
                const destinationName = selectedOption.textContent.trim();

                if (value == "1") {
                    homeSectionFields.style.display = 'block';

                    if (sortOrderList.find(`li[data-id="${destinationId}"]`).length === 0) {
                        sortOrderList.append(`
                    <li class="list-group-item d-flex align-items-center justify-content-between" data-id="${destinationId}">
                        <span>${destinationName}</span>
                        <i class="bi bi-grip-vertical text-muted cursor-pointer"></i>
                    </li>
                `);
                        homeSettings.push({ id: destinationId, title: destinationName });
                        updateSortOrderData();
                    }
                } else {
                    homeSectionFields.style.display = 'none';
                }
            });

            renderHomeDestinations();
            populateForm();
        });
    </script>
@endpush
