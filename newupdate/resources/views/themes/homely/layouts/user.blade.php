<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" rel="icon">


    <title> {{basicControl()->site_title}} @if(isset($pageSeo['page_title']))
            | {{str_replace(basicControl()->site_title, ' ',$pageSeo['page_title'])}}
        @else
            | @yield('title')
        @endif</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{ asset(template(true) . 'css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset(template(true) . 'css/intlTelInput.min.css') }}"/>
    <link href="{{ asset(template(true) . 'css/daterangepicker.css') }}" rel="stylesheet">
    @stack('style')
</head>
<body class="">
<div class="page-wrapper">
    @if (Str::contains(request()->path(), 'listing'))
        @yield('content')
    @else
        @if(auth()->user()->role == 1)
            @include(template().'partials.vendor_dashboard_header')
        @else
            @include(template().'partials.header')
        @endif

        @yield('content')
    @endif
</div>

<script src="{{ asset(template(true) . 'js/jquery.min.js') }}"></script>

<script src="{{ asset(template(true) . 'js/bootstrap.min.js') }}"></script>

<script src="{{ asset('assets/admin/js/jquery.uploadPreview.min.js') }}"></script>
<script src="{{ asset(template(true) . 'js/select2.min.js') }}"></script>
<script src="{{ asset(template(true) . 'js/socialSharing.js') }}"></script>
<script src="{{ asset(template(true) . 'js/swiper.min.js') }}"></script>
<script src="{{ asset(template(true).'js/wow.js') }}"></script>
<script src="{{ asset(template(true) . 'js/moment.min.js') }}"></script>

<script src="{{ asset(template(true) . 'js/daterangepicker.min.js') }}"></script>

<script src="{{ asset(template(true) . 'js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset(template(true) . 'js/isotope.js') }}"></script>
<script src="{{ asset(template(true) . 'js/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset(template(true) . 'js/intlTelInput.min.js') }}"></script>

<script src="{{ asset('assets/global/js/notiflix-aio-3.2.6.min.js') }}"></script>
<script src="{{ asset('assets/global/js/pusher.min.js') }}"></script>
<script src="{{ asset('assets/global/js/vue.min.js') }}"></script>
<script src="{{ asset('assets/global/js/axios.min.js') }}"></script>

<script src="{{ asset(template(true) . 'js/script.js') }}"></script>

@stack('script')

<script>
    'use strict';

    const toggleSideMenu = () => {
        document.getElementById("sidebar").classList.toggle("active");
        document.getElementById("content").classList.toggle("active");
    };
    const hideSidebar = () => {
        document.getElementById("formWrapper").classList.remove("active");
        document.getElementById("formWrapper2").classList.remove("active");
    };

    // tab
    const tabs = document.getElementsByClassName("tab");
    const contents = document.getElementsByClassName("content");
    for (const element of tabs) {
        const tabId = element.getAttribute("tab-id");
        const content = document.getElementById(tabId);
        element.addEventListener("click", () => {
            for (const t of tabs) {
                t.classList.remove("active");
            }
            for (const c of contents) {
                c.classList.remove("active");
            }
            element.classList.add("active");
            content.classList.add("active");
        });
    }

</script>


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





