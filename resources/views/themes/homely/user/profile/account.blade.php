@extends(template().'layouts.user')
@section('title',trans('Account Settings'))
@section('content')
    <section class="account">
        <div class="container">
            <div class="common-title">
                <h3>@lang('Account')</h3>
                <p>
                    <b>{{ auth()->user()->firstname }}</b>
                    {{ auth()->user()->gmail ? ', ' . auth()->user()->gmail : '' }}.
                    <a href="{{ route('user.profile.details') }}">@lang('Go to profile')</a>
                </p>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-memo-circle-check"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Personal info')</h5>
                            <p>@lang('Tell Us About Yourself — We’d Love to Hear More About You and How We Can Stay Connected!')</p>
                            <a href="{{ route('user.personalInfo') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-shield"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Login & security')</h5>
                            <p>@lang('Update your password regularly to keep your account safe and protect your personal information.')</p>
                            <a href="{{ route('user.loginSecurity') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-file-invoice"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Transactions')</h5>
                            <p>@lang('See detailed transaction information for all your bookings, including dates, amounts, and status updates.')</p>
                            <a href="{{ route('user.transaction') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-file-invoice"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Taxes')</h5>
                            <p>@lang('Easily create, edit, and manage custom tax labels for your services to ensure accurate billing and compliance.')</p>
                            <a href="{{ route('user.tax.list') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-bell"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Notifications')</h5>
                            <p>@lang('Select your notification preferences and choose the best ways for us to contact you anytime, anywhere.')</p>
                            <a href="{{ route('user.notification.permission.list') }}" class="btn-1">@lang('View Details')</a>
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
                            <a href="{{ route('user.ticket.list') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
