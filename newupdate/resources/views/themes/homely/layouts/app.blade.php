<!DOCTYPE html>
<html lang="{{ session()->get('lang') }}" dir="{{ defaultLang()->rtl == 1 ? 'rtl' : ''  }}">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta content="{{ isset($pageSeo['meta_description']) ? $pageSeo['meta_description'] : '' }}" name="description">
    <meta content="{{ is_array(@$pageSeo['meta_keywords']) ? implode(', ', @$pageSeo['meta_keywords']) : @$pageSeo['meta_keywords'] }}" name="keywords">
    <meta name="theme-color" content="{{ basicControl()->primary_color }}">
    <meta name="author" content="{{basicControl()->site_title}}">
    <meta name="robots" content="{!! isset($pageSeo['meta_robots']) ? $pageSeo['meta_robots'] : ''  !!}">

    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ isset(basicControl()->site_title) ? basicControl()->site_title : '' }}">
    <meta property="og:title" content="{{ isset($pageSeo['meta_title']) ? $pageSeo['meta_title'] : '' }}">
    <meta property="og:description" content="{{ isset($pageSeo['og_description']) ? $pageSeo['og_description'] : '' }}">
    <meta property="og:image" content="{{ @$pageSeo['meta_image'] }}">

    <meta name="twitter:card" content="{{ isset($pageSeo['meta_title']) ? $pageSeo['meta_title'] : '' }}">
    <meta name="twitter:title" content="{{ isset($pageSeo['meta_title']) ? $pageSeo['meta_title'] : '' }}">
    <meta name="twitter:description" content="{{ isset($pageSeo['meta_description']) ? $pageSeo['meta_description'] : '' }}">
    <meta name="twitter:image" content="{{ @$pageSeo['meta_image'] }}">

    <title> {{basicControl()->site_title}} @if(isset($pageSeo['page_title']))
            | {{str_replace(basicControl()->site_title, ' ', html_entity_decode($pageSeo['page_title']))}}
        @else
             | @yield('title')
        @endif</title>

    <!-- Favicons -->
    <link href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" rel="icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">
    @stack('css-lib')
    @stack('style')

    {{-- <link rel="stylesheet" href="{{ asset(template(true) . "css/bootstrap.min.css") }}"/> --}}
    <link rel="stylesheet" href="{{ asset(template(true) . "css/intlTelInput.min.css") }}"/>

    <!-- Template Main CSS File -->
    <link href="{{ asset(template(true) . 'css/style.css') }}" rel="stylesheet">
</head>

<body>
    <div class="page-wrapper">
        @if(basicControl()->preloader_status == 1)
            @include(template().'partials.preloader')
        @endif

        @if(auth('affiliate')->check())
            @include(template().'partials.affiliate_header')
        @else
            @if(!Route::is('login') && !Route::is('register'))
                @include(template().'partials.header')
            @endif
        @endif

        @if(!request()->is('/') && !request()->is('/'))
            @if(isset($pageSeo) && $pageSeo['breadcrumb_status'] == 1)
                <section class="common-banner">
                    <div class="container">
                        <div class="common-banner-container">
                            <div class="bg-layer" style="background: url({{ $pageSeo['breadcrumb_image'] }});"></div>
                            <div class="common-banner-content">
                                <h3>{{ $pageSeo['page_title'] ?? '' }}</h3>
                                <div class="breadcrumb">
                                    <ul>
                                        <li class="breadcrumb-item active"><a href="{{ route('page','/') }}">@lang('Home')</a></li>
                                        <li class="breadcrumb-item">{{ $pageSeo['page_title'] ?? '' }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            @endif
        @endif

        @yield('content')
        @if(!Route::is('login') && !Route::is('register'))
            @include(template().'sections.footer')
        @endif
    </div>

    <!-- Vendor JS Files -->
    <script src="{{ asset(template(true).'js/jquery.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/bootstrap.min.js') }}"></script>

    <script src="{{ asset(template(true).'js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/search-box.js') }}"></script>
    <script src="{{ asset(template(true).'js/appear.js') }}"></script>
    <script src="{{ asset(template(true).'js/wow.js') }}"></script>
    <script src="{{ asset(template(true).'js/owl.js') }}"></script>
    <script src="{{ asset(template(true).'js/TweenMax.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/odometer.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/swiper.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/parallax-scroll.js') }}"></script>
    <script src="{{ asset(template(true).'js/jarallax.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/jquery.paroller.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/isotope.js') }}"></script>
    <script src="{{ asset(template(true).'js/socialSharing.js') }}"></script>
    <script src="{{ asset(template(true).'js/moment.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/nouislider.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/progresscircle.js') }}"></script>
    <script src="{{ asset(template(true).'js/intlTelInput.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/select2.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/fancybox.umd.js') }}"></script>
    <script src="{{ asset(template(true).'js/gsap.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/scrollTrigger.min.js') }}"></script>
    <script src="{{ asset(template(true).'js/splitText.min.js') }}"></script>

    <script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/vue.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/axios.min.js') }}"></script>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script>
        window.flagBaseUrl = '{{ asset(template(true) . "img/mini-flag") }}';
    </script>
    <script src="{{ asset(template(true).'js/script.js') }}"></script>
    @stack('js-lib')

    @stack('script')

    @if (session()->has('success'))
        <script>
            Notiflix.Notify.success("@lang(session('success'))");
        </script>
    @endif

    @if (session()->has('error'))
        <script>
            Notiflix.Notify.failure("@lang(session('error'))");
        </script>
    @endif

    @if (session()->has('warning'))
        <script>
            Notiflix.Notify.warning("@lang(session('warning'))");
        </script>
    @endif

    @include('plugins')
    @include(template().'partials.cookie')
</body>

</html>


