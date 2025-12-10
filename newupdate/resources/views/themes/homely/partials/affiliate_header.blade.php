
<header class="main-header header-style-one">
    @php
        $class = $class2 = '';
        $socialData = getSocialData();
    @endphp
    <div class="header-lower">
        <div class="container">
            <div class="inner-container d-flex align-items-center justify-content-between">
                <div class="header-left-column">
                    <div class="logo-box">
                        <div class="logo"><a href="{{ route('page', '/') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="logo"></a></div>
                    </div>
                </div>
                <div class="header-right-column d-flex  align-items-center">
                    <div class="nav-outer">
                        <div class="mobile-nav-toggler"><img src="{{ asset(template(true).'/img/icons/icon-bar.png') }}" alt="icon"></div>
                        <nav class="main-menu navbar-expand-md navbar-light">
                            <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                {!! renderHeaderMenu(getHeaderMenuData()) !!}
                            </div>
                        </nav>
                    </div>
                    <div class="header-right-btn-placeholder">
                        <div class="header-right-btn-area">
                            <div class="header-language">
                                <div class="language-box-wrapper">
                                    @php
                                        $langCurrencyData = langCurrencyData();
                                        $languages = $langCurrencyData['language'];
                                    @endphp
                                    <button type="button" class="btn lang-currency-btn"
                                            data-lang_data="{{ json_encode($languages->map(function($lang) {
                                                   return [
                                                       'short_name' => $lang->short_name,
                                                       'name' => $lang->name,
                                                       'flag_url' => getFile($lang->flag_driver, $lang->flag),
                                                   ];
                                               }))
                                           }}"
                                            data-route="{{ route('settingChange') }}"
                                            data-default_lang="{{ @$langCurrencyData['defaultLanguage']['short_name'] }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#lang-currency-modal">
                                        <i class="fa-light fa-globe"></i>
                                        <i class="fa-thin fa-angle-down"></i>
                                    </button>
                                </div>
                            </div>

                            @auth
                                @include(template().'partials.affiliate_notification')
                            @endauth

                            <div class="signup-container">
                                <div class="dropdown">
                                    <button class="cmn-dropdown-toggle btn-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <div class="btn-wrapper">
                                            <div class="main-text btn-single">
                                                @if(auth('affiliate')->check())
                                                    <span>@lang('Dashboard')</span>
                                                @elseif(auth('web')->check())
                                                    <span>@lang('Dashboard')</span>
                                                @else
                                                    <span>@lang('Log In / Sign Up')</span>
                                                @endif
                                            </div>
                                            <div class="hover-text btn-single">
                                                @if(auth('affiliate')->check())
                                                    <span>@lang('Dashboard')</span>
                                                @elseif(auth('web')->check())
                                                    <span>@lang('Dashboard')</span>
                                                @else
                                                    <span>@lang('Log In / Sign Up')</span>
                                                @endif
                                            </div>
                                        </div>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <div class="signup-list">
                                            <ul>
                                                @auth('affiliate')
                                                    <li>
                                                        <a type="button" class="btn d-flex align-items-center justify-content-start" href="{{ route('affiliate.dashboard') }}">
                                                            @lang('Dashboard')
                                                        </a>
                                                    </li>
                                                @endauth

                                                <li class="border"></li>

                                                @auth('affiliate')
                                                    <li><a href="{{ route('affiliate.information') }}">@lang('Manage Information')</a></li>
                                                    <li>
                                                        <form id="affiliate-logout-form" action="{{ route('affiliate.logout') }}" method="POST" class="d-none">
                                                            @csrf
                                                        </form>

                                                        <a href="{{ route('affiliate.logout') }}"
                                                           onclick="event.preventDefault(); document.getElementById('affiliate-logout-form').submit();">
                                                            @lang('Logout')
                                                        </a>
                                                    </li>
                                                @endauth
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
    <div class="sticky-header">
        <div class="header-upper">
            <div class="container">
                <div class="inner-container d-flex align-items-center justify-content-between">
                    <div class="left-column d-flex align-items-center">
                        <div class="logo-box">
                            <div class="logo"><a href="{{ route('page', '/') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="logo"></a></div>
                        </div>
                    </div>

                    <div class="header-right-column d-flex align-items-center">
                        <div class="nav-outer gap-5 d-flex align-items-center">
                            <div class="mobile-nav-toggler"><img src="{{ asset(template(true).'img/icons/icon-bar.png') }}" alt="icon"></div>
                            <nav class="main-menu navbar-expand-md navbar-light"></nav>
                        </div>
                        <div class="sticky-header-btn-placeholder"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="lang-currency-modal" data-bs-keyboard="false" tabindex="-1"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered ">
            <div class="modal-content langCurrency">
                <div class="modal-header">
                    <h4 class="modal-title" id="staticBackdropLabel">@lang('Select Language')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <form action="{{route('settingChange')}}" method="POST">
                            @csrf

                            @if(isset($languages) && !empty($languages))
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div id="formModal">
                                            <label class="form-label">@lang('Select language')</label>
                                            <select class="cmn-select2-image2 langContainer" name="language">
                                                @foreach($languages as $language)
                                                    <option value="{{$language->short_name}}"
                                                            data-flag="{{getFile($language->flag_driver,$language->flag)}}"
                                                        {{session()->get('lang') == $language->short_name ? 'selected':''}}>
                                                        {{$language->name}}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="mt-10 langCurrencySaveD">
                                <button type="submit" class="btn-1 mt-2 langCurrencySave">
                                    <div class="btn-wrapper">
                                        <div class="main-text btn-single">
                                            @lang('Save')
                                        </div>
                                        <div class="hover-text btn-single">
                                            @lang('Save')
                                        </div>
                                    </div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn"><span class="fal fa-times"></span></div>

        <nav class="menu-box">
            <div class="nav-logo"><a href="{{ route('page','/') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="logo"></a></div>
            <div class="menu-outer"></div>

            <div class="social-media">
                <ul>
                    @foreach($socialData['multiple'] as $social)
                        <li><a href="{{ $social['link'] }}"><i class="{{ $social['media']->icon }}"></i></a></li>
                    @endforeach
                </ul>
            </div>
        </nav>
    </div>
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


    @include(template().'partials.affiliate_scripts')

</header>



