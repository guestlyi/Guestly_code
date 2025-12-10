@extends('admin.layouts.app')
@section('page_title', __('Exchange Api Settings'))
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
                                aria-current="page">@lang('Affiliate Management')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Affiliate Management')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
            </div>
            <div class="col-lg-9 seo-setting">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header">
                            <h2 class="card-title h4">@lang('Affiliate Management')</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.affiliate.info.update') }}" method="post">
                                @csrf

                                <p>@lang("Turn your audience into earners! With affiliate marketing, partners share unique links and earn commissions every time someone books through them. It’s a smart way to grow your bookings—and let others earn while they promote your services!")</p>
                                <div class="row mb-4 mt-5">
                                    <label for="currency_layer_access_key"
                                           class="col-sm-4 col-form-label form-label">@lang("Percentage")<i
                                            class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            aria-label="@lang("Affiliate Commission Percentage.")"
                                            data-bs-original-title="@lang("Set the commission percentage affiliates will earn for each successful booking they refer.")"></i></label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <input type="text"
                                                   class="form-control @error('affiliate_commission_percentage') is-invalid @enderror"
                                                   name="affiliate_commission_percentage" id="affiliate_commission_percentage"
                                                   autocomplete="off"
                                                   placeholder="e.g. 0.25"
                                                   aria-label="affiliate_commission_percentage"
                                                   value="{{ old('affiliate_commission_percentage', $basicControl->affiliate_commission_percentage) }}">
                                            <span class="input-group-text">%</span>
                                            @error('affiliate_commission_percentage')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <label class="row form-check form-switch mb-4" for="currency_layer_auto_update">
                                        <span class="col-8 col-sm-9 ms-0">
                                          <span class="d-block text-dark">@lang("Affiliate Status")</span>
                                          <span class="d-block fs-5">@lang("Easily toggle the affiliate system on or off to control how partners promote and earn through your site!")</span>
                                        </span>
                                    <span class="col-4 col-sm-3 text-end">
                                           <input type='hidden' value='0' name='affiliate_status'>
                                                <input
                                                    class="form-check-input @error('affiliate_status') is-invalid @enderror"
                                                    type="checkbox"
                                                    name="affiliate_status"
                                                    id="affiliate_status"
                                                    value="1" {{ $basicControl->affiliate_status == 1 ? 'checked' : '' }}>
                                    </span>
                                    @error('affiliate_status')
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
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
    </script>
@endpush
