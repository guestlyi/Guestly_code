@extends(template().'layouts.affiliate')
@section('title',trans('Account Settings'))
@section('content')
    <section class="account">
        <div class="container">
            <div class="common-title">
                <h3>@lang('Account')</h3>
                <p class="mt-3">
                    <b>{{ auth('affiliate')->user()->firstname.' '.auth('affiliate')->user()->lastname }}</b>
                    <p>{{ auth('affiliate')->user()->email ? auth('affiliate')->user()->email : '' }}</p>
                </p>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-memo-circle-check"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Profile Information')</h5>
                            <p>@lang('Tell Us About Yourself — We’d Love to Hear More About You and How We Can Stay Connected!')</p>
                            <a href="{{ route('affiliate.profile') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-file-invoice-dollar"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Payouts')</h5>
                            <p>@lang('View detailed payout records for all your withdrawals, including dates, amounts, and payment status').</p>
                            <a href="{{ route('affiliate.payouts') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-exchange-alt"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Transactions')</h5>
                            <p>@lang('See detailed transaction information for all your earnings, including dates, amounts, and status updates.')</p>
                            <a href="{{ route('affiliate.transactions') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-credit-card"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Payments')</h5>
                            <p>@lang('Track all your affiliate payments, showing paid amounts, pending balances, payment dates, and current status updates.')</p>
                            <a href="{{ route('affiliate.payments') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-chart-line"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Analytics')</h5>
                            <p>@lang("Want insights on your performance? Explore detailed analytics and track your progress with ease!")</p>
                            <a href="{{ route('affiliate.analytics') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-user-headset"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Support Ticket')</h5>
                            <p>@lang("Have questions or need help? Submit a support ticket and our team will get back to you very shortly!")</p>
                            <a href="{{ route('affiliate.ticket.list') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    <style>
        .account .common-title p {
            font-size: 17px;
            margin-top: 6px;
        }
    </style>
@endpush
