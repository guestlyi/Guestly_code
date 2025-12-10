@if(isset($companies))
    <section class="companies">
        <div class="container">
            <div class="common-title">
                <h3>{{ $companies['single']['title'] ?? '' }}</h3>
            </div>
            <div class="companies-slider">
                <div class="six-item-carousel swiper-container">
                    <div class="swiper-wrapper">
                        @foreach($companies['multiple'] as $company)
                            <div class="swiper-slide">
                                <div class="companies-item  wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                                    <div class="companies-logo">
                                        <img src="{{ getFile($company['media']->image->driver, $company['media']->image->path) }}" alt="@lang('Company Logo')">
                                        <img src="{{ getFile($company['media']->image->driver, $company['media']->image->path) }}" class="hide-companies-logo" alt="@lang('Company Logo')">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

