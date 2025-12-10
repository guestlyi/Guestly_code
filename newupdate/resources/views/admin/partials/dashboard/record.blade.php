<div class="row g-4">
    <div class="col-lg-3 col-md-6">
        <a class="card stat-card-1 user-card card-hover-shadow" href="javascript:void(0)">
            <div class="card-body">
                <div class="card-title-top d-flex justify-content-between align-items-center flex-wrap">

                    <div>
                        <i class="fa-regular fa-user"></i>
                        <h6 class="card-subtitle text-dark">@lang('Total Users')</h6>
                    </div>
                    <div>
                        <h2 class="card-title text-inherit ">{{ $total_user ?? 0 }}</h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <a class="card stat-card-2 user-card card-hover-shadow" href="javascript:void(0)">
            <div class="card-body">
                <div class="card-title-top d-flex justify-content-between align-items-center flex-wrap">

                    <div>
                        <i class="fa-solid fa-users-gear"></i>
                        <h6 class="card-subtitle text-dark">@lang('Pending KYC')</h6>
                    </div>
                    <div>
                        <h2 class="card-title text-inherit ">{{ $pending_kyc }}</h2>
                    </div>
                </div>

            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <a class="card stat-card-3 user-card card-hover-shadow" href="javascript:void(0)">
            <div class="card-body">
                <div class="card-title-top d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <i class="fa-light fa-headset"></i>
                        <h6 class="card-subtitle text-dark">@lang('Pending Tickets')</h6>
                    </div>
                    <div>
                        <h2 class="card-title text-inherit ">{{ $pending_tickets }}</h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <!-- Card -->
        <a class="card stat-card-4 user-card card-hover-shadow" href="javascript:void(0)">
            <div class="card-body">
                <div class="card-title-top d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <i class="bi bi-send"></i>
                        <h6 class="card-subtitle text-dark">@lang('This Month Transactions')</h6>
                    </div>
                    <div>
                        <h2 class="card-title text-inherit ">{{ $this_month_transactions }}</h2>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-3 col-md-6">
        <!-- Card -->
        <a class="card stat-card-bold-1 user-card card-hover-shadow" href="javascript:void(0)">
            <div class="card-body">
                <div class="card-title-top d-flex justify-content-between align-items-center flex-wrap">

                    <div>
                        <i class="bi-person-check"></i>
                        <h6 class="card-subtitle text-dark">@lang('Total Host')</h6>
                    </div>
                    <div>
                        <h2 class="card-title text-inherit ">{{ $total_host ?? 0 }}</h2>
                    </div>
                </div>

            </div>
        </a>
        <!-- End Card -->
    </div>
    <div class="col-lg-3 col-md-6">
        <!-- Card -->
        <a class="card stat-card-bold-2 user-card card-hover-shadow" href="javascript:void(0)">
            <div class="card-body">
                <div class="card-title-top d-flex justify-content-between align-items-center flex-wrap">

                    <div>
                        <i class="bi-cart-check"></i>
                        <h6 class="card-subtitle text-dark">@lang('Total Booking')</h6>
                    </div>
                    <div>
                        <h2 class="card-title text-inherit ">{{ $total_booking }}</h2>
                    </div>
                </div>

            </div>
        </a>
        <!-- End Card -->
    </div>
    <div class="col-lg-3 col-md-6">
        <!-- Card -->
        <a class="card stat-card-bold-3 user-card card-hover-shadow" href="javascript:void(0)">
            <div class="card-body">
                <div class="card-title-top d-flex justify-content-between align-items-center flex-wrap">

                    <div>
                        <i class="bi-piggy-bank"></i>
                        <h6 class="card-subtitle text-dark">@lang('Total Host Earning')</h6>
                    </div>
                    <div>
                        <h2 class="card-title text-inherit ">{{ currencyPosition($total_host_earning) }}</h2>
                    </div>
                </div>

            </div>
        </a>
        <!-- End Card -->
    </div>
    <div class="col-lg-3 col-md-6">
        <!-- Card -->
        <a class="card stat-card-bold-4 user-card card-hover-shadow" href="javascript:void(0)">
            <div class="card-body">
                <div class="card-title-top d-flex justify-content-between align-items-center flex-wrap">

                    <div>
                        <i class="bi-people"></i>
                        <h6 class="card-subtitle text-dark">@lang('Total Affiliate')</h6>
                    </div>
                    <div>
                        <h2 class="card-title text-inherit ">{{ $total_affiliate ?? 0 }}</h2>
                    </div>
                </div>

            </div>
        </a>
        <!-- End Card -->
    </div>

    <div class="col-lg-9">
        @include('admin.partials.dashboard.affiliate_activity')
    </div>
    <div class="col-lg-3">
        <div class="row g-4">
            <div class="col-lg-12 col-md-6">
                <!-- Card -->
                <a class="card stat-card-neutral-1 user-card card-hover-shadow" href="javascript:void(0)">
                    <div class="card-body">
                        <div class="card-title-top d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <i class="bi-wallet"></i>
                                <h6 class="card-subtitle text-dark">@lang('Total Affiliate Earning')</h6>
                            </div>
                            <h2 class="card-title text-inherit ">{{ currencyPosition($total_affiliate_earning) }}</h2>
                        </div>
                    </div>
                </a>
                <!-- End Card -->
            </div>
            <div class="col-lg-12 col-md-6">
                <!-- Card -->
                <a class="card stat-card-neutral-3 user-card card-hover-shadow" href="javascript:void(0)">
                    <div class="card-body">
                        <div class="card-title-top d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <i class="bi-credit-card-2-front"></i>
                                <h6 class="card-subtitle text-dark">@lang('Total Booking Amount')</h6>
                            </div>
                            <h2 class="card-title text-inherit ">{{ currencyPosition($total_booking_amount) }}</h2>
                        </div>
                    </div>
                </a>
                <!-- End Card -->
            </div>
            <div class="col-lg-12 col-md-6">
                <!-- Card -->
                <a class="card stat-card-neutral-2 user-card card-hover-shadow" href="javascript:void(0)">
                    <div class="card-body">
                        <div class="card-title-top d-flex justify-content-between align-items-center flex-wrap">
                            <div>
                                <i class="bi-graph-up-arrow"></i>
                                <h6 class="card-subtitle text-dark">@lang('Platform Profit')</h6>
                            </div>
                            <h2 class="card-title text-inherit ">{{ currencyPosition($total_platform_earning) }}</h2>
                        </div>
                    </div>
                </a>
                <!-- End Card -->
            </div>
        </div>
    </div>
</div>



