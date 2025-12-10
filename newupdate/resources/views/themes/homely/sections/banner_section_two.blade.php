@if(isset($banner_section_two))
    <input type="hidden" name="home_destinations" id="home_destinations" value="{{ $banner_section_two['home_destinations'] ?? '' }}" />
    <section class="banner-section banner-section-two" style="background-image: url('{{ getFile($banner_section_two['single']['media']->image->driver, $banner_section_two['single']['media']->image->path) }}')">
        <div class="container">
            <div class="banner-section-two-inner">
                <div class="left-side">
                    <div class="subtitle">{{ $banner_section_two['single']['heading'] ?? '' }}</div>
                    <h1 class="banner-section-two-title">
                        {{ $banner_section_two['single']['sub_heading_one'] ?? '' }}
                        <span class="d-inline-flex align-items-center gap-3 flex-wrap justify-content-center">
                          <span class="secondary-text">{{ $banner_section_two['single']['sub_heading_two'] ?? '' }}</span>
                              <span class="video-box">
                                <img src="{{ asset(template(true).'img/banner/shape.png') }}" alt="" />
                                <a href="{{ $banner_section_two['single']['media']->video_link ?? '#' }}"
                                    class="video-play-btn"
                                    data-fancybox
                                >
                                  <i class="fa-solid fa-play"></i>
                                </a>
                              </span>
                          </span>
                        {{ $banner_section_two['single']['sub_heading_three'] ?? '' }}
                    </h1>
                    <a class="btn-2" href="{{ route('services') }}">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                {{ $banner_section_two['single']['button_text'] ?? 'Explore More' }}
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
                                {{ $banner_section_two['single']['button_text'] ?? 'Explore More' }}
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
                <div class="right-side">
                    @if($banner_section_two['destination'])
                    <div class="destination-box">
                        <a href="{{ route('services', ['destination' => $banner_section_two['destination']->slug]) }}" class="img-box">
                            <img src="{{ getFile($banner_section_two['destination']->thumb_driver, $banner_section_two['destination']->thumb ) }}" alt="destination-img"/>
                            <div class="destination-badge">{{ count($banner_section_two['destination']->place).' Attractions' }} </div>
                        </a>
                        <div class="text-box">
                            <a href="{{ route('services', ['destination' => $banner_section_two['destination']->slug]) }}" class="title">{{ $banner_section_two['single']['destination_button_text'] }}
                                <i class="fa-light fa-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="destination-search-wrapper">
            <form action="{{ route('services') }}" method="get">
                <div class="destination-search">
                    <div class="destination-search-inner">
                        <div class="location-search-box">
                            <div class="select-option">
                                <div class="select-icon">
                                    <i class="fa-light fa-location-dot"></i>
                                </div>
                                <div class="select-option-content">
                                    <h6 class="select-option-title">{{ $banner_section_two['single']['search_text_one'] ?? '' }}</h6>
                                    <input type="search" class="soValue optionSearch" placeholder="Search destination" name="search" autocomplete="off"/>
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
                                    <h6 class="select-option-title">{{ $banner_section_two['single']['search_text_two'] ?? '' }}</h6>
                                    <input type="text" id="date-picker" name="datefilter" placeholder="12/12/2024 - 14/12/2024"/>
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
                                    <h6 class="select-option-title">{{ $banner_section_two['single']['search_text_three'] ?? '' }}</h6>
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
                                    <span class="d-md-none d-xl-block">{{ $banner_section_two['single']['search_button'] ?? '' }}</span>
                                </div>
                                <div class="hover-text btn-single">
                                    <i class="fa-regular fa-magnifying-glass"></i>
                                    <span class="d-md-none d-xl-block">{{ $banner_section_two['single']['search_button'] ?? '' }}</span>
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
@endif

