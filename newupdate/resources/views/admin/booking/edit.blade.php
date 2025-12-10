@extends('admin.layouts.app')
@section('page_title',__('Edit Booking'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end justify-content-between">
                <div class="col-md-6 col-sm mb-2 mb-sm-0 w-auto">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Booking Manage')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Booking Edit')</h1>
                    <p class="mb-0">{{ 'Booking range: '.dateTime($booking->check_in_date) .' To '.dateTime($booking->check_out_date)}}</p>
                </div>
                <div class="col-md-6">
                    <div class="d-flex justify-content-end align-items-center gap-2">

                        @if($booking->status == 5 && $booking->date > now())
                            <a class="approveButton text-light btn btn-primary btn-sm" href="javascript:void(0)"
                               data-route="{{ route('admin.booking.approve', $booking->id) }}"
                               data-amount="{{ currencyPosition($booking->total_price) }}"
                               data-id="{{ $booking->id }}"
                               data-trx_id = " {{ $booking->trx_id }} "
                               data-paid_date = " {{ dateTime($booking->created_at) }} "
                               data-bs-toggle="modal"
                               data-bs-target="#approveModal"
                            >
                                <i class="bi bi-check-circle"></i>
                                @lang('Approve Tour')
                            </a>
                        @endif
                        @if($booking->status == 1)
                            <a class="refundBtn text-light btn btn-info btn-sm" href="javascript:void(0)"
                               data-route="{{ route('admin.booking.refund', $booking->id) }}"
                               data-bs-toggle="modal" data-bs-target="#refundModal">
                                <i class="bi bi-arrow-up-circle"></i>
                                @lang('Make it Refund')
                            </a>
                            <a class="actionBtn text-light btn btn-success btn-sm" href="javascript:void(0)"
                               data-action="{{ route('admin.booking.action', $booking->id) }}"
                               data-bs-toggle="modal"
                               data-bs-target="#Confirm"
                               data-amount="{{ currencyPosition($booking->total_price) }}"
                               data-id="{{ $booking->id }}"
                               data-trx_id = "{{ $booking->trx_id }}"
                               data-paid_date = " {{ dateTime($booking->created_at) }}"
                            >
                                <i class="bi bi-check-square"></i>
                                @lang('Make it Completed')
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center w-100">
            <div class="col-lg-4">
                <div class="card mb-3 mb-lg-5">
                    <div class="card-header card-header-content-between">
                        <div class="userHeader d-flex justify-content-start align-items-center gap-2">
                            <img class="bookingUserImage" src="{{ getFile($booking->guest->image_driver, $booking->guest->image) }}">
                            <h4 class="card-header-title">{{ optional($booking->guest)->firstname.' '.optional($booking->guest)->lastname }}</h4>
                        </div>
                        <a class="btn btn-white btn-sm"
                           href="{{ route('admin.user.view.profile', optional($booking->guest)->id) }}"><i
                                class="bi-eye me-1"></i> @lang("Profile")</a>
                    </div>

                    <div class="card-body">
                        <ul class="list-unstyled list-py-2 text-dark mb-0">
                            <li class="pb-0"><span class="card-subtitle">@lang("About")</span></li>
                            <li>
                                <i class="bi-person dropdown-item-icon"></i>  {{ optional($booking->guest)->firstname.' '.optional($booking->guest)->lastname }}
                            </li>
                            <li>
                                <i class="bi-briefcase dropdown-item-icon"></i> {{ optional($booking->guest)->email }}
                            </li>
                            <li class="text-success">
                                <a href="{{ route('admin.user.booking', optional($booking->guest)->id) }}"><i class="bi-list dropdown-item-icon"></i> {{ optional($booking->guest)->active_booking_count .' Previous Booking' }}</a>
                            </li>

                            <li class="pt-4 pb-0"><span class="card-subtitle">@lang("Contacts")</span></li>
                            <li><i class="bi-phone dropdown-item-icon"></i>{{optional($booking->guest)->phone_code . optional($booking->guest)->phone }} </li>

                            @if(optional($booking->guest)->country)
                                <li class="pt-4 pb-0"><span class="card-subtitle">@lang("Address")</span></li>
                                <li class="fs-6 text-body">
                                    <i class="bi bi-geo-alt dropdown-item-icon"></i> @lang(optional($booking->guest)->country)
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>

                <div class="card mb-3 mb-lg-5">
                    <div class="card-header card-header-content-between">
                        <div class="userHeader d-flex justify-content-start align-items-center gap-2">
                            <h4 class="card-header-title">@lang('Payment Information')</h4>
                        </div>
                    </div>

                    <div class="card-body">
                        <ul class="list-unstyled list-py-2 text-dark mb-0">
                            <li class="pb-0"><span class="card-subtitle">@lang("Transaction Details")</span></li>
                            <li class="d-flex justify-content-between align-items-center">
                                <span>@lang('Booking ID : ')</span>  <span>{{ $booking->uid }}</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center text-danger">
                                <span>@lang('Charge In Payment Currency: ')</span>  <span>{{getAmount($booking->depositable?->fixed_charge + $booking->depositable?->percentage_charge)}} {{$booking->depositable?->payment_method_currency}}</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <span>@lang('Paid In Payment Currency : ')</span>  <span>{{getAmount($booking->depositable?->payable_amount)}} {{$booking->depositable?->payment_method_currency}}</span>
                            </li>
                            <li class="d-flex justify-content-between align-items-center">
                                <span>@lang('Paid In Base : ')</span>  <span>{{ currencyPosition(getAmount($booking->depositable?->base_currency_charge + $booking->depositable?->payable_amount_in_base_currency)) }}</span>
                            </li>

                            <li class="pt-4 pb-0"><span class="card-subtitle">@lang("Coupon Information")</span></li>

                            <li class="d-flex justify-content-between align-items-center"><span>@lang('Amount : ')</span><span>{{currencyPosition($booking->amount_without_discount)}}</span></li>
                            <li class="d-flex justify-content-between align-items-center"><span>@lang('Coupon Apply : ')</span><span>@lang($booking->coupon == 1 ? 'Yes' : 'No')</span></li>
                            <li class="d-flex justify-content-between align-items-center"><span>@lang('Coupon : ')</span><span>{{ $booking->cupon_number ?? 'N/A' }}</span></li>
                            <li class="d-flex justify-content-between align-items-center text-danger"><span>@lang('Discount Amount : ')</span><span>{{ currencyPosition($booking->discount_amount) ?? 0 }}</span></li>
                            <li class="d-flex justify-content-between align-items-center"><h5>@lang('Final Amount : ')</h5><h5>{{ currencyPosition($booking->amount_without_discount - $booking->discount_amount) ?? 0 }}</h5></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-8 mb-3 mb-lg-0">
                <div class="card mb-3 mb-lg-5">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div class="d-flex justify-content-start align-items-center gap-2">
                            <h4 class="card-header-title">@lang('Booking Information')</h4>

                            @if($booking->status == 1 && $booking->check_in_date > now() && $booking->check_out_date > now())
                                <span class="badge bg-soft-warning text-warning">
                                    <span class="legend-indicator bg-warning"></span>
                                    @lang('Pending')
                                </span>
                            @elseif($booking->status == 0 && $booking->check_in_date > now())
                                <span class="badge bg-soft-danger text-danger">
                                    <span class="legend-indicator bg-danger"></span>
                                    @lang('Payment Pending')
                                </span>
                            @elseif($booking->status == 2)
                                <span class="badge bg-soft-success text-success">
                                    <span class="legend-indicator bg-success"></span>
                                    @lang('Completed')
                                </span>
                            @elseif($booking->status == 4 && $booking->check_in_date > now())
                                <span class="badge bg-soft-secondary text-secondary">
                                    <span class="legend-indicator bg-secondary"></span>
                                    @lang('Refunded')
                                </span>
                            @elseif ($booking->check_out_date < now())
                                <span class="badge bg-soft-danger text-danger">
                                    <span class="legend-indicator bg-danger"></span>
                                    @lang('Expired')
                                </span>
                            @elseif ($booking->check_in_date < now() && $booking->check_out_date > now())
                                <span class="badge bg-soft-primary text-primary">
                                    <span class="legend-indicator bg-primary"></span>
                                    @lang('Running')
                                </span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-start align-items-center gap-2">
                            <a type="button" href="{{ route('admin.all.booking') }}" class="btn btn-info btn-sm float-end">
                                <i class="bi bi-arrow-left"></i>@lang('Back')
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.booking.update') }}" method="post" enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="uid" value="{{ $booking->uid }}" />
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mb-4">
                                                <label for="nameLabel" class="form-label">@lang('Name') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type name here..."></i></label>
                                                <input type="text" class="form-control" name="name" id="nameLabel" placeholder="e.g dhaka" aria-label="name" value="{{ old('name', $booking->property?->title) }}" {{ ($booking->status == 1 && $booking->check_out_date > now()) ? '' : 'disabled' }}>
                                                @error('name')
                                                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="mb-4">
                                                <label class="form-label" for="TotalPriceLabel">@lang('Total Price')
                                                    <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Total Paid Amount"></i></label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control wid1" name="total_price" id="TotalPriceLabel" value="{{ old('total_price', $booking->total_amount)  }}" placeholder="e.g 500" aria-label="price" {{ ($booking->status == 1 && $booking->check_out_date > now()) ? '' : 'disabled' }}>
                                                    <h5 class="form-control wid2 mb-0">{{ basicControl()->base_currency }}</h5>
                                                </div>
                                                @error('total_price')
                                                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mb-4">
                                                <label class="form-label" for="check-in-date">@lang('Check In Date')</label>
                                                <input type="text" name="check_in_date" id="check-in-date" class="form-control date-picker2" placeholder="@lang('Select a Date')" value="{{ $booking->check_in_date }}" {{ ($booking->status == 1 && $booking->check_out_date > now()) ? '' : 'disabled' }}>
                                                @error('check_in_date')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mb-4">
                                                <label class="form-label" for="check-out-date">@lang('Check Out Date')</label>
                                                <input type="text" name="check_out_date" id="check-in-date" class="form-control date-picker2" placeholder="@lang('Select a Date')" value="{{ $booking->check_out_date }}" {{ ($booking->status == 1 && $booking->check_out_date > now()) ? '' : 'disabled' }}>
                                                @error('check_out_date')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mb-4">
                                                <label class="form-label" for="statrtPoint">@lang('Booking Created Date')</label>
                                                <input type="text" class="form-control date-picker2" placeholder="@lang('Created At')" value="{{ $booking->created_at }}" disabled>
                                                @error('startPoint')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mb-4">
                                                <label class="form-label" for="totalAdult">@lang('Total Adult')</label>
                                                <input type="text" name="total_adult" class="form-control" placeholder="e.g. 5" id="totalAdult" value="{{ old('total_adult', $booking->information['adults']) }}" {{ ($booking->status == 1 && $booking->check_out_date > now()) ? '' : 'disabled' }}>
                                                @error('total_adult')
                                                    <span class="invalid-feedback d-block" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mb-4">
                                                <label class="form-label" for="totalChild">@lang('Total Child')</label>
                                                <input type="text" name="total_children" class="form-control" placeholder="e.g. 5" id="totalChild" value="{{ old('total_children', $booking->information['children']) }}" {{ ($booking->status == 1 && $booking->check_out_date > now()) ? '' : 'disabled' }}>
                                                @error('total_child')
                                                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-lg-6 col-md-6">
                                            <div class="mb-4">
                                                <label class="form-label" for="totalPets">@lang('Total Pets')</label>
                                                <input type="text" name="total_pets" class="form-control" placeholder="e.g. 5" id="totalPets" value="{{ old('total_pets', $booking->information['pets']) }}" {{ ($booking->status == 1 && $booking->check_out_date > now()) ? '' : 'disabled' }}>
                                                @error('total_infant')
                                                <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    @if($booking->status == 1 && $booking->check_out_date > now())
                                        <button type="submit" class="btn btn-primary">@lang("Save Changes")</button>
                                    @endif
                                </form>
                            </div>
                        </div>
                        @php
                            $userInfo = $booking->user_info;
                        @endphp

                        <div class="card mt-2">
                            <div class="card-header">
                                <h3>@lang('Guest Information')</h3>
                            </div>
                            <div class="card-body userInformation">
                                <input type="hidden" class="bookingUid" value="{{ $booking->uid }}">

                                @foreach ($userInfo['adult'] ?? [] as $index => $adult)
                                    <h5 class="mt-3">@lang('Adult') {{ $index + 1 }}</h5>
                                    <div class="row">
                                        <div class="form-group col-md-6 mb-2">
                                            <label>@lang('First Name')</label>
                                            <input type="text" class="form-control" name="user_info[adult][{{ $index }}][firstname]" value="{{ $adult['firstname'] ?? '' }}">
                                        </div>
                                        <div class="form-group col-md-6 mb-2">
                                            <label>@lang('Last Name')</label>
                                            <input type="text" class="form-control" name="user_info[adult][{{ $index }}][lastname]" value="{{ $adult['lastname'] ?? '' }}">
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <label>@lang('Gender')</label>
                                            <select name="user_info[adult][{{ $index }}][gender]" class="form-control">
                                                <option value="male" @selected($adult['gender'] == 'male')>@lang('Male')</option>
                                                <option value="female" @selected($adult['gender'] == 'female')>@lang('Female')</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <label>@lang('Age')</label>
                                            <input type="number" class="form-control" name="user_info[adult][{{ $index }}][age]" value="{{ $adult['age'] ?? '' }}">
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <label>@lang('Country')</label>
                                            <input type="text" class="form-control" name="user_info[adult][{{ $index }}][country]" value="{{ $adult['country'] ?? '' }}">
                                        </div>
                                        <div class="form-group col-md-6 mb-2">
                                            <label>@lang('Email')</label>
                                            <input type="email" class="form-control" name="user_info[adult][{{ $index }}][email]" value="{{ $adult['email'] ?? '' }}">
                                        </div>
                                        <div class="form-group col-md-6 mb-2">
                                            <label>@lang('Phone')</label>
                                            <input type="text" class="form-control" name="user_info[adult][{{ $index }}][phone]" value="{{ $adult['phone'] ?? '' }}">
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label class="pb-1 d-block">@lang('Image')</label>
                                            <label class="form-check form-check-dashed" for="adultPhotoUploader{{ $index }}">
                                                <img id="adultPreviewLight{{ $index }}"
                                                     class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                     src="{{ getFile($adult['image']['driver'], $adult['image']['path'], true) }}"
                                                     alt="Image" data-hs-theme-appearance="default">

                                                <img id="adultPreviewDark{{ $index }}"
                                                     class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                     src="{{ getFile($adult['image']['driver'], $adult['image']['path'], true) }}"
                                                     alt="Image" data-hs-theme-appearance="dark">

                                                <span class="d-block">@lang("Browse your file here")</span>

                                                <input type="file"
                                                       class="js-file-attach form-check-input"
                                                       name="user_info[adult][{{ $index }}][photo]"
                                                       id="adultPhotoUploader{{ $index }}"
                                                       data-hs-file-attach-options='{
                                                           "textTarget": "#adultPreviewLight{{ $index }}",
                                                           "mode": "image",
                                                           "targetAttr": "src",
                                                           "allowTypes": [".png", ".jpeg", ".jpg"]
                                                       }'>
                                            </label>

                                            @error("user_info.adult.$index.photo")
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach

                                @foreach ($userInfo['children'] ?? [] as $index => $child)
                                    <h5 class="mt-4">@lang('Child') {{ $index + 1 }}</h5>
                                    <div class="row">
                                        <div class="form-group col-md-6 mb-2">
                                            <label>@lang('First Name')</label>
                                            <input type="text" class="form-control" name="user_info[children][{{ $index }}][firstname]" value="{{ $child['firstname'] ?? '' }}">
                                        </div>
                                        <div class="form-group col-md-6 mb-2">
                                            <label>@lang('Last Name')</label>
                                            <input type="text" class="form-control" name="user_info[children][{{ $index }}][lastname]" value="{{ $child['lastname'] ?? '' }}">
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <label>@lang('Gender')</label>
                                            <select name="user_info[children][{{ $index }}][gender]" class="form-control">
                                                <option value="male" @selected($child['gender'] == 'male')>@lang('Male')</option>
                                                <option value="female" @selected($child['gender'] == 'female')>@lang('Female')</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <label>@lang('Age')</label>
                                            <input type="number" class="form-control" name="user_info[children][{{ $index }}][age]" value="{{ $child['age'] ?? '' }}">
                                        </div>
                                        <div class="form-group col-md-4 mb-2">
                                            <label>@lang('Country')</label>
                                            <input type="text" class="form-control" name="user_info[children][{{ $index }}][country]" value="{{ $child['country'] ?? '' }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="pb-1 d-block">@lang('Image')</label>
                                            <label class="form-check form-check-dashed" for="childPhotoUploader{{ $index }}">
                                                <img id="childPreviewLight{{ $index }}"
                                                     class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                     src="{{ getFile($child['image']['driver'], $child['image']['path'], true) }}"
                                                     alt="Image" data-hs-theme-appearance="default">

                                                <img id="childPreviewDark{{ $index }}"
                                                     class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                     src="{{ getFile($child['image']['driver'], $child['image']['path'], true) }}"
                                                     alt="Image" data-hs-theme-appearance="dark">

                                                <span class="d-block">@lang("Browse your file here")</span>

                                                <input type="file"
                                                       class="js-file-attach form-check-input"
                                                       name="user_info[children][{{ $index }}][photo]"
                                                       id="childPhotoUploader{{ $index }}"
                                                       data-hs-file-attach-options='{
                                                           "textTarget": "#childPreviewLight{{ $index }}",
                                                           "mode": "image",
                                                           "targetAttr": "src",
                                                           "allowTypes": [".png", ".jpeg", ".jpg"]
                                                       }'>
                                            </label>

                                            @error("user_info.children.$index.photo")
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                @endforeach

                                <div class="form-group mt-4">
                                    <button type="button" class="btn btn-primary userInformationUpdateBtn">@lang('Update')</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.booking.partials.modal')

