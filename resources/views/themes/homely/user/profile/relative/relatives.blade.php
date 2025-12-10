@extends(template().'layouts.user')
@section('title',trans('Relatives'))
@section('content')
    <section class="listing">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('Guests')</li>
                    </ul>
                    <h4>@lang('Guests')</h4>
                </div>
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <a class="btn-3 other_btn" type="button" href="{{ route('user.relative.add') }}">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                <i class="fas fa-plus-circle"></i>@lang('Add New')
                            </div>
                            <div class="hover-text btn-single">
                                <i class="fas fa-plus-circle"></i>@lang('Add New')
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
                                <path fill="none" d="M7 16H3m26 0H15M29 6h-4m-8 0H3m26 20h-4M7 16a4 4 0 1 0 8 0 4 4 0 0 0-8 0zM17 6a4 4 0 1 0 8 0 4 4 0 0 0-8 0zm0 20a4 4 0 1 0 8 0 4 4 0 0 0-8 0zm0 0H3"></path>
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
                                        <th scope="col">@lang('Relative')</th>
                                        <th scope="col">@lang('Type')</th>
                                        <th scope="col">@lang('Gender')</th>
                                        <th scope="col">@lang('Date Of Birth')</th>
                                        <th scope="col">@lang('Country')</th>
                                        <th scope="col">@lang('Action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($relatives as $item)
                                        <tr>
                                            <td data-label="relative-info">
                                                <div class="relative-cell">
                                                    <div class="relative-img">
                                                        <img src="{{ $item['image'] ? getFile($item['image']['driver'], $item['image']['path']) : asset(template(true).'img/no_image.png') }}" alt="Relative Photo">
                                                    </div>
                                                    <div class="relative-data">
                                                        <h5>{{ $item['firstname'] }} {{ $item['lastname'] }}</h5>
                                                        @if(isset($item['email']))
                                                            <p><strong>Email:</strong> {{ $item['email'] }}</p>
                                                        @endif
                                                        @if(isset($item['phone']))
                                                            <p><strong>Phone:</strong> {{ $item['phone'] }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td data-label="type">
                                                @if($item['type'] === 'adult')
                                                    <span class="badge bg-info-subtle text-info">@lang('adult')</span>
                                                @elseif($item['type'] === 'children')
                                                    <span class="badge bg-success-subtle text-success">@lang('child')</span>
                                                @endif
                                            </td>
                                            <td data-label="gender">
                                                @if($item['gender'] === 'male')
                                                    <span class="badge bg-primary-subtle text-primary">@lang('male')</span>
                                                @elseif($item['gender'] === 'female')
                                                    <span class="badge bg-secondary-subtle text-secondary">@lang('female')</span>
                                                @endif
                                            </td>
                                            <td data-label="Age">
                                                <span class="age-badge">{{ $item['birth_date'] }}</span>
                                            </td>
                                            <td data-label="country" class="text-center">
                                                <span class="bg-body-subtle text-body">{{ $item['country'] }}</span>
                                            </td>
                                            <td data-label="Action">
                                                <div class="dropdown">
                                                    <button class="action-btn-secondary" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="fa-regular fa-ellipsis-stroke-vertical"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <a class="dropdown-item edit-tax-btn" href="{{ route('user.relative.edit', [$item['type'], $item['serial']]) }}">@lang('Edit')</a>
                                                        <li>
                                                            <a class="dropdown-item"
                                                               href="#"
                                                               data-route="{{ route('user.relative.delete') }}"
                                                               data-type="{{ $item['type'] }}"
                                                               data-serial="{{ $item['serial'] }}"
                                                               data-bs-target="#deleteRelativeModal"
                                                               data-bs-toggle="modal"
                                                            >
                                                                @lang('Remove')
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
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
            <h5 class="offcanvas-title" id="offcanvasRightLabel">@lang('Guest Filter')</h5>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('user.relatives') }}" method="get">
                <div class="listing-offcanvas-form">
                    <div class="listing-offcanvas-search">
                        <label for="search">@lang('Name')</label>
                        <input
                            type="search"
                            class="form-control"
                            name="name"
                            id="search"
                            placeholder="e.g. john doe"
                            value="{{ request()->get('name') }}"
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

    <div class="modal fade" id="deleteRelativeModal" tabindex="-1" aria-labelledby="deleteRelativeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteRelatives" method="POST" action="">
                @csrf
                @method('DELETE')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteRelativeModalLabel">@lang('Confirm Deletion')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
                    </div>
                    <div class="modal-body">
                        @lang('Are you sure you want to remove this Relative?')
                        <input type="hidden" name="type" id="type" value="">
                        <input type="hidden" name="serial" id="serial" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-danger">@lang('Delete')</button>
                    </div>
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
        .relativeImageUser{
            max-width: 100px;
            border-radius: 8px;
            height: 70px;
        }

        .relative-cell {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px 0;
        }

        .relative-img img {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
        }

        .relative-data {
            display: flex;
            flex-direction: column;
            font-size: 0.95rem;
            color: #333;
        }

        .relative-data h5 {
            margin: 0;
            font-size: 1rem;
            font-weight: 600;
            color: #222;
        }

        .relative-data p {
            margin: 2px 0;
            font-size: 0.9rem;
            color: #555;
        }
        .age-badge {
            display: inline-block;
            padding: 4px 10px;
            background-color: #f0f0f0;
            color: #333;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
        }

    </style>
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            $('.cmn-select2').select2({
                placeholder: "Select Tax Type",
                width: '100%'
            });
        });

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

        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('[data-bs-target="#deleteRelativeModal"]');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const route = this.getAttribute('data-route');
                    const type = this.getAttribute('data-type');
                    const serial = this.getAttribute('data-serial');

                    document.getElementById('deleteRelatives').setAttribute('action', route);

                    document.getElementById('type').value = type;
                    document.getElementById('serial').value = serial;
                });
            });
        });
    </script>
@endpush
