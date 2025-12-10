@if(isset($banner_section_three))
    <section class="banner-section-three">
        <div class="container">
            <div class="top-box">
                <div class="subtitle split-text">{{ $banner_section_three['single']['heading'] ?? '' }}</div>
                <h1 class="banner-section-three-title">{{ $banner_section_three['single']['sub_heading'] ?? '' }}</h1>
            </div>
            <div class="middle-box">
                <div class="middle-left-side">
                    <a href="{{ $banner_section_three['single']['media']->circle_link ?? '#' }}"
                        data-fancybox
                        class="round-box-content"
                        aria-label="Play promotional video"
                    >

                    <span class="curved-circle">
                      {{ $banner_section_three['single']['circle_text'] ?? '' }}
                    </span>
                        <div class="icon-box">
                            <div class="icon-box-inner">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="26"
                                    height="26"
                                    viewBox="0 0 26 26"
                                    fill="none"
                                >
                                    <path
                                        d="M3.52125 11.5843C3.52125 11.5843 2.61659 7.38435 7.85176 6.81452C7.85176 6.81452 7.23509 5.75858 4.70467 5.43604C4.70467 5.43604 7.08918 2.5201 11.0196 5.57734C12.8926 0.964989 16.416 2.29816 16.416 2.29816C14.3848 3.84176 14.3809 5.06588 14.3809 5.06588C19.1983 2.94171 20.5154 7.03032 20.5154 7.03032C17.4282 6.08112 14.6881 7.87046 14.6881 7.87046C21.9853 7.74759 19.4295 14.3758 19.4295 14.3758C16.0789 9.33189 12.2691 10.2404 12.2691 10.2404C12.2691 10.2404 8.51297 11.3578 8.1336 17.4024C8.1336 17.4024 2.60661 12.9398 8.98834 9.39716C8.98834 9.39716 5.71837 9.219 3.52125 11.5843Z"
                                        fill="white"
                                    />
                                    <path
                                        d="M15.768 21.0108C15.7618 20.9509 15.7572 20.8926 15.7503 20.8342C15.639 19.991 15.4785 19.1539 15.2749 18.3291C14.6836 15.9431 13.706 13.6162 12.3682 11.5373C12.4394 11.5005 12.5125 11.4674 12.5871 11.4382C12.7211 11.4192 12.8564 11.4102 12.9918 11.4113C13.3336 11.4113 13.7774 11.4567 14.2881 11.6095C14.4609 11.8675 14.6391 12.1209 14.8027 12.382C15.2854 13.1466 15.7293 13.935 16.1328 14.7443C17.1148 16.7074 17.8292 18.7933 18.2569 20.9463C21.2643 21.1682 22.46 22.4776 22.46 24.0634H12.1839C12.1832 22.5897 13.2138 21.3572 15.768 21.0108Z"
                                        fill="white"
                                    />
                                </svg>
                            </div>
                        </div>
                    </a>
                    <a class="btn-2" href="{{ route('services') }}">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                {{ $banner_section_three['single']['button_text'] ?? '' }}
                                <div class="icon-box">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="16"
                                        height="16"
                                        viewBox="0 0 16 16"
                                        fill="none"
                                    >
                                        <path
                                            d="M1.85457 7.01898C1.85457 7.01898 1.26557 4.28448 4.67407 3.91348C4.67407 3.91348 4.27257 3.22598 2.62507 3.01598C2.62507 3.01598 4.17757 1.11748 6.73657 3.10798C7.95607 0.104985 10.2501 0.972985 10.2501 0.972985C8.92757 1.97798 8.92507 2.77498 8.92507 2.77498C12.0616 1.39198 12.9191 4.05398 12.9191 4.05398C10.9091 3.43598 9.12507 4.60098 9.12507 4.60098C13.8761 4.52098 12.2121 8.83648 12.2121 8.83648C10.0306 5.55248 7.55007 6.14398 7.55007 6.14398C7.55007 6.14398 5.10457 6.87148 4.85757 10.807C4.85757 10.807 1.25907 7.90148 5.41407 5.59498C5.41407 5.59498 3.28507 5.47898 1.85457 7.01898Z"
                                            fill="#F23F3F"
                                        />
                                        <path
                                            d="M9.82806 13.156C9.82406 13.117 9.82106 13.079 9.81656 13.041C9.74406 12.492 9.63956 11.947 9.50706 11.41C9.12206 9.8565 8.48556 8.3415 7.61456 6.988C7.6609 6.96403 7.70847 6.9425 7.75706 6.9235C7.84433 6.91114 7.93241 6.90529 8.02056 6.906C8.24306 6.906 8.53206 6.9355 8.86456 7.035C8.97706 7.203 9.09306 7.368 9.19956 7.538C9.51388 8.0358 9.80291 8.54912 10.0656 9.076C10.7049 10.3542 11.1701 11.7123 11.4486 13.114C13.4066 13.2585 14.1851 14.111 14.1851 15.1435H7.49456C7.49406 14.184 8.16506 13.3815 9.82806 13.156Z"
                                            fill="#F23F3F"
                                        />
                                    </svg>
                                </div>
                            </div>
                            <div class="hover-text btn-single">
                                {{ $banner_section_three['single']['button_text'] ?? '' }}
                                <div class="icon-box">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        width="16"
                                        height="16"
                                        viewBox="0 0 16 16"
                                        fill="none"
                                    >
                                        <path
                                            d="M1.85457 7.01898C1.85457 7.01898 1.26557 4.28448 4.67407 3.91348C4.67407 3.91348 4.27257 3.22598 2.62507 3.01598C2.62507 3.01598 4.17757 1.11748 6.73657 3.10798C7.95607 0.104985 10.2501 0.972985 10.2501 0.972985C8.92757 1.97798 8.92507 2.77498 8.92507 2.77498C12.0616 1.39198 12.9191 4.05398 12.9191 4.05398C10.9091 3.43598 9.12507 4.60098 9.12507 4.60098C13.8761 4.52098 12.2121 8.83648 12.2121 8.83648C10.0306 5.55248 7.55007 6.14398 7.55007 6.14398C7.55007 6.14398 5.10457 6.87148 4.85757 10.807C4.85757 10.807 1.25907 7.90148 5.41407 5.59498C5.41407 5.59498 3.28507 5.47898 1.85457 7.01898Z"
                                            fill="#F23F3F"
                                        />
                                        <path
                                            d="M9.82806 13.156C9.82406 13.117 9.82106 13.079 9.81656 13.041C9.74406 12.492 9.63956 11.947 9.50706 11.41C9.12206 9.8565 8.48556 8.3415 7.61456 6.988C7.6609 6.96403 7.70847 6.9425 7.75706 6.9235C7.84433 6.91114 7.93241 6.90529 8.02056 6.906C8.24306 6.906 8.53206 6.9355 8.86456 7.035C8.97706 7.203 9.09306 7.368 9.19956 7.538C9.51388 8.0358 9.80291 8.54912 10.0656 9.076C10.7049 10.3542 11.1701 11.7123 11.4486 13.114C13.4066 13.2585 14.1851 14.111 14.1851 15.1435H7.49456C7.49406 14.184 8.16506 13.3815 9.82806 13.156Z"
                                            fill="#F23F3F"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <input type="hidden" name="home_destinations" id="home_destinations" value="{{ $banner_section_three['home_destinations'] ?? '' }}" />

                <div class="middle-right-side">
                    <div class="swiper-container">
                        <div class="swiper banner-three-swiper">
                            <div class="swiper-wrapper">
                                @foreach ($banner_section_three['destinations'] ?? [] as $key => $item)
                                    <div class="swiper-slide">
                                        <div class="destination-box3">
                                            <div class="img-box">
                                                <img
                                                    src="{{ getFile($item->thumb_driver, $item->thumb) }}"
                                                    alt="{{ $item->title ?? '' }}"
                                                />
                                                <a href="{{ route('services', ['destination' => $item->slug]) }}" class="destination-badge3">
                                                    {{ $banner_section_three['single']['destination_button_text'] ?? 'Explore Popular Destination' }}
                                                    <i class="fa-light fa-arrow-up-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="destination-search">
                <form action="{{ route('services') }}" method="get">
                    <div class="destination-search-inner">
                        <div class="location-search-box">
                            <div class="select-option">
                                <div class="select-icon">
                                    <i class="fa-light fa-location-dot"></i>
                                </div>
                                <div class="select-option-content">
                                    <h6 class="select-option-title">{{ $banner_section_three['single']['search_text_one'] ?? '' }}</h6>
                                    <input type="search" class="soValue optionSearch" name="search" placeholder="Search destination" autocomplete="off"/>
                                </div>
                            </div>

                            <div class="location-search-dropdown">
                                <ul class="search-options top-search-options" id="search-results"></ul>
                                <button class="show-more" style="display:none;">@lang('Show More')</button>
                            </div>
                        </div>
                        <div class="hr-line"></div>
                        <div class="date">
                            <div class="select-option">
                                <div class="select-icon">
                                    <i class="fa-light fa-door-open"></i>
                                </div>
                                <div class="select-option-content">
                                    <h6 class="select-option-title">{{ $banner_section_three['single']['search_text_two'] ?? '' }}</h6>
                                    <input
                                        type="text"
                                        id="date-picker"
                                        name="datefilter"
                                        placeholder="12/12/2024 - 14/12/2024"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="hr-line"></div>
                        <div class="count">
                            <input type="hidden" name="adult_count" id="adult_count" value="0" />
                            <input type="hidden" name="children_count" id="children_count" value="0" />
                            <input type="hidden" name="pet_count" id="pet_count" value="0" />
                            <div class="count-counter select-option">
                                <div class="select-icon">
                                    <i class="fa-light fa-user"></i>
                                </div>
                                <div class="select-option-content">
                                    <h6 class="select-option-title">{{ $banner_section_three['single']['search_text_three'] ?? '' }}</h6>
                                    <div class="count-input">
                                        <div class="count-counter-inner">
                                            <span class="adult">0</span>
                                            <p>@lang('adult')</p>
                                        </div>
                                        <div class="count-counter-inner">
                                            <span class="childeren">0</span>
                                            <p>@lang('children')</p>
                                        </div>
                                        <div class="count-counter-inner">
                                            <span class="room">0</span>
                                            <p>@lang('pet')</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="count-container">
                                <div class="count-single">
                                    <div class="count-single-text">
                                        <h6>@lang('Adult')</h6>
                                        <p>@lang('Over 12 Years')</p>
                                    </div>
                                    <div class="count-single-inner">
                                        <button type="button" class="decrement">
                                            <i class="fa-regular fa-minus"></i>
                                        </button>
                                        <span class="adult">0</span>
                                        <button type="button" class="increment">
                                            <i class="fa-regular fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="count-single">
                                    <div class="count-single-text">
                                        <h6>@lang('Children')</h6>
                                        <p>@lang('Below 12 Years')</p>
                                    </div>
                                    <div class="count-single-inner">
                                        <button type="button" class="decrementTwo">
                                            <i class="fa-regular fa-minus"></i>
                                        </button>
                                        <span class="childeren">0</span>
                                        <button type="button" class="incrementTwo">
                                            <i class="fa-regular fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="count-single">
                                    <div class="count-single-text">
                                        <h6>@lang('Pet')</h6>
                                    </div>
                                    <div class="count-single-inner">
                                        <button type="button" class="decrementThree">
                                            <i class="fa-regular fa-minus"></i>
                                        </button>
                                        <span class="room">0</span>
                                        <button type="button" class="incrementThree">
                                            <i class="fa-regular fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-1">
                            <div class="btn-wrapper">
                                <div class="main-text btn-single">
                                    <i class="fa-regular fa-magnifying-glass"></i>
                                    <span class="d-md-none d-xl-block">{{ $banner_section_three['single']['search_button'] ?? '' }}</span>
                                </div>
                                <div class="hover-text btn-single">
                                    <i class="fa-regular fa-magnifying-glass"></i>
                                    <span class="d-md-none d-xl-block">{{ $banner_section_three['single']['search_button'] ?? '' }}</span>
                                </div>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        @php
            $videoPath = $banner_section_three['single']['media']->video_file->path ?? null;
            $videoDriver = $banner_section_three['single']['media']->video_file->driver ?? null;
        @endphp

        @if ($videoPath && $videoDriver)
            <video class="banner-section-three-video" autoplay loop muted playsinline>
                <source
                    class="banner-section-three-video-source"
                    src="{{ getFile($videoDriver, $videoPath) }}"
                    type="video/mp4"
                />
            </video>
        @endif
    </section>
@endif

