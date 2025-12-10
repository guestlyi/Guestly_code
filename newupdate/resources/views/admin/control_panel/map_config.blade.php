@extends('admin.layouts.app')
@section('page_title',__('Map Setting'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Control Panel')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Map Settings')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Map Settings')</h1>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'Settings'])
            </div>

            <div class="col-lg-7">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card">
                        <div class="card-header card-header-content-sm-between">
                            <h2 class="card-title h4">@lang('Configure Map')</h2>
                        </div>
                        <form action="{{ route('admin.map.config.update') }}" method="get" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <label for="MapLabel" class="form-label">@lang('Google Map Api Key')</label>
                                            <input type="text"
                                                   class="form-control  @error('google_map_app_key') is-invalid @enderror"
                                                   name="google_map_app_key" id="MapLabel"
                                                   placeholder="@lang("Google Map App Key")" aria-label="@lang("Google Map App Key")"
                                                   autocomplete="off"
                                                   value="{{ old('google_map_app_key', $basicControl->google_map_app_key) }}" required>
                                            @error('google_map_app_key')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 mt-2">
                                            <label for="MapIDLabel" class="form-label">@lang('Google Map ID')</label>
                                            <input type="text"
                                                   class="form-control  @error('google_map_id') is-invalid @enderror"
                                                   name="google_map_id" id="MapIDLabel"
                                                   placeholder="@lang("Google Map ID")" aria-label="@lang("Google Map ID")"
                                                   autocomplete="off"
                                                   value="{{ old('google_map_id', $basicControl->google_map_id) }}" required>
                                            @error('google_map_id')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">@lang("Save changes")</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


