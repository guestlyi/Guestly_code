@extends('admin.layouts.app')
@section('page_title', __('Preloader Config'))
@section('content')
    <div class="content container-fluid">
            <div class="page-header">
                <div class="row align-items-end">
                    <div class="col-sm mb-2 mb-sm-0">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb breadcrumb-no-gutter">
                                <li class="breadcrumb-item">
                                    <a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')                                </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                                <li class="breadcrumb-item active" aria-current="page">@lang('Preloader Config')</li>
                            </ol>
                        </nav>
                        <h1 class="page-header-title">@lang('Preloader Config')</h1>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3">
                    @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
                </div>
                <div class="col-lg-9" id="basic_control">
                    <div class="d-grid gap-3 gap-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang('Preloader Config')</h2>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.preloader.config.update') }}" method="post" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row g-3 mb-3">
                                        <div class="col-sm-12">
                                            <div>
                                                <label>@lang('Preloader Text')</label>
                                                <input type="text" name="preloader_text" id="instantSwapCommission"
                                                       value="{{ $basicControl->preloader_text }}"
                                                       placeholder="Preloader text" class="form-control" autocomplete="off">
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <label class="row form-check form-switch" for="stakingSwitch">
                                                <span class="col-8 col-sm-9 ms-0">
                                                  <span class="text-dark">@lang('Preloader Status')</span>
                                                    <span
                                                        class="d-block fs-5">@lang('Enable the preloader on the frontend to display a loading animation.')</span>
                                                </span>
                                                <span class="col-4 col-sm-3 text-end">
                                                <input type="hidden" name="is_preloader" value="0">
                                                <input type="checkbox" class="form-check-input" name="is_preloader"
                                                       id="stakingSwitch" value="1" {{ $basicControl->preloader_status == 1 ? 'checked' : '' }}>
                                            </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-start">
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

