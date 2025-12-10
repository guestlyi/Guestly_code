@if(isset($offer))
    <section class="offer">
        <div class="container">
            <div class="common-title">
                <h3>{{ $offer['single']['title'] ?? '' }}</h3>
            </div>

            <div class="popular-slider">
                <div class="swiper-container">
                    <div class="swiper two-item-carousel">
                        <div class="swiper-wrapper">
                            @foreach($offer['multiple'] ?? [] as $item)
                            <div class="swiper-slide">
                                <div class="offer-single">
                                    <div class="bg-layer" style="background: url({{ getFile($item['media']->image->driver, $item['media']->image->path) }});"></div>
                                    <div class="offer-content">
                                        <h4>{{ $item['title'] ?? '' }}</h4>
                                        <p>
                                            {{ $item['sub_title'] ?? '' }}
                                        </p>

                                        <a href="{{ route('services') }}" class="btn-1">
                                            <div class="btn-wrapper h-40">
                                                <div class="main-text btn-single h-40">
                                                    {{ $item['button_name'] ?? '' }}
                                                </div>
                                                <div class="hover-text btn-single h-40">
                                                    {{ $item['button_name'] ?? '' }}
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="swiper-button-next swiper-button-next4">
                    <i class="fa-duotone fa-light fa-arrow-right"></i>
                </div>
                <div class="swiper-button-prev swiper-button-prev4">
                    <i class="fa-duotone fa-light fa-arrow-left"></i>
                </div>
            </div>
        </div>
    </section>
@endif