@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">
@endpush

@push('script')
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let today = new Date();
            today.setDate(today.getDate() - 1);
            let maxDate = today.toISOString().split('T')[0];

            flatpickr('.date-picker', {
                enableTime: false,
                dateFormat: "Y-m-d",
                maxDate: maxDate
            });
            flatpickr('.date-picker2', {
                enableTime: false,
                dateFormat: "Y-m-d"
            });
        });
        $(document).on('click', '.userInformationUpdateBtn', function () {
            const container = $('.userInformation');
            const bookingUid = container.find('.bookingUid').val();
            const formData = new FormData();

            formData.append('_token', '{{ csrf_token() }}');
            formData.append('booking_uid', bookingUid);

            container.find('input, select, textarea').each(function () {
                const input = $(this);
                const name = input.attr('name');
                if (!name) return;

                if (input.attr('type') === 'file') {
                    const files = input[0].files;
                    if (files.length > 0) {
                        formData.append(name, files[0]);
                    }
                } else {
                    formData.append(name, input.val());
                }
            });

            $.ajax({
                url: '{{ route('admin.booking.user.info.update') }}',
                method: 'POST',
                processData: false,
                contentType: false,
                data: formData,
                success: function (res) {
                    if (res.status === 'success') {
                        Notiflix.Notify.success(res.message);
                    } else {
                        Notiflix.Notify.failure(res.error || 'Update failed!');
                    }
                },
                error: function (xhr) {
                    let msg = 'An error occurred';
                    if (xhr.responseJSON?.message) msg = xhr.responseJSON.message;
                    Notiflix.Notify.failure(msg);
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[type="file"][name^="user_info[adult]"], input[type="file"][name^="user_info[children]"]').forEach(function(input) {
                input.addEventListener('change', function (e) {
                    const file = e.target.files[0];
                    if (!file || !file.type.startsWith('image/')) return;

                    const reader = new FileReader();
                    reader.onload = function (event) {
                        const match = input.name.match(/\[(\d+)\]/);
                        if (!match) return;

                        const index = match[1];
                        const isAdult = input.name.includes('[adult]');

                        const previewId = isAdult ? `adultPreviewLight${index}` : `childPreviewLight${index}`;
                        const img = document.getElementById(previewId);
                        if (img) {
                            img.src = event.target.result;
                        }
                    };
                    reader.readAsDataURL(file);
                });
            });
        });
    </script>
@endpush
