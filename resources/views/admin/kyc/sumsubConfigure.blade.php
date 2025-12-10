@extends('admin.layouts.app')
@section('page_title', __('Sumsub KYC Setting'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Sumsub KYC Setting')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Sumsub KYC')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Sumsub KYC')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title m-0">@lang('Sumsub Configure')</h4>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.kyc.sumsubConfigure') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6 mt-3">
                                        <label for="sumsubAppTokenLabel"
                                               class="form-label">@lang('App Token')</label>
                                        <input type="text" class="form-control"
                                               name="app_token" placeholder="App Token" aria-label="sumsubAppTokenLabel"
                                               autocomplete="off"
                                               value="{{ old('app_token',config('services.sumsub.app_token')) }}">
                                        @error('app_token')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mt-3">
                                        <label for="SecretKeyLabel"
                                               class="form-label">@lang('Secret Key')</label>
                                        <input type="text" class="form-control"
                                               name="secret_key" placeholder="Secret Key" aria-label="SecretKeyLabel"
                                               autocomplete="off"
                                               value="{{ old('secret_key',config('services.sumsub.secret_key')) }}">
                                        @error('secret_key')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label for="LevelNameLabel"
                                               class="form-label">@lang('Level Name')</label>
                                        <input type="text" class="form-control"
                                               name="level_name" placeholder="Level Name" aria-label="LevelNameLabel"
                                               autocomplete="off"
                                               value="{{ old('level_name',config('services.sumsub.level_name')) }}">
                                        @error('level_name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label"
                                               for="WebhookUrlLabel">@lang('Webhook Url')</label>
                                        <div class="input-group input-group-merge">
                                            <input type="text" id="extraParameters"
                                                   class="form-control"
                                                   value="{{ route('sumsub.webhook') }}"
                                                   readonly>
                                            <a class="js-clipboard input-group-append input-group-text"
                                               href="javascript:void(0);" data-bs-toggle="tooltip"
                                               title="Copy to clipboard!"
                                               data-hs-clipboard-options='{
                                                               "type": "tooltip",
                                                               "successText": "Copied!",
                                                               "contentTarget": "#extraParameters"
                                                             }'>
                                                <i class="bi-clipboard"></i>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="row form-check form-switch mt-3" for="SumsubStatus">
                                        <span class="col-4 col-sm-9 ms-0 ">
                                          <span class="d-block text-dark">@lang("Status")</span>
                                          <span
                                              class="d-block fs-5">@lang("Enable this option to allow users to verify their information through Sumsub.")</span>
                                        </span>
                                            <span class="col-2 col-sm-3 text-end">
                                         <input type='hidden' value='0' name='status'>
                                            <input class="form-check-input"
                                                   type="checkbox" name="status" id="kycStatusSwitch"
                                                   value="1" @checked(config('services.sumsub.status'))>
                                            <label class="form-check-label text-center" for="SumsubStatus"></label>
                                        </span>
                                            @error('status')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </label>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end mt-4">
                                    <button type="submit"
                                            class="btn btn-primary submit_btn">@lang('Save changes')</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div id="emailSection" class="card mb-4">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h2 class="card-title h4 mt-2">@lang('Sumsub API Instruction')</h2>
                    </div>
                    <div class="card-body">
                        <p> @lang('Sumsub is a leading identity verification platform that helps businesses ensure compliance with KYC (Know Your Customer), AML, and other global regulations. It provides automated and secure user verification through document checks, facial recognition, and liveness detection.')</p>

                        <p> @lang("With Sumsub, companies can onboard users quickly while minimizing fraud and reducing manual review efforts.")</p>
                        <p> @lang("The platform supports a wide range of documents from over 220+ countries and regions. Sumsub ensures a seamless user experience while maintaining high standards of data privacy and regulatory compliance.")</p>
                        <a href="https://cockpit.sumsub.com/"
                           target="_blank">@lang('Create an account') <i class="fas fa-external-link-alt"></i></a>
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
    <script src="{{ asset('assets/admin/js/clipboard.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        HSCore.components.HSClipboard.init('.js-clipboard')
    </script>
@endpush

