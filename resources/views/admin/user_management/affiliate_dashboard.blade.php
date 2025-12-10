@extends('admin.layouts.app')
@section('page_title',__('Affiliate Dashboard'))
@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="javascript:;">@lang('Profile')</a></li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Affiliate Dashboard')</li>
                        </ol>
                    </nav>

                    <h1 class="page-header-title">@lang('Affiliate Dashboard')</h1>
                </div>
            </div>
        </div>

        <div class="row col-lg-divider">
            <div class="col-lg-4">
                <div class="text-center">
                    <img class="avatar avatar-xl avatar-4x3 mb-4" src="{{ asset('assets/admin/img/oc-megaphone.svg') }}" alt="Image Description" data-hs-theme-appearance="default" style="min-height: 6rem;">
                    <img class="avatar avatar-xl avatar-4x3 mb-4" src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark" style="min-height: 6rem;">
                    <span class="text-cap text-body">@lang('Today Earning')</span>
                    <span class="d-block display-4 text-dark mb-2">{{ currencyPosition($today_earning) }}</span>

                    <div class="row col-divider">
                        <div class="col text-end">
                            <span class="d-block fw-semibold text-success">
                              <i class="bi-graph-up"></i> 12%
                            </span>
                            <span class="d-block">change</span>
                        </div>

                        <div class="col text-start">
                            <span class="d-block fw-semibold text-dark">25</span>
                            <span class="d-block">last week</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Card -->
                <div class="text-center">
                    <img class="avatar avatar-xl avatar-4x3 mb-4" src="{{ asset('assets/admin/img/oc-megaphone.svg') }}" alt="Image Description" data-hs-theme-appearance="default" style="min-height: 6rem;">
                    <img class="avatar avatar-xl avatar-4x3 mb-4" src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark" style="min-height: 6rem;">
                    <span class="text-cap text-body">@lang('Total Clicked')</span>
                    <span class="d-block display-4 text-dark mb-2">{{ $total_click }}</span>

                    <div class="row col-divider">
                        <div class="col text-end">
                            <span class="d-block fw-semibold text-success">
                              <i class="bi-graph-up"></i> 5.6%
                            </span>
                            <span class="d-block">change</span>
                        </div>

                        <div class="col text-start">
                            <span class="d-block fw-semibold text-dark">$582.00</span>
                            <span class="d-block">last week</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Card -->
                <div class="text-center">
                    <img class="avatar avatar-xl avatar-4x3 mb-4" src="{{ asset('assets/admin/img/oc-money-profits.svg') }}" alt="Image Description" data-hs-theme-appearance="default" style="min-height: 6rem;">
                    <img class="avatar avatar-xl avatar-4x3 mb-4" src="{{ asset('assets/admin/img/oc-money-profits-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark" style="min-height: 6rem;">
                    <span class="text-cap text-body">@lang('Balance')</span>
                    <span class="d-block display-4 text-dark mb-2">{{ currencyPosition($balance) }}</span>

                    <div class="row col-divider">
                        <div class="col text-end">
                            <span class="d-block fw-semibold text-danger">
                              <i class="bi-graph-down"></i> 2.3%
                            </span>
                            <span class="d-block">change</span>
                        </div>

                        <div class="col text-start">
                            <span class="d-block fw-semibold text-dark">7</span>
                            <span class="d-block">last week</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-5">
            <p class="text-muted"><i class="bi-exclamation-octagon"></i> Last referral: August 25, 2020.</p>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-3 mb-lg-5">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Header -->
                    <div class="card-header card-header-content-sm-between">
                        <h4 class="card-header-title mb-2 mb-sm-0">Total sales earnings</h4>

                        <!-- Daterangepicker -->
                        <button id="js-daterangepicker-predefined" class="btn btn-ghost-secondary btn-sm dropdown-toggle">
                            <i class="tio-date-range"></i>
                            <span class="js-daterangepicker-predefined-preview ms-1"></span>
                        </button>
                        <!-- End Daterangepicker -->
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                        <!-- Bar Chart -->
                        <div class="chartjs-custom">
                            <canvas id="referrals" class="js-chart" style="height: 15rem;" data-hs-chartjs-options='{
                          "type": "bar",
                          "data": {
                            "labels": ["Jan", "Feb", "March", "Apr", "May", "June", "July", "Aug", "Sep", "Oct", "Nov", "Dec"],
                            "datasets": [{
                              "data": [200, 300, 290, 350, 150, 350, 300, 100, 125, 220, 390, 220],
                              "backgroundColor": "#377dff",
                              "hoverBackgroundColor": "#377dff",
                              "borderColor": "#377dff",
                              "maxBarThickness": "10"
                            }]
                          },
                          "options": {
                            "scales": {
                              "y": {
                                "grid": {
                                  "color": "#e7eaf3",
                                  "drawBorder": false,
                                  "zeroLineColor": "#e7eaf3"
                                },
                                "ticks": {
                                  "beginAtZero": true,
                                  "stepSize": 100,
                                  "color": "#97a4af",
                                    "font": {
                                      "size": 12,
                                      "family": "Open Sans, sans-serif"
                                    },
                                  "padding": 10,
                                  "postfix": "$"
                                }
                              },
                              "x": {
                                "grid": {
                                  "display": false,
                                  "drawBorder": false
                                },
                                "ticks": {
                                  "color": "#97a4af",
                                    "font": {
                                      "size": 12,
                                      "family": "Open Sans, sans-serif"
                                    },
                                  "padding": 5
                                },
                                "categoryPercentage": 0.5,
                                "maxBarThickness": "10"
                              }
                            },
                            "cornerRadius": 2,
                            "plugins": {
                              "tooltip": {
                                "prefix": "$",
                                "hasIndicator": true,
                                "mode": "index",
                                "intersect": false
                              }
                            },
                            "hover": {
                              "mode": "nearest",
                              "intersect": true
                            }
                          }
                        }'></canvas>
                        </div>
                        <!-- End Bar Chart -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>

            <div class="col-lg-4 mb-3 mb-lg-5">
                <!-- Card -->
                <div class="card h-100">
                    <!-- Header -->
                    <div class="card-header card-header-content-between">
                        <h4 class="card-header-title">Your top countries <i class="bi-patch-check-fill text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="This report is based on 100% of sessions."></i></h4>
                        <a class="btn btn-ghost-secondary btn-sm" href="#">View all</a>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                        <div class="list-group list-group-flush list-group-no-gutters">
                            <!-- Item -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img class="avatar avatar-xss avatar-circle" src="./assets/vendor/flag-icon-css/flags/1x1/us.svg" alt="Flag">
                                    </div>

                                    <div class="flex-grow-1 ms-2">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <span class="d-block">United States</span>
                                            </div>
                                            <!-- End Col -->

                                            <div class="col-auto">
                                                <h5>$4,302.00</h5>
                                            </div>
                                            <!-- End Col -->
                                        </div>
                                        <!-- End Row -->
                                    </div>
                                </div>
                            </div>
                            <!-- End Item -->

                            <!-- Item -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img class="avatar avatar-xss avatar-circle" src="./assets/vendor/flag-icon-css/flags/1x1/de.svg" alt="Flag">
                                    </div>

                                    <div class="flex-grow-1 ms-2">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <span class="d-block">Germany</span>
                                            </div>
                                            <!-- End Col -->

                                            <div class="col-auto">
                                                <h5>$1,951.00</h5>
                                            </div>
                                            <!-- End Col -->
                                        </div>
                                        <!-- End Row -->
                                    </div>
                                </div>
                            </div>
                            <!-- End Item -->

                            <!-- Item -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img class="avatar avatar-xss avatar-circle" src="./assets/vendor/flag-icon-css/flags/1x1/fr.svg" alt="Flag">
                                    </div>

                                    <div class="flex-grow-1 ms-2">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <span class="d-block">France</span>
                                            </div>
                                            <!-- End Col -->

                                            <div class="col-auto">
                                                <h5>$592.00</h5>
                                            </div>
                                            <!-- End Col -->
                                        </div>
                                        <!-- End Row -->
                                    </div>
                                </div>
                            </div>
                            <!-- End Item -->

                            <!-- Item -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img class="avatar avatar-xss avatar-circle" src="./assets/vendor/flag-icon-css/flags/1x1/ca.svg" alt="Flag">
                                    </div>

                                    <div class="flex-grow-1 ms-2">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <span class="d-block">Canada</span>
                                            </div>
                                            <!-- End Col -->

                                            <div class="col-auto">
                                                <h5>$219.00</h5>
                                            </div>
                                            <!-- End Col -->
                                        </div>
                                        <!-- End Row -->
                                    </div>
                                </div>
                            </div>
                            <!-- End Item -->

                            <!-- Item -->
                            <div class="list-group-item">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <img class="avatar avatar-xss avatar-circle" src="./assets/vendor/flag-icon-css/flags/1x1/it.svg" alt="Flag">
                                    </div>

                                    <div class="flex-grow-1 ms-2">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <span class="d-block">Italy</span>
                                            </div>
                                            <!-- End Col -->

                                            <div class="col-auto">
                                                <h5>$191.00</h5>
                                            </div>
                                            <!-- End Col -->
                                        </div>
                                        <!-- End Row -->
                                    </div>
                                </div>
                            </div>
                            <!-- End Item -->
                        </div>
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
@endsection
