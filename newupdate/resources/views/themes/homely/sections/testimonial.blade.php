@if(isset($testimonial))
    <section class="testimonial">
        <div class="container">
            <div class="common-title">
                <h3>{{ $testimonial['single']['title'] ?? '' }}</h3>
            </div>
            <div class="testimonial-slider">
                <div class="swiper-container">
                    <div class="swiper three-item-carousel ">
                        <div class="swiper-wrapper">
                            @foreach($testimonial['multiple'] ?? [] as $item)
                                <div class="swiper-slide">
                                    <div class="testimonial-single">
                                        <i class="fa-solid fa-quote-left"></i>
                                        <div class="testimonial-description">
                                            <p>{{ strip_tags($item['description']) ?? '' }}</p>
                                        </div>
                                        <div class="testimonial-shape">
                                            <img src="{{ getFile($item['media']->shape_image->driver, $item['media']->shape_image->path) }} " alt="shape">
                                        </div>
                                        <div class="testimonial-info-box">
                                            <div class="testimonial-cloent-image">
                                                <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="image">
                                            </div>
                                            <div class="testimonial-info">
                                                <h6>{{ $item['name'] ?? '' }}</h6>
                                                <p>{{ $item['designation'] ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

