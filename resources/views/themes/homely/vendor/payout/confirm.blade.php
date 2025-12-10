@extends(template().'layouts.user')
@section('title',__('Payout'))

@section('content')
    <div class="container-fluid">
        <div class="main row justify-content-lg-center">
            <div class="col-md-10">
                <div class="personal-info-title listing-top">
                    <div class="text-area">
                        <ul>
                            <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                            <li><i class="fa-light fa-chevron-right"></i></li>
                            <li>@lang('Payout')</li>
                        </ul>
                        <h4>@lang('Payout')</h4>
                    </div>
                </div>
                <div class="col-12">
                    <div class="row g-4 g-lg-5">
                        <div class="col-lg-6 payout-confirm-left">
                            <div class="sidebar-wrapper">
                                <form action="{{ route('user.payout.confirm',$payout->trx_id) }}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf

                                    <div class="row g-4">
                                        @if($payoutMethod->supported_currency)
                                            <div class="col-md-12">
                                                <div class="input-box search-currency-dropdown">
                                                    <label for="from_wallet" class="form-label">@lang('Select Bank Currency')</label>
                                                    <input type="text" name="currency_code"
                                                           placeholder="Selected"
                                                           autocomplete="off"
                                                           value="{{ $payout->payout_currency_code }}"
                                                           class="form-control transfer-currency @error('currency_code') is-invalid @enderror">

                                                    @error('currency_code')
                                                    <span class="text-danger">{{$message}}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endif

                                        @if($payoutMethod->code == 'paypal')
                                            <div class="row">
                                                <div class="col-md-12 mt-4">
                                                    <div class="form-group search-currency-dropdown">
                                                        <label for="from_wallet" class="form-label">@lang('Select Recipient Type')</label>
                                                        <select id="from_wallet" name="recipient_type"
                                                                class="form-control form-control-sm" required>
                                                            <option value="" disabled=""
                                                                    selected="">@lang('Select Recipient')</option>
                                                            <option value="EMAIL">@lang('Email')</option>
                                                            <option value="PHONE">@lang('phone')</option>
                                                            <option value="PAYPAL_ID">@lang('Paypal Id')</option>
                                                        </select>
                                                        @error('recipient_type')
                                                        <span class="text-danger">{{$message}}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                        @if(isset($payoutMethod->inputForm))
                                            @foreach($payoutMethod->inputForm as $key => $value)
                                                @if($value->type == 'text')
                                                    <div class="input-box mt-3">
                                                        <label for="{{ $value->field_name }}" class="form-label">@lang($value->field_label)</label>
                                                        <input type="text" name="{{ $value->field_name }}"
                                                               placeholder="{{ __(snake2Title($value->field_name)) }}"
                                                               autocomplete="off"
                                                               value="{{ old(snake2Title($value->field_name)) }}"
                                                               class="form-control @error($value->field_name) is-invalid @enderror">
                                                        <div class="invalid-feedback">
                                                            @error($value->field_name) @lang($message) @enderror
                                                        </div>
                                                    </div>
                                                @elseif($value->type == 'textarea')
                                                    <div class="input-box">
                                                        <label for="{{ $value->field_name }}" class="form-label">@lang($value->field_label)</label>
                                                        <textarea
                                                            class="form-control @error($value->field_name) is-invalid @enderror"
                                                            name="{{$value->field_name}}"
                                                            rows="5">{{ old($value->field_name) }}</textarea>
                                                        <div
                                                            class="invalid-feedback">@error($value->field_name) @lang($message) @enderror</div>
                                                    </div>
                                                @elseif($value->type == 'file')
                                                    <div class="input-box mt-3">
                                                        <div class="image-preview">
                                                            <label for="image-upload" id="image-label" class="form-label">
                                                                @lang($value->field_label)
                                                            </label>
                                                            <input type="file" name="{{ $value->field_name }}"
                                                                   class="form-control @error($value->field_name) is-invalid @enderror"
                                                                   id="image-upload-{{ $value->field_name }}"
                                                                   accept="image/*" onchange="previewImage(event, '{{ $value->field_name }}')"/>
                                                            <img id="preview-{{ $value->field_name }}" src="#" alt="Image Preview" class="mt-2 d-none" style="max-width: 200px; max-height: 200px; border: 1px solid #ccc;"/>
                                                        </div>
                                                        <div class="invalid-feedback d-block">
                                                            @error($value->field_name) @lang($message) @enderror
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                        <div class="input-box col-12">
                                            <button type="submit" class="btn-4">@lang('submit')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div id="tab1" class="content active">
                                <form action="">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>@lang('Payout Method')</span>
                                            <span class="text-info">{{ __($payoutMethod->name) }} </span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>@lang('Request Amount')</span>
                                            <span
                                                class="text-success">{{ (getAmount($payout->amount)) }} {{ $payout->payout_currency_code }}</span>
                                        </li>

                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span class="text-danger">@lang('Charge')</span>
                                            <span class="text-danger">{{ (getAmount($payout->charge)) }} {{ $payout->payout_currency_code }}</span>
                                        </li>

                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span>@lang('Total Amount In Base Currency')</span>
                                            <span class="text-success">{{ (currencyPosition($payout->net_amount_in_base_currency)) }} </span>
                                        </li>

                                    </ul>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('style')
    <style>
        .list-group-item{
            padding: 10px !important;
        }
        .personal-info-title{
            margin-bottom: 0 !important;
        }
        .image-preview img{
            border-radius: 8px;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('assets/dashboard/js/jquery.uploadPreview.min.js') }}"></script>

    <script>
        function previewImage(event, fieldName) {
            const input = event.target;
            const preview = document.getElementById('preview-' + fieldName);

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

    @if ($errors->any())
        @php
            $collection = collect($errors->all());
            $errors = $collection->unique();
        @endphp
        <script>
            "use strict";
            @foreach ($errors as $error)
            Notiflix.Notify.Failure("{{ trans($error) }}");
            @endforeach
        </script>
    @endif
@endpush
