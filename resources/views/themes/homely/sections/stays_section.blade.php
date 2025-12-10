<section class="categories newCategory">
    <div class="container">
        @foreach ($stays_section['multiple'] as $sectionTitle => $properties)
            @php
                $firstProperty = $properties->first();
            @endphp
            <div class="category-item">

                <h3 class="section-title">
                    <a href="{{ route('services', ['destination' => $firstProperty->destination_slug ?? '']) }}">
                        {{ $sectionTitle }} <i class="fa-regular fa-angle-right"></i>
                    </a>
                </h3>
                <div class="category-swiper-box">
                    <div class="swiper-container">
                        <div class="swiper category-swiper">
                            <div class="swiper-wrapper">
                                @foreach ($properties as $property)
                                    <div class="swiper-slide">
                                        <div class="categories-single">
                                            <div class="categories-single-image-container">
                                                @if (isset($property->host) && $property->host?->vendorInfo->badge)
                                                    <div class="most-favorite">
                                                        <a
                                                            href="{{ route('service.details', $property->slug) }}">{{ $property->host?->vendorInfo?->badgeInfo?->title }}</a>
                                                    </div>
                                                @endif
                                                <button type="button" class="filter-main-button hostButton"
                                                    data-bs-toggle="modal" data-bs-target="#hostModal"
                                                    data-amenities='@json($property->amenities)'
                                                    data-host='@json($property->host)'>
                                                    <span class="host-btn">
                                                        <span class="pageFoldRight"></span>
                                                        <img src="{{ getFile($property->host->image_driver, $property->host->image) }}"
                                                            alt="image">
                                                    </span>
                                                </button>

                                                <div class="wishlist-icon">
                                                    <a href="#0" data-product_id="{{ $property->id }}">
                                                        <i
                                                            class="{{ $property->is_wishlisted == 1 ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                                                    </a>
                                                </div>
                                                <div class="theme_carousel owl-theme owl-carousel"
                                                    data-options='{"loop": true, "margin": 0, "autoheight":true, "lazyload":true, "nav": true, "dots": true, "autoplay": false, "autoplayTimeout": 6000, "smartSpeed": 300, "responsive":{ "0" :{ "items": "1" }, "600" :{ "items" : "1" }, "768" :{ "items" : "1" } , "992":{ "items" : "1" }, "1200":{ "items" : "1" }}}'>
                                                    @foreach ($property->photos->images['images'] ?? [] as $media)
                                                        <div class="categories-single-image">
                                                            <a href="{{ route('service.details', $property->slug) }}"><img
                                                                    src="{{ getFile($media['driver'], $media['path']) }}"
                                                                    alt="@lang('Property Image')"></a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="categories-single-content">
                                                <div class="categories-single-title">
                                                    <a href="{{ route('service.details', $property->slug) }}" title="{{ $property->title }}">
                                                        {!! e(Str::limit($property->title, 40)) !!}
                                                    </a>
                                                </div>
                                                <div class="categories-single-date">
                                                    <h5>{{ userCurrencyPosition($property->pricing?->nightly_rate) ?? 0 }}
                                                        <span>/@lang('Night')</span>
                                                    </h5>
                                                    <div class="rat"><i class="fa-sharp fa-solid fa-star"></i>
                                                        {{ $property->reviewSummary ? ($property->reviewSummary->average_rating > '0.00' ? number_format($property->reviewSummary->average_rating, 1) : 'New') : 'New' }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="swiper-button-next category-swiper-button-next">
                        <i class="fa-duotone fa-light fa-arrow-right"></i>
                    </div>
                    <div class="swiper-button-prev category-swiper-button-prev">
                        <i class="fa-duotone fa-light fa-arrow-left"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @include(template() . 'frontend.services.host.host_modal')
</section>
