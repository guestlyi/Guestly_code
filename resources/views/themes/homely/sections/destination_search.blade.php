@if(isset($destination_search))
    <input type="hidden" name="home_destinations" id="home_destinations" value="{{ $destination_search['home_destinations'] ?? '' }}" />

    <div class="container">
        <form action="{{ route('services') }}" method="get">
            <div class="destination-search destination-search-six">
                <div class="destination-search-inner">
                    <div class="location-search-box">
                        <div class="select-option">
                            <div class="select-icon">
                                <i class="fa-light fa-location-dot"></i>
                            </div>
                            <div class="select-option-content">
                                <h6 class="select-option-title">{{ $destination_search['single']['search_text_one'] ?? '' }}</h6>
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
                                <h6 class="select-option-title">{{ $destination_search['single']['search_text_two'] ?? '' }}
                                </h6>
                                <input type="text" id="date-picker" name="datefilter"
                                       placeholder="12/12/2024 - 14/12/2024" />
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
                                <h6 class="select-option-title">
                                    {{ $destination_search['single']['search_text_three'] ?? '' }}
                                </h6>
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
                                <span
                                    class="d-md-none d-xl-block">{{ $destination_search['single']['search_button'] ?? '' }}</span>
                            </div>
                            <div class="hover-text btn-single">
                                <i class="fa-regular fa-magnifying-glass"></i>
                                <span
                                    class="d-md-none d-xl-block">{{ $destination_search['single']['search_button'] ?? '' }}</span>
                            </div>
                        </div>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endif

