@if(isset($private_two))
    <section class="private private-two-section">
        <div class="container">
            <div class="private-container">
                <div class="row g-4">
                    <div class="col-lg-3">
                        <div class="common-title">
                            <h3 class="split-text">
                                {{ $private_two['single']['title'] ?? '' }}
                            </h3>
                            <p class="mb-0 mt-4 split-text">
                                {{ $private_two['single']['sub_title'] ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="private-slider">
                            <div class="swiper-container">
                                <div class="swiper three-item-carousel">
                                    <div class="swiper-wrapper">
                                        @foreach($private_two['multiple'] ?? [] as $item)
                                            <div class="swiper-slide">
                                                <div class="private-single">
                                                    <a href="{{ route('services', ['style' => slug($item->name)]) }}" class="privet-image">
                                                        <img
                                                            src="{{ getFile($item->driver, $item->image) }}"
                                                            alt="{{ $item->name ?? '' }}"
                                                        />
                                                    </a>
                                                    <div class="private-content">
                                                        <a href="{{ route('services', ['style' => slug($item->name)]) }}">{{ $item->name ?? '' }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="swiper-button-next3">
                                <i class="fa-duotone fa-light fa-arrow-right"></i>
                            </div>
                            <div class="swiper-button-prev3">
                                <i class="fa-duotone fa-light fa-arrow-left"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

