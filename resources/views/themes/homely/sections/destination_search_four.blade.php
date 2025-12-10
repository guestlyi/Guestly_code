@if(isset($destination_search_four))
    <input type="hidden" name="home_destinations" id="home_destinations" value="{{ $destination_search_four['home_destinations'] ?? '' }}" />

    <section class="add-home-search">
        <div class="container">
            <div class="destination-search-three-container">
                <form action="{{ route('services') }}" method="get">
                    <div class="location-search-box">
                        <div class="select-option">
                            <div class="select-icon">
                                <i class="fa-thin fa-location-dot"></i>
                            </div>
                            <div class="select-option-content">
                                <h6 class="select-option-title">{{ $destination_search_four['single']['location_title'] }}</h6>
                                <input type="search" class="soValue optionSearch" placeholder="@lang('e.g. Srinagar')" name="search" value="" autocomplete="off">
                            </div>
                            <button type="submit" class="destination-search-three-submit"><i class="fa-light fa-magnifying-glass"></i></button>
                        </div>
                        <div class="location-search-dropdown">
                            <ul class="search-options top-search-options" id="search-results"></ul>
                            <button class="show-more" style="display:none;">@lang('Show More')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endif

