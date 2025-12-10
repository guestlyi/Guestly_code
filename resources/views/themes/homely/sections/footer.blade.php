
@php
    $footer = footerData();
    $socialData = getSocialData();
@endphp
@if (isset($footer))
    <footer id="footer" class="main-footer footer-one">
        <div class="bg-layer" style="background: url({{ getFile(@$footer['single']['media']->background_image->driver, @$footer['single']['media']->background_image->path) }});"></div>
        <div class="container">
            <div class="footer-widget-container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="footer-widget logo-widget">
                            <div class="logo-widget-inner">
                                <div class="footer-logo">
                                    <img src="{{ getFile(basicControl()->admin_dark_mode_logo_driver, basicControl()->admin_dark_mode_logo) }}" alt="logo">
                                </div>
                                <p>{{ $footer['single']['sub_heading'] ?? '' }}</p>
                                <div class="social-media">
                                    <ul>
                                        @foreach($socialData['multiple'] as $social)
                                            <li><a href="{{ $social['link'] }}"><i class="{{ $social['media']->icon }}"></i></a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4  col-md-6">
                        <div class="footer-widget company-widget">
                            <div class="company-widget-inner">
                                <h6 class="footer-widget-title">@lang('QUICK LINKS')</h6>
                                <ul class="footer-widget-list">
                                    @if(getFooterMenuData('useful_link') != null)
                                        @foreach(getFooterMenuData('useful_link') as $list)
                                            {!! $list !!}
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-4">
                        <div class="footer-widget guest-widget">
                            <div class="guest-widget-inner">
                                <h6 class="footer-widget-title">@lang('SUPPORT LINKS')</h6>
                                <ul class="footer-widget-list">
                                    @if(getFooterMenuData('support_link') != null)
                                        @foreach(getFooterMenuData('support_link') as $list)
                                            {!! $list !!}
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-6 col-md-8">
                        <div class="footer-widget newsletter-widget">
                            <div class="newsletter-widget-inner">
                                <h6 class="footer-widget-title">{{ $footer['single']['newsletter_title'] ?? '' }}</h6>
                                <form action="{{ route('subscribe') }}" method="post">
                                    @csrf

                                    <div class="footer-newsletter-info">
                                        <p>{{ $footer['single']['newsletter_sub_title'] ?? '' }}</p>
                                        <div class="footer-newsletter-form">
                                            <input type="email" name="contactEmail" placeholder="@lang('Your Email')">
                                            <button type="submit" class="btn-1"><i class="fa-regular fa-arrow-right"></i></button>
                                        </div>
                                        @error('contactEmail')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-copyright">
            <div class="container">
                <div class="footer-copyright-content">
                    <div class="copyright-left">
                        <p> &copy; {{ date('Y') }} <a href="{{ route('page','/') }}">{{ basicControl()->site_title }}</a> {{ $footer['single']['copyright_text'] ?? '' }} </p>
                    </div>
                    <div class="copyright-right">
                        <div class="footer-language">
                            <p><i class="fa-light fa-globe"></i> {{ $footer['defaultLanguage']->name.'('.strtoupper($footer['defaultLanguage']->short_name).')' }}</p>
                            <p> <i class="fa-light fa-money-bill pe-1"></i>{{ session('currency_code') ?? basicControl()->base_currency }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
@endif
