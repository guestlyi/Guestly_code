@php
    $homeClass = 'one';
    $lastElements = ['/', 'home-101', 'home-102', 'home-103', 'home-104'];
    $currentSegment = request()->segment(count(request()->segments())) ?? '/';
    $homeStyle = basicControl()->home_style ?? 'home-101';
    $requestedVersion = request()->home_version ?? null;

    $activeHome = $requestedVersion ?? $currentSegment ?? $homeStyle;

    $homeClassMap = [
        'home-101' => 'three',
        'home-102' => 'two',
        'home-103' => 'one',
        'home-104' => 'one',
        '/'        => $homeClass
    ];

    if (in_array($currentSegment, $lastElements)) {
        $homeClass = $homeClassMap[$activeHome] ?? $homeClass;
    }
@endphp
<header class="main-header header-style-{{ $homeClass }}">
    <div class="header-lower">
        <div class="container">
            <div class="inner-container d-flex align-items-center justify-content-between gap-1">
                <div class="header-left-column">
                    <div class="logo-box">
                        <div class="logo">
                            <a href="{{ url('/') }}"><img
                                    src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}"
                                    alt="logo" /></a>
                        </div>
                    </div>
                </div>
                <div class="header-right-column d-flex align-items-center">
                    <div class="nav-outer">
                        <div class="mobile-nav-toggler">
                            <img src="{{ asset(template(true) . '/img/icons/icon-bar.png') }}" alt="icon" />
                        </div>
                        <nav class="main-menu navbar-expand-md navbar-light">
                            <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                {!! renderHeaderMenu(getHeaderMenuData()) !!}
                            </div>
                        </nav>
                    </div>
                    <div class="header-right-btn-placeholder">
                        <div class="header-right-btn-area">
                            @auth
                                @include(template() . 'partials.notification')
                            @endauth
                            <div class="lang-currency-dropdown">
                                @php
                                    $langCurrencyData = langCurrencyData();
                                    $currencies = $langCurrencyData['currency'];
                                    $languages = $langCurrencyData['language'];
                                    $activeCurrency = $langCurrencyData['activeCurrency'] ?? null;
                                    $defaultLang = $langCurrencyData['defaultLanguage']['short_name'] ?? null;
                                @endphp

                                <button type="button" class="btn-3 lang-currency-btn"
                                    data-flag-base="{{ asset('admin/flags') }}"
                                    data-lang_data="{{ json_encode(
                                        $languages->map(
                                            fn($lang) => [
                                                'short_name' => $lang->short_name,
                                                'name' => $lang->name,
                                                'flag_url' => getFile($lang->flag_driver, $lang->flag),
                                            ],
                                        ),
                                    ) }}"
                                    data-route="{{ route('settingChange') }}">

                                    <div class="btn-wrapper">
                                        <div class="main-text btn-single">
                                            <i class="fa-light fa-globe"></i>
                                        </div>
                                        <div class="hover-text btn-single">
                                            <i class="fa-light fa-globe"></i>
                                        </div>
                                    </div>
                                </button>

                                <div class="lang-currency-dropdown-menu">
                                    <div class="d-flex gap-2 justify-content-between align-items-center mb-2">
                                        <h6>@lang('Language and Currency')</h6>
                                        <button type="button" class="lang-currency-btn-close">
                                            <i class="fa-regular fa-xmark"></i>
                                        </button>
                                    </div>

                                    <label class="form-label">@lang('Language')</label>
                                    <select id="languageSelect" class="cmn-select2-image2 w-100">
                                        @foreach ($languages as $lang)
                                            <option value="{{ $lang->short_name }}"
                                                data-flag="{{ getFile($lang->flag_driver, $lang->flag) }}"
                                                @selected($defaultLang == $lang->short_name)>
                                                {{ $lang->name }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <label class="form-label mt-3">@lang('Currency')</label>
                                    <select id="currencySelect" class="cmn-select2-image2 w-100">
                                        @foreach ($currencies as $currency)
                                            <option value="{{ $currency->id }}" @selected($activeCurrency == $currency->code)>
                                                {{ $currency->name }} ({{ $currency->symbol }})
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="button" id="saveLangCurrency" class="btn-1 w-100 mt-3">
                                        <div class="btn-wrapper h-45">
                                            <div class="main-text btn-single h-45">@lang('Save')</div>
                                            <div class="hover-text btn-single h-45">@lang('Save')</div>
                                        </div>
                                    </button>
                                </div>

                                <div class="lang-currency-overlay"></div>
                            </div>

                            @guest('web')
                                <a class="btn-3 login-btn" href="{{ route('login') }}">
                                    <div class="btn-wrapper">
                                        <div class="main-text btn-single">
                                            <i class="fa-regular fa-user"></i>
                                            <span class="d-none d-sm-block">@lang('Log In')</span>
                                        </div>
                                        <div class="hover-text btn-single">
                                            <i class="fa-regular fa-user"></i>
                                            <span class="d-none d-sm-block">@lang('Log In')</span>
                                        </div>
                                    </div>
                                </a>
                            @endguest

                            @auth('web')
                                <div class="dropdown">
                                    <a class="btn-3 login-btn dropdown-toggle" href="#" role="button"
                                        id="dashboardDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="btn-wrapper">
                                            <div class="main-text btn-single">
                                                <i class="fa-regular fa-user"></i>
                                                <span class="d-none d-sm-block">@lang('Dashboard')</span>
                                            </div>
                                            <div class="hover-text btn-single">
                                                <i class="fa-regular fa-user"></i>
                                                <span class="d-none d-sm-block">@lang('Dashboard')</span>
                                            </div>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="dashboardDropdown">
                                        <li>
                                            <a class="front-header-menus" href="{{ route('user.dashboard') }}">
                                                <i class="fa-regular fa-home me-1"></i> @lang('Dashboard')
                                            </a>
                                        </li>
                                        <li>
                                            <a class="front-header-menus" href="{{ route('user.enter.home') }}">
                                                <i class="fa-regular fa-house-user me-1"></i> @lang('Set your Home')
                                            </a>
                                        </li>
                                        <li>
                                            <a class="front-header-menus" href="{{ route('user.ticket.list') }}">
                                                <i class="fa-regular fa-headset me-1"></i> @lang('Help Center')
                                            </a>
                                        </li>
                                        <li>
                                            <a class="front-header-menus" href="{{ route('user.wishlists') }}">
                                                <i class="fa-regular fa-heart me-1"></i> @lang('Wishlists')
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="front-header-menus text-danger" href="{{ route('logout') }}"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fa-regular fa-right-from-bracket me-1"></i> @lang('Logout')
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endauth

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
                            <div class="logo">
                                <a href="{{ url('/') }}"><img
                                        src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}"
                                        alt="logo" /></a>
                            </div>
                        </div>
                    </div>

                    <div class="header-right-column d-flex align-items-center">
                        <div class="nav-outer gap-5 d-flex align-items-center">
                            <div class="mobile-nav-toggler">
                                <img src="{{ asset(template(true) . '/img/icons/icon-bar.png') }}" alt="icon" />
                            </div>
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
            <div class="nav-logo">
                <a href="{{ url('/') }}"><img
                        src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="logo" /></a>
            </div>
            <div class="menu-outer">
            </div>
        </nav>
    </div>
    <!-- mobile menu -->

    @include(template() . 'partials.scripts')
</header>
