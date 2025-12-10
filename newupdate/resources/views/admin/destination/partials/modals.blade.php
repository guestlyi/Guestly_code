<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="deleteModalLabel"><i class="bi bi-check2-square"></i> @lang("Confirmation")</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post" class="setRoute">
                @csrf
                @method("delete")
                <div class="modal-body">
                    <p>@lang("Do you want to delete this Destination")</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel"
     data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="statusModalLabel"><i
                        class="bi bi-check2-square"></i> @lang('Destination Status Confirmation')</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="get" class="setStatusRoute">
                @method('get')
                <div class="modal-body">
                    <p>@lang('Are you sure you want to change the status of this item? This action cannot be undone and will affect the current status of the item.')</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="DeleteMultipleModal" tabindex="-1" role="dialog" aria-labelledby="DeleteMultipleModalLabel" data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="DeleteMultipleModalLabel"><i
                        class="fa-light fa-square-check"></i> @lang('Confirmation')</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @csrf
                <div class="modal-body">
                    @lang('Do you want to delete all selected data?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary delete-multiple">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="statusMultipleModal" tabindex="-1" role="dialog" aria-labelledby="statusMultipleModalLabel" data-bs-backdrop="static"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="statusMultipleModalLabel"><i
                        class="fa-light fa-square-check"></i> @lang('Confirmation')</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" class="setInactiveRoute" method="post">
                @csrf
                <div class="modal-body">
                    @lang('Do you want to change status for all selected data?')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary status-multiple">@lang('Confirm')</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="setHomeModal" tabindex="-1" aria-labelledby="setHomeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-semibold" id="setHomeModalLabel">
                    <i class="bi bi-house-door me-1"></i> @lang('Set Home Section')
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" method="POST" class="setHomeRoute">
                @csrf
                @method('POST')

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="show_on_home" class="form-label">@lang('Show on Home Page')</label>
                        <select name="show_on_home" id="show_on_home" class="form-select">
                            <option value="1">@lang('Yes')</option>
                            <option value="0">@lang('No')</option>
                        </select>
                    </div>

                    <div id="homeSectionFields">
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

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Cancel')</button>
                    <button type="submit" class="btn btn-primary">@lang('Save Changes')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
@endpush


@push('js-lib')
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/select.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/appear.min.js') }}"></script>
    <script src="{{ asset("assets/admin/js/hs-counter.min.js") }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
@endpush
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script>
        $(document).on('ready', function () {
            HSCore.components.HSTomSelect.init('#show_on_home', {
                maxOptions: 250,
                placeholder: 'Select an option'
            });
            HSCore.components.HSTomSelect.init('#home_section_type', {
                maxOptions: 250,
                placeholder: 'Select an option'
            })

            function toggleHomeSectionFields() {
                if ($('#show_on_home').val() == "1") {
                    $('#homeSectionFields').removeClass('d-none');
                } else {
                    $('#homeSectionFields').addClass('d-none');
                }
            }

            $(document).on('click', '.setHomeBtn', function () {
                let route = $(this).data('route');
                let showOnHome = parseInt($(this).data('show_on_home'));
                let homeSectionType = $(this).data('home_section_type');
                let homeDestinations = $(this).data('home_destinations');
                let currentDestinationId = $(this).data('destination_id');
                let currentDestinationTitle = $(this).data('destination_title');

                if (typeof homeDestinations === 'string') {
                    try { homeDestinations = JSON.parse(homeDestinations); } catch(e) { homeDestinations = []; }
                }

                $('.setHomeRoute').attr('action', route);

                const showOnHomeSelect = document.querySelector('#show_on_home').tomselect;
                if (showOnHomeSelect) {
                    showOnHomeSelect.setValue(showOnHome);
                }

                toggleHomeSectionFields();

                const homeSectionTypeSelection = document.querySelector('#home_section_type').tomselect;
                if (homeSectionTypeSelection) {
                    homeSectionTypeSelection.setValue(homeSectionType);
                }
                toggleHomeSectionFields();

                const list = $('#sortOrderList');
                list.empty();

                let allDestinations = Array.isArray(homeDestinations) ? [...homeDestinations] : [];

                const exists = allDestinations.some(dest => dest.id == currentDestinationId);

                if (!exists) {
                    allDestinations.push({ id: currentDestinationId, title: currentDestinationTitle });
                }

                allDestinations.forEach((dest, index) => {
                    list.append(`
                        <li class="list-group-item d-flex align-items-center justify-content-between" data-id="${dest.id}">
                            <span>${dest.title}</span>
                            <i class="bi bi-grip-vertical text-muted cursor-pointer"></i>
                        </li>
                    `);
                });

                const orderData = allDestinations.map((dest, index) => ({
                    id: dest.id,
                    sort_order: index + 1
                }));
                $('#sort_order_data').val(JSON.stringify(orderData));

                if (list.length && !list.hasClass('sortable-initialized')) {
                    Sortable.create(list[0], {
                        animation: 150,
                        handle: '.bi-grip-vertical',
                        onEnd: function () {
                            const order = [];
                            list.find('li[data-id]').each(function(index) {
                                order.push({ id: $(this).data('id'), sort_order: index + 1 });
                            });
                            $('#sort_order_data').val(JSON.stringify(order));
                        }
                    });
                    list.addClass('sortable-initialized');
                }
            });

            $('#show_on_home').on('change', function () {
                toggleHomeSectionFields();
            });

            $(document).on('click', '.statusBtn', function () {
                let route = $(this).data('route');
                $('.setStatusRoute').attr('action', route);
            })

            $(document).on('click', '.deleteBtn', function () {
                let route = $(this).data('route');
                $('.setRoute').attr('action', route);
            })

            $(document).on('click', '.delete-multiple', function (e) {
                e.preventDefault();
                let all_value = [];
                $(".row-tic:checked").each(function () {
                    all_value.push($(this).attr('data-id'));
                });
                let strIds = all_value;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('admin.destination.delete.multiple') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        location.reload();
                    },
                });
            });
            $(document).on('click', '.status-multiple', function (e) {
                e.preventDefault();
                let all_value = [];
                $(".row-tic:checked").each(function () {
                    all_value.push($(this).attr('data-id'));
                });
                let strIds = all_value;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: "{{ route('admin.destination.statusMultiple') }}",
                    data: {strIds: strIds},
                    datatType: 'json',
                    type: "post",
                    success: function (data) {
                        location.reload();
                    },
                });
            });
        });
    </script>
@endpush
