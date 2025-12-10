<header class="main-header header-style-one">
    @php
        $class = $class2 = '';
        if (auth()->user()->role == 1 && str_starts_with(request()->route()?->getName(), 'user.')) {
            $class = 'notification-counter-2';
            $class2 = 'btn-2';
        }
    @endphp
    <div class="header-lower">
        <div class="container">
            <div class="inner-container d-flex align-items-center justify-content-between">
                <div class="header-left-column">
                    <div class="logo-box">
                        <div class="logo"><a href="{{ route('page','/') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="{{ basicControl()->site_title }}"></a></div>
                    </div>
                </div>
                <div class="header-right-column d-flex  align-items-center">
                    <div class="nav-outer">
                        <div class="mobile-nav-toggler"><img src="{{ asset(template(true).'img/icons/icon-bar.png')  }}" alt="icon"></div>
                        <nav class="main-menu navbar-expand-md navbar-light">
                            <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                <ul class="navigation">
                                    <li><a class="{{ menuActive(['user.dashboard']) }}" href="{{ route('user.dashboard') }}">@lang('Home')</a></li>
                                    <li><a class="{{ menuActive(['user.calender']) }}" href="{{ route('user.calender') }}">@lang('Calender')</a></li>
                                    <li><a class="{{ menuActive(['user.property.list']) }}" href="{{ route('user.property.list') }}">@lang('Listing')</a></li>
                                    <li><a class="{{ menuActive(['user.messages']) }}" href="{{ route('user.messages') }}">@lang('Messages')</a></li>
                                    <li class="dropdown"><a class="{{ menuActive(['user.payout.index','user.payout']) }}" href="javascript:void(0);">@lang('Payouts')</a>
                                        <ul>
                                            <li><a class="{{ menuActive(['user.payout']) }}" href="{{ route('user.payout') }}">@lang('Make Payout')</a></li>
                                            <li><a class="{{ menuActive(['user.payout.index']) }}"  href="{{ route('user.payout.index') }}">@lang('History')</a></li>
                                        </ul>
                                    </li>
                                    <li class="dropdown"><a class="{{ menuActive(['user.reservations','user.earnings','user.listing.introduction']) }}" href="javascript:void(0);">@lang('Menu')</a>
                                        <ul>
                                            <li><a class="{{ menuActive(['user.reservations']) }}"  href="{{ route('user.reservations') }}">@lang('Reservations')</a></li>
                                            <li><a class="{{ menuActive(['user.earnings']) }}" href="{{ route('user.earnings') }}">@lang('Earning')</a></li>
                                            <li><a class="{{ menuActive(['user.listing.introduction']) }}" href="{{ route('user.listing.introduction') }}">@lang('Create a new Listing')</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <div class="header-right-btn-placeholder">
                        <div class="header-right-btn-area">
                            @include(template().'partials.notification')
                            <div class="signup-container">
                                <div class="dropdown">
                                    <button class="cmn-dropdown-toggle btn-3 other_btn3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="btn-wrapper">
                                            <div class="main-text btn-single">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                            <div class="hover-text btn-single">
                                                <i class="fa-solid fa-user"></i>
                                            </div>
                                        </div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <div class="signup-list">
                                            <ul>
                                                <li><a class="{{ menuActive(['user.profile']) }}" href="{{ route('user.profile') }}">@lang('Account')</a></li>
                                                <li><a href="{{ route('user.wishlists') }}">@lang('Wishlists')</a></li>
                                                <li><a class="{{ menuActive(['user.twostep.security']) }}" href="{{ route('user.twostep.security') }}">@lang('2FA Verification')</a></li>
                                                <li><a class="{{ menuActive(['user.ticket.list']) }}" href="{{ route('user.ticket.list') }}">@lang('Help Center') </a></li>
                                                <li>
                                                    <a  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">@lang('Logout')</a>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                        @csrf
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Header Lower -->

    <!-- sticky header -->
    <div class="sticky-header">
        <div class="header-upper">
            <div class="container">
                <div class="inner-container d-flex align-items-center justify-content-between">
                    <div class="left-column d-flex align-items-center">
                        <div class="logo-box">
                            <div class="logo"><a href="{{ route('page','/') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="logo"></a></div>
                        </div>
                    </div>

                    <div class="header-right-column d-flex align-items-center">
                        <div class="nav-outer gap-5 d-flex align-items-center">
                            <div class="mobile-nav-toggler"><img src="{{ asset(template(true).'img/icons/icon-bar.png')  }}" alt="icon"></div>
                            <nav class="main-menu navbar-expand-md navbar-light"></nav>
                        </div>
                        <div class="sticky-header-btn-placeholder"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- sticky header -->

    <!-- mobile menu -->
    <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn"><span class="fal fa-times"></span></div>

        <nav class="menu-box">
            <div class="nav-logo"><a href="{{ route('page','/') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="logo"></a></div>
            <div class="menu-outer"></div>
        </nav>
    </div>
    <!-- mobile menu -->

    <!-- Bottom Mobile Menu -->
    <ul class="nav bottom-nav fixed-bottom d-lg-none">
        <li class="nav-item">
            <a class="nav-link mobile-nav-toggler" href="#"><i class="fa-light fa-list"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('services') ? 'active' : '' }}" href="{{ route('page','services') }}"><i class="fa-light fa-planet-ringed"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="{{ route('page','/') }}"><i class="fa-light fa-house"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('contact-us') ? 'active' : '' }}" href="{{ route('page','contact-us') }}"><i class="fa-light fa-address-book"></i></a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ Request::is('blog') ? 'active' : '' }}" href="{{ route('blog') }}"><i class="fa-light fa-user"></i></a>
        </li>
    </ul>
    <!-- Bottom Mobile Menu -->

</header>

@include(template().'partials.scripts')
