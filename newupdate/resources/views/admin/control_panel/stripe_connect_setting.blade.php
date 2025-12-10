@extends('admin.layouts.app')
@section('page_title', __('Stripe Connect Settings'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="{{ route('admin.dashboard')  }}">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang('Stripe Connect Setting')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Stripe Connect Setting')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
            </div>
            <div class="col-lg-9 seo-setting">
                @if(!$stripeGateway->status)
                    <div class="alert alert-soft-danger" role="alert">
                        @lang('To use Stripe Connect, enable the Stripe gateway and configure all required credentials. Please complete the setup from ')
                        <a target="_blank"
                           href="{{route('admin.edit.payment.methods',$stripeGateway->id)}}"> @lang('here') <i
                                class="fas fa-external-link-alt"></i></a>
                    </div>
                @endif
                <div class="alert alert-soft-info" role="alert">
                    <i class="fas fa-triangle-exclamation"></i>
                    @lang("For Stripe Connect to work properly, ensure that you have sufficient balance in $basicControl->base_currency, and all connected accounts must support receiving payments in this currency.")
                </div>
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang('Stripe Connect Setting')</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.stripeConnectConfigure') }}" method="post">
                                @csrf

                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <label
                                            class="form-label">@lang("Select Account Type")</label>
                                        <div class="tom-select-custom">
                                            <select class="js-select form-select" name="stripe_connect_account_type">
                                                <option value="express"
                                                        {{$basicControl->stripe_connect_account_type == 'express' ? 'selected':''}}
                                                        data-option-template='<div class="d-flex align-items-start"><div class="flex-shrink-0"><i class="fas fa-user-shield"></i></div><div class="flex-grow-1 ms-2"><span class="d-block fw-semibold">Express</span><span class="tom-select-custom-hide small">Quick setup, Stripe handles onboarding and KYC, limited dashboard.</span></div></div>'>
                                                    @lang('Express')
                                                </option>
                                                <option value="standard"
                                                        {{$basicControl->stripe_connect_account_type == 'standard' ? 'selected':''}}
                                                        data-option-template='<div class="d-flex align-items-start"><div class="flex-shrink-0"><i class="fas fa-user"></i></div><div class="flex-grow-1 ms-2"><span class="d-block fw-semibold">Standard</span><span class="tom-select-custom-hide small">Full Stripe dashboard access, user manages everything.</span></div></div>'>
                                                    @lang('Standard')
                                                </option>
                                            </select>
                                            @error('stripe_connect_account_type')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" id="formInlineCheck2"
                                                   name="stripe_connect_hold_payout"
                                                   class="form-check-input indeterminate-checkbox" {{$basicControl->stripe_connect_hold_payout == 1 ? 'checked':''}}>
                                            <label class="form-check-label"
                                                   for="formInlineCheck2">@lang('Holding Payout')</label>
                                            <div
                                                class="text-muted">@lang('If you want to hold vendor payments for a few days or weeks to prevent fraud, Stripe Connect allows delayed payouts.')
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row d-none" id="holdingDays">
                                    <div class="col-md-12">
                                        <label
                                            class="form-label">@lang("Holding Days")</label>
                                        <div class="input-group mb-3">
                                            <input type="number" class="form-control" placeholder="7"
                                                   value="{{$basicControl->stripe_connect_hold_days}}"
                                                   name="stripe_connect_hold_days"
                                                   aria-label="Recipient's username" aria-describedby="basic-addon2">
                                            <span class="input-group-text" id="basic-addon2">@lang('Days')</span>

                                            @error('stripe_connect_hold_days')
                                            <span class="text-danger">{{$message}}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <label class="row form-check form-switch mb-4" for="Status">
                                        <span class="col-8 col-sm-9 ms-0">
                                          <span class="d-block text-dark">@lang("Status")</span>
                                          <span
                                              class="d-block fs-5">@lang("If Stripe Connect is enabled, all vendor payments and payouts are managed directly through Stripe.")</span>
                                        </span>
                                    <span class="col-4 col-sm-3 text-end">
                                           <input type='hidden' value='0' name='stripe_connect_status'>
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    name="stripe_connect_status"
                                                    id="Status"
                                                    value="1" {{$basicControl->stripe_connect_status == 1 ? 'checked':''}}>
                                    </span>

                                    @error('stripe_connect_status')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </label>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
                                </div>
                            </form>
                        </div>
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
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function () {
            HSCore.components.HSTomSelect.init('.js-select')
        })();

        checkHold();
        function checkHold() {
            if ($("#formInlineCheck2").is(":checked")) {
                $("#holdingDays").removeClass("d-none");
            } else {
                $("#holdingDays").addClass("d-none");
            }
        }

        $(document).on("click", "#formInlineCheck2", function () {
            checkHold();
        });

    </script>
@endpush



