@extends(template() . 'layouts.app')
@section('title',trans('Payments'))
@section('content')
    <div class="booking-container container">
        <div class="row g-4 justify-content-center">
            <div class="col-xl-7 col-md-7">
                <div class="booking-form">
                    <div class="form-section" id="payment">
                        <div class="header-part d-flex align-items-center justify-content-between mb-3 flex-wrap gap-3">
                            <h3>@lang('Make Payment')</h3>
                            <a class="btn-3" href="{{ route('user.booking.guest.info', request()->uid) }}">
                                <div class="btn-wrapper">
                                    <div class="main-text btn-single">
                                        <i class="fas fa-users"></i>
                                        @lang('Guest Infos')
                                    </div>
                                    <div class="hover-text btn-single">
                                        <i class="fas fa-users"></i>
                                        @lang('Guest Infos')
                                    </div>
                                </div>
                            </a>
                        </div>

                        <div class="payment-information">
                            <form action="{{ route('user.booking.payment') }}" method="post">
                                @csrf

                                <input name="booking_uid" id="booking_uid" type="hidden" value="{{ $booking->uid }}"/>
                                <div class="payment-methods-scroll">
                                    <div class="payment-methods-list">
                                        <ul class="payment-container-list mt-0">
                                            @foreach($gateways ?? [] as $key => $method)
                                                <li class="item">
                                                    <input type="radio" class="form-check-input select-payment-method"
                                                           name="payout_method_id"
                                                           id="{{ $method->name }}"
                                                           value="{{ $method->id }}"
                                                           autocomplete="off"
                                                           {{ $loop->first ? 'checked' : '' }}
                                                    />
                                                    <label class="form-check-label" for="{{ $method->name }}">
                                                        <div class="image-area">
                                                            <img src="{{ getFile($method->driver, $method->image) }}" alt="">
                                                        </div>
                                                        <div class="content-area">
                                                            <h5>{{ $method->name }}</h5>
                                                            <span>{{ $method->description }}</span>
                                                        </div>
                                                    </label>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <div class="side-bar">

                                </div>

                                <div class="paymentModal">
                                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                         aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="modal-title" id="staticBackdropLabel">@lang('Payment')</h4>
                                                    <button type="button" class="close-btn text-white" data-bs-dismiss="modal" aria-label="Close">
                                                        <i class="fas fa-xmark"></i>
                                                    </button>
                                                </div>
                                                <div class="modal-body" id="paymentModalBody">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-5">
                <div class="booking-summary payment-page-product-image">
                    <img src="{{ getFile($property->photos->images['thumb']['driver'], $property->photos->images['thumb']['path']) }}" alt="{{ $property->title }}">
                    <div>
                        <a class="fw-semibold text-black mb-2">{!! $property->title !!}</a><br>
                        <div class="booking-summary-info d-flex align-items-center justify-content-between mb-2">
                            <span> <i class="far fa-star text-warning"></i> {{ number_format($property->reviewSummary?->average_rating, 1) }} ({{ $property->reviewSummary?->review_count ?? 0 }}) </span>
                            @if(getBadge($property) != 'Unknown')
                                <div class="badge bg-info">{{ getBadge($property) }}</div>
                            @endif
                        </div>
                    </div>

                    <p>{{ ($property->refundable == 1) ? $property->refund_message : 'Non Refundable After Booking'}}</p>
                    <strong>@lang('Booking Details')</strong>
                    <div class="trip-details">
                        <p id="booking-dates">{{ dateTime($booking->check_in_date) .' - '. dateTime($booking->check_out_date) }}</p>
                        @if (!empty($booking->information))
                            <div id="booking-guests" class="d-flex align-items-center gap-3 fw-semibold">
                                <span class="adult-result">{{ $booking->information['adults'] }} {{ Str::plural('adult', $booking->information['adults']) }}</span>
                                <span class="children-result">{{ $booking->information['children'] }} {{ Str::plural('child', $booking->information['children']) }}</span>
                                <span class="pets-status">{{ $booking->information['pets'] }} {{ Str::plural('pet', $booking->information['pets']) }}</span>
                            </div>
                        @endif
                        <button class="change-btn btn-3"><i class="far fa-pen"></i></button>
                    </div>

                    @php
                        use Carbon\Carbon;

                        $checkIn = Carbon::parse($booking->check_in_date);
                        $checkOut = Carbon::parse($booking->check_out_date);
                        $nights = $checkIn->diffInDays($checkOut);
                    @endphp

                    <strong>@lang('Price Details')</strong>
                    <div class="price-details">
                        <p class="night-count">{{ $nights }} @lang('nights')</p>
                        <p>
                            <strong class="total-amount">
                                @lang('Total'): {{ currencyPosition($booking->total_amount) }}
                            </strong>
                            @if($booking->amount_without_discount > $booking->total_amount)
                                <del class="amount-without-discount">{{ currencyPosition($booking->amount_without_discount) }}</del>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editBookingModal" tabindex="-1" role="dialog" aria-labelledby="editBookingModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="editBookingForm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Edit Booking Details')</h5>
                        <button type="button" class="close close-btn" data-bs-dismiss="modal" aria-label="@lang('Close')">
                            <span aria-hidden="true"><i class="far fa-xmark"></i></span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label class="form-label">@lang('Check-in Date')</label>
                            <input type="text" class="form-control" name="check_in_date" id="check_in_date" value="{{ date('Y-m-d', strtotime($booking->check_in_date)) }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">@lang('Check-out Date')</label>
                            <input type="text" class="form-control" name="check_out_date" id="check_out_date" value="{{ date('Y-m-d', strtotime($booking->check_out_date)) }}">
                        </div>
                        @php
                            $info = $booking->information ?? ['adults' => 1, 'children' => 0, 'pets' => 0];
                        @endphp

                        <div class="form-group">
                            <label class="form-label">@lang('Adults')</label>
                            <input type="number" name="adults" class="form-control" value="{{ $info['adults'] ?? 1 }}" min="1">
                        </div>

                        <div class="form-group">
                            <label class="form-label">@lang('Children')</label>
                            <input type="number" name="children" class="form-control" value="{{ $info['children'] ?? 0 }}" min="0">
                        </div>

                        <div class="form-group">
                            <label class="form-label">@lang('Pets')</label>
                            <input type="number" name="pets" class="form-control" value="{{ $info['pets'] ?? 0 }}" min="0">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">@lang('Update')</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .payment-page-product-image{
            aspect-ratio: 400 / 300;
        }
        .payment-page-product-image img{
            height: 100%;
        }
    </style>
@endpush

@push('style')
    <link rel="stylesheet" href="{{ asset(template(true) . "css/flatpickr.min.css") }}"/>
@endpush

@include(template().'frontend.services.partials.payment_script')
