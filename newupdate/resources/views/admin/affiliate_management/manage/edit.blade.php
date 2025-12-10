@extends('admin.layouts.app')
@section('page_title', __('Affiliates Edit'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="javascript:;">@lang('Affiliate')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Edit')</li>
                        </ol>
                    </nav>

                    <h1 class="page-header-title">@lang('Edit')</h1>
                </div>
                <!-- End Col -->

                <div class="col-sm-auto">
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.affiliate.profile.view', $affiliate->id) }}">
                        <i class="bi-person-fill me-1"></i> @lang('My profile')
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                <div class="navbar-expand-lg navbar-vertical mb-3 mb-lg-5">
                    <div class="d-grid">
                        <button type="button" class="navbar-toggler btn btn-white mb-3" data-bs-toggle="collapse" data-bs-target="#navbarVerticalNavMenu" aria-label="Toggle navigation" aria-expanded="false" aria-controls="navbarVerticalNavMenu">
                            <span class="d-flex justify-content-between align-items-center">
                              <span class="text-dark">@lang('Menu')</span>

                              <span class="navbar-toggler-default">
                                <i class="bi-list"></i>
                              </span>

                              <span class="navbar-toggler-toggled">
                                <i class="bi-x"></i>
                              </span>
                            </span>
                        </button>
                    </div>

                    <div id="navbarVerticalNavMenu" class="collapse navbar-collapse">
                        <ul id="navbarSettings" class="js-sticky-block js-scrollspy card card-navbar-nav nav nav-tabs nav-lg nav-vertical" data-hs-sticky-block-options='{
                             "parentSelector": "#navbarVerticalNavMenu",
                             "targetSelector": "#header",
                             "breakpoint": "lg",
                             "startPoint": "#navbarVerticalNavMenu",
                             "endPoint": "#stickyBlockEndPoint",
                             "stickyOffsetTop": 20
                           }'>
                            <li class="nav-item">
                                <a class="nav-link active" href="#content">
                                    <i class="bi-person nav-icon"></i> @lang('Basic information')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#emailSection">
                                    <i class="bi-at nav-icon"></i> @lang('Email')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#passwordSection">
                                    <i class="bi-key nav-icon"></i> @lang('Password')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#preferencesSection">
                                    <i class="bi-gear nav-icon"></i> @lang('Preferences')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#deleteAccountSection">
                                    <i class="bi-trash nav-icon"></i> @lang('Delete account')
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-grid gap-3 gap-lg-5">
                    @include('admin.affiliate_management.manage.partials.profile_image')
                    @include('admin.affiliate_management.manage.partials.profile_basic')
                    @include('admin.affiliate_management.manage.partials.profile_email')
                    @include('admin.affiliate_management.manage.partials.profile_password_change')
                    @include('admin.affiliate_management.manage.partials.profile_prefences')
                    @include('admin.affiliate_management.manage.partials.delete')
                </div>

                <div id="stickyBlockEndPoint"></div>
            </div>
        </div>
        <!-- End Row -->
    </div>
@endsection
