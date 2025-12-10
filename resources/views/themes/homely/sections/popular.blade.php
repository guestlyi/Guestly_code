@if(isset($popular))
    <section class="popular">
        <div class="container">
            <div class="common-title">
                <h3>{{ $popular['single']['title'] ?? '' }}</h3>
            </div>

            <div class="popular-slider">
                <div class="swiper-container">
                    <div class="swiper four-item-carousel">
                        <div class="swiper-wrapper">
                            @foreach($popular['multiple'] ?? [] as $destination)
                                <div class="swiper-slide">
                                    <div class="popular-single">
                                        <div class="popular-single-badge">{{ count($destination->place ?? []) . ' attractions' }}</div>
                                        <a href="{{ route('services', ['destination' => $destination->slug]) }}" class="popular-image">
                                            <img src="{{ getFile($destination->thumb_driver, $destination->thumb) }}" alt="{{ $destination->title ?? '' }}"/>
                                        </a>
                                        <div class="popular-content">
                                            <a href="{{ route('services', ['destination' => $destination->slug]) }}" class="popular-content-title">{{ $destination->title .', ' .$destination->countryTake?->name }}</a>
                                            <a href="{{ route('services', ['destination' => $destination->slug]) }}" class="popular-content-btn">
                                                <i class="fa-light fa-arrow-up-right"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="swiper-button-next swiper-button-next2">
                    <i class="fa-duotone fa-light fa-arrow-right"></i>
                </div>
                <div class="swiper-button-prev swiper-button-prev2">
                    <i class="fa-duotone fa-light fa-arrow-left"></i>
                </div>
            </div>
        </div>
    </section>
@endif

