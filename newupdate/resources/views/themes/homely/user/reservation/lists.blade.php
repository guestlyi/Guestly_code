@extends(template().'layouts.user')
@section('title',trans('Reservations'))
@section('content')
    <section class="reservations-page">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('Reservations')</li>
                    </ul>
                    <h4>@lang('Reservations')</h4>
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
                <nav>
                    @php
                        $active = request('filter');
                    @endphp

                    <div class="nav nav-tabs reservation-nav" id="nav-tab" role="tablist">
                        <a class="nav-link {{ $active == 'upcoming' || $active === null ? 'active' : '' }}" href="{{ route('user.reservations', ['filter' => 'upcoming']) }}">@lang('Upcoming')</a>
                        <a class="nav-link {{ $active == 'completed' ? 'active' : '' }}" href="{{ route('user.reservations', ['filter' => 'completed']) }}">@lang('Completed')</a>
                        <a class="nav-link {{ $active == 'canceled' ? 'active' : '' }}" href="{{ route('user.reservations', ['filter' => 'canceled']) }}">@lang('Canceled')</a>
                        <a class="nav-link {{ $active == 'all' ? 'active' : '' }}" href="{{ route('user.reservations', ['filter' => 'all']) }}">@lang('All')</a>
                    </div>
                </nav>
                <div class="listing-container">
                    <div class="shop-view-content">
                        <div class="tab-content" id="nav-tabContent">
                            <div class="list-view-wrapper">
                                <div class="table-responsive d-flex flex-column-reverse">
                                    <table class="table table-striped align-middle">
                                        <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">@lang('Title')</th>
                                            @if(auth()->user()->role == 1)
                                                <th scope="col">@lang('Guest')</th>
                                            @endif
                                            <th scope="col">@lang('Check-in')</th>
                                            <th scope="col">@lang('Check-out')</th>
                                            <th scope="col">@lang('Amount')</th>
                                            <th scope="col">@lang('Status')</th>
                                            <th scope="col">@lang('Action')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($bookings as $key => $booking)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td data-label="title">
                                                        <div class="listing-image-container">
                                                            <a href="{{ route('service.details', $booking->property?->slug) }}" target="_blank"><i class="fas fa-level-up-alt"></i></a>
                                                            <h6>{!! Str::limit($booking->property?->title, 30, '...') !!}</h6>
                                                        </div>
                                                    </td>

                                                    @if(auth()->user()->role == 1)
                                                        <td data-label="Guest">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <img src="{{ getFile($booking->guest?->image_driver, $booking->guest?->image) }}"
                                                                     alt="Guest Image"
                                                                     class="rounded-circle"
                                                                     width="32" height="32">
                                                                <span>{{ $booking->guest?->firstname.' '.$booking->guest?->lastname ?? 'N/A' }}</span>
                                                            </div>
                                                        </td>
                                                    @endif

                                                    <td>{{ \Carbon\Carbon::parse($booking->check_in_date)->format('M d, Y') }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($booking->check_out_date)->format('M d, Y') }}</td>
                                                    <td>{{ currencyPosition($booking->total_amount) }}</td>

                                                    <td>
                                                        @switch($booking->status)
                                                            @case(1) <span class="badge bg-soft-info text-info">@lang('Confirmed')</span> @break
                                                            @case(2) <span class="badge bg-soft-danger text-danger">@lang('Canceled')</span> @break
                                                            @case(3) <span class="badge bg-soft-success text-success">@lang('Completed')</span> @break
                                                            @case(4) <span class="badge bg-soft-primary text-primary">@lang('Paid')</span> @break
                                                            @case(5) <span class="badge bg-soft-warning text-warning">@lang('Refunded')</span> @break
                                                            @default <span class="badge bg-soft-secondary text-secondary">@lang('Pending')</span>
                                                        @endswitch
                                                    </td>

                                                    <td data-label="Edit">
                                                        <div class="dropdown">
                                                            <button class="action-btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="fa-regular fa-ellipsis-stroke-vertical"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                @if(auth()->user()->role == 1)
                                                                    @if($booking->status == 4)
                                                                        <li>
                                                                            <a class="dropdown-item confirmReservation" href="#"
                                                                               data-booking='@json($booking)'
                                                                               data-bs-toggle="modal"
                                                                               data-bs-target="#confirmReservation">
                                                                                @lang('Confirm')
                                                                            </a>
                                                                        </li>
                                                                    @endif

                                                                    @if($booking->status == 1)
                                                                        <li>
                                                                            <a class="dropdown-item completeReservation" href="#"
                                                                               data-booking='@json($booking)'
                                                                               data-bs-toggle="modal"
                                                                               data-bs-target="#completedReservation">
                                                                                @lang('Completed')
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <a class="dropdown-item refundedReservation" href="#"
                                                                               data-booking='@json($booking)'
                                                                               data-bs-toggle="modal"
                                                                               data-bs-target="#refundedReservation">
                                                                                @lang('Refunded')
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                <li>
                                                                    <a class="dropdown-item details" href="#"
                                                                       data-uid="{{ $booking->uid }}"
                                                                       data-check_in_date="{{ dateTime($booking->check_in_date) }}"
                                                                       data-check_out_date="{{ dateTime($booking->check_out_date) }}"
                                                                       data-information='@json($booking->information)'
                                                                       data-user_info='@json($booking->user_info)'
                                                                       data-total_amount="{{ currencyPosition($booking->total_amount) }}"
                                                                       data-amount_without_discount="{{ currencyPosition($booking->amount_without_discount) }}"
                                                                       data-discount_amount="{{ currencyPosition($booking->discount_amount) }}"
                                                                       data-applied_discount="@json($booking->applied_discount)"
                                                                       data-site_charge="{{ currencyPosition($booking->site_charge) }}"
                                                                       data-host_received="{{ currencyPosition($booking->host_received) }}"
                                                                       data-status="{{ $booking->status }}"
                                                                       data-property_title="{{ optional($booking->property)->title }}"
                                                                       data-bs-toggle="modal"
                                                                       data-bs-target="#bookingDetailsModal"
                                                                       >
                                                                        @lang('Details')
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item" href="{{ route('user.messages', ['booking_uid' => $booking->uid]) }}">
                                                                        @lang('Conversation')
                                                                    </a>
                                                                </li>
                                                                @php
                                                                    $canReview = $booking->status == 3
                                                                        || ($booking->status == 1 && $booking->check_in_date <= now());
                                                                @endphp

                                                                @if($canReview && $booking->property?->slug)
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('service.details', $booking->property->slug) }}">
                                                                            @lang('Give Review')
                                                                        </a>
                                                                    </li>
                                                                @endif
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
                                {{ $bookings->appends(request()->query())->links(template().'partials.pagination') }}
                            </div>
                        </div>
                    </div>
                </div>
                <p class="text-center mt-5">@lang('How can we make it easier to manage your reservations?') <a href="#0" data-bs-target="#sendFeedback" data-bs-toggle="modal"> @lang('Share your feedback')</a></p>
            </div>
        </div>
    </section>

    @include(template().'user.reservation.partials.modals')
@endsection

@include(template().'user.reservation.partials.styles')
@include(template().'user.reservation.partials.scripts')

