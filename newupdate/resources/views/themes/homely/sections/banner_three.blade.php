@if(isset($banner_three))
    <section class="bannerNewItem">
        <div class="container">
            <div class="swiper-container banner-slider-1">
                <div class="swiper-wrapper">
                    @foreach($banner_three['multiple'] ?? [] as $item)
                        <div class="swiper-slide">
                            <div class="row">
                                <div class="col-xl-5 col-lg-5">
                                    <div class="banner-content-box">
                                        <h2>{{ $item['title_part_one'] ?? '' }} <span>{{ $item['title_part_two'] ?? '' }}</span></h2>
                                        <p>
                                            {{ $item['description'] ?? '' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="col-xl-6 offset-xl-1 col-lg-7">
                                    <div class="bannerNewItem-right-content">
                                        <div class="arrow-shape">
                                            <img src="{{ asset(template(true).'img/shape/arrow-shape.png') }}" alt="image">
                                        </div>
                                        <div class="bannerNewItem-shape-1">
                                            <img src="{{ asset(template(true).'img/shape/banner-shape-1.png') }}" alt="image">
                                        </div>
                                        <div class="bannerNewItem-estimate">
                                            <h3>{{ $item['price'] ?? '' }}</h3>
                                            <p><strong>{{ $item['duration'] ?? '' }} </strong>{{ $item['duration_text'] ?? '' }}</p>
                                        </div>
                                        <div class="banner-progress">
                                            <div class="circlechart" data-percentage="{{ $item['banner_progress'] }}"></div>
                                        </div>
                                        <div class="bannerNewItem-image-box">
                                            <div class="bannerNewItem-shape">
                                                <img src="{{ asset(template(true).'img/banner/banner-three-shape.png') }}" alt="shape">
                                            </div>
                                            <div class="bannerNewItem-image">
                                                <img src="{{ getFile($item['media']->background_image->driver, $item['media']->background_image->path) }}" alt="image">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif

