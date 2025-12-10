@extends(template() . 'layouts.app')
@section('title',trans('Affiliate'))
@section('content')
    <section class="booking-about">
        <div class="bg-layer" style="background: url({{ getFile($singleContent->content->media->image_one->driver, $singleContent->content->media->image_one->path) }});"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="booking-content">
                        <h3>{{ $singleContent->description->title_one ?? '' }}</h3>
                        <p>{{ $singleContent->description->sub_title_one ?? '' }}</p>
                        @if($basicControl->affiliate_registration && $basicControl->affiliate_status)
                            <a href="{{ route('affiliate.register') }}" class="btn-1">
                                <div class="btn-wrapper">
                                    <div class="main-text btn-single">
                                        @lang('Register')
                                    </div>
                                    <div class="hover-text btn-single">
                                        @lang('Register')
                                    </div>
                                </div>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="booking-services">
        <div class="container">
            <div class="common-title">
                <h3>{{ $singleContent->description->title_two ?? '' }}</h3>
                <p>{{ $singleContent->description->sub_title_two ?? '' }}</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="services-single">
                        <div class="service-icon">
                            <img src="{{ template(true).'img/icons/service-1.png' }}" alt="icon">
                        </div>
                        <div class="services-content">
                            <a href="#0">{{ $singleContent->description->item_title_one ?? '' }}</a>
                            <p>{{ $singleContent->description->item_description_one ?? '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="services-single">
                        <div class="service-icon">
                            <img src="{{ template(true).'img/icons/service-2.png' }}" alt="icon">
                        </div>
                        <div class="services-content">
                            <a href="#0">{{ $singleContent->description->item_title_two ?? '' }}</a>
                            <p>{{ $singleContent->description->item_description_two ?? '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="services-single">
                        <div class="service-icon">
                            <img src="{{ template(true).'img/icons/service-3.png' }}" alt="icon">
                        </div>
                        <div class="services-content">
                            <a href="#0">{{ $singleContent->description->title_three ?? '' }}</a>
                            <p>{{ $singleContent->description->item_description_three ?? '' }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="services-single">
                        <div class="service-icon">
                            <img src="{{ template(true).'img/icons/service-4.png' }}" alt="icon">
                        </div>
                        <div class="services-content">
                            <a href="#0">{{ $singleContent->description->title_four ?? '' }}</a>
                            <p>{{ $singleContent->description->item_description_four ?? '' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="booking-work">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="booking-work-content">
                        <div class="common-title">
                            <h3>{{ $singleContent->description->title_three ?? '' }}</h3>
                            <p>{{ $singleContent->description->subtitle_three ?? '' }}</p>
                        </div>
                        <div class="booking-work-list">
                            <ul>
                                @foreach($multipleContents ?? [] as $step)
                                    <li>
                                        <div class="list-number">
                                            {{ $step['serial'] }}
                                        </div>
                                        <div class="list-content">
                                            <h5>{{ $step['title'] ?? '' }}</h5>
                                            <p>{{ $step['sub_title'] ?? '' }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block">
                    <div class="booking-work-image">
                        <img src="{{ getFile($singleContent->content->media->image_two->driver, $singleContent->content->media->image_two->path) }}" alt="image">
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if($basicControl->affiliate_registration && $basicControl->affiliate_status)
        <section class="booking-register">
            <div class="bg-layer" data-jarallax  style="background: url({{ getFile($singleContent->content->media->image_three->driver, $singleContent->content->media->image_three->path) }});"></div>
            <div class="booking-register-container">
                <div class="booking-register-content">
                    <div class="common-title">
                        <h3>{{ $singleContent->description->title_four ?? '' }}</h3>
                        <p>{{ $singleContent->description->subtitle_four ?? '' }}</p>
                        <a href="{{ route('affiliate.register') }}" class="btn-1 mt-4">
                            <div class="btn-wrapper">
                                <div class="main-text btn-single">
                                    @lang('Register')
                                </div>
                                <div class="hover-text btn-single">
                                    @lang('Register')
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
