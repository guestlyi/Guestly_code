@extends(template().'layouts.user')
@section('title',trans('Wishlists'))
@section('content')
    <section class="reservations-page">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('Wishlists')</li>
                    </ul>
                    <h4>@lang('Wishlists')</h4>
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


            <div class="reservations">
                <div class="listing-container">
                    <div class="shop-view-content">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="list-view-wrapper">
                                <div class="table-responsive d-flex flex-column-reverse">
                                    <table class="table table-striped align-middle">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">@lang('Property')</th>
                                                <th scope="col">@lang('Nighly Price')</th>
                                                <th scope="col">@lang('Location')</th>
                                                <th scope="col">@lang('Date')</th>
                                                <th scope="col">@lang('Action')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($wishlists as $key => $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td data-label="title">
                                                        <div class="listing-image-container">
                                                            <div class="listing-image">
                                                                @if($item->property?->photos && isset($item->property?->photos->images['thumb']))
                                                                    <img src="{{ getFile($item->property?->photos->images['thumb']['driver'], $item->property?->photos->images['thumb']['path']) }}" alt="{{ $item->property?->title ?? '' }}">
                                                                @else
                                                                    <img src="{{ asset(template(true).'img/no_image.png') }}" alt="@lang('No image available')">
                                                                @endif
                                                            </div>
                                                            <h6>{!! Str::limit($item->property?->title, 50) !!}</h6>
                                                        </div>
                                                    </td>
                                                    <td>{{ currencyPosition($item->property?->pricing?->nightly_rate) }}</td>
                                                    <td class="cursor-pointer" title="{{ $item->property?->address }}">{{ Str::limit($item->property?->address, 30) }}</td>
                                                    <td>{{ dateTime($item->created_at) }}</td>
                                                    <td data-label="Edit">
                                                        <div class="dropdown">
                                                            <a class="btn-3 other_btn" href="{{ route('service.details', $item->property?->slug) }}">
                                                                <div class="btn-wrapper">
                                                                    <div class="main-text btn-single">
                                                                        <i class="fa-regular fa-eye pe-1"></i> @lang('View')
                                                                    </div>
                                                                    <div class="hover-text btn-single">
                                                                        <i class="fa-regular fa-eye pe-1"></i> @lang('View')
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                @include('empty')
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                {{ $wishlists->appends(request()->query())->links(template().'partials.pagination') }}
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
            <h5 class="offcanvas-title" id="offcanvasRightLabel">@lang('Wishlist Filter')</h5>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('user.wishlists') }}" method="get">
                <div class="listing-offcanvas-form">

                    <div class="listing-offcanvas-search">
                        <label for="search">@lang('Title')</label>
                        <input
                            type="search"
                            class="form-control"
                            name="name"
                            id="name"
                            placeholder="e.g. Entire home in Khao Thong"
                            value="{{ request()->get('search') }}"
                        >
                    </div>

                    <div class="select-option-content">
                        <label for="wishlistDateFilter">@lang('Select Date')</label>
                        <input
                            type="text"
                            class="form-control"
                            name="datefilter"
                            id="wishlistDateFilter"
                            placeholder="12/12/2024 - 14/12/2024"
                            autocomplete="off"
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
        .cursor-pointer {
            cursor: pointer;
        }
    </style>
@endpush
@push('script')
    <script>
        $(document).ready(function() {
            $('#wishlistDateFilter').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY'
                }
            });

            $('#wishlistDateFilter').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(
                    picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY')
                );
            });

            $('#wishlistDateFilter').on('cancel.daterangepicker', function() {
                $(this).val('');
            });
        });
    </script>
@endpush

