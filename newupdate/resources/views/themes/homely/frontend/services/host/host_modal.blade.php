<div class="modal fade" id="hostModal" tabindex="-1" aria-labelledby="hostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content host-modal-content">
            <div class="modal-header">
                <h5 class="modal-title fs-5" id="hostModalLabel">@lang('Host Info')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="categories-modal-content">
                    <a href="#0" class="host-modal-info">
                        <div class="host-info-left">
                            <div class="host-modal-image">
                                <img id="hostModalImage" src="" alt="image">
                            </div>
                            <div class="host-modal-designation">
                                <h4 id="hostModalName"></h4>
                                <h6 id="hostModalDesignation"><i class="fa-solid fa-user-tie"></i></h6>
                            </div>
                        </div>
                        <div class="host-info-right">
                            <div class="host-modal-info-list">
                                <ul>
                                    <li>
                                        <h4 id="hostModalReviews">0</h4>
                                        <h6>@lang('Reviews')</h6>
                                    </li>
                                    <li>
                                        <h4 id="hostModalRating">0</h4>
                                        <h6>@lang('Rating')</h6>
                                    </li>
                                    <li>
                                        <h4 id="hostModalYears">0</h4>
                                        <h6>@lang('Years hosting')</h6>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </a>
                    <div class="host-modal-other-info">
                        <ul>
                            <li><i class="fa-light fa-briefcase"></i> <span id="hostModalWork"></span></li>
                            <li><i class="fa-light fa-language"></i> <span id="hostModalLanguages"></span></li>
                            <li><i class="fa-light fa-globe-stand"></i> <span id="hostModalLocation"></span></li>
                        </ul>
                        <p id="hostModalDescription"></p>
                    </div>
                    <div class="host-modal-testimonial">
                        <h4>@lang('What guests are saying about') <span class="hostname"></span></h4>
                        <div class="host-modal-slider p_relative">
                            <div class="swiper single-item-carousel">
                                <div class="swiper-wrapper"></div>
                            </div>

                            <div class="swiper-button-next"><i class="fa-duotone fa-light fa-angle-right"></i></div>
                            <div class="swiper-button-prev"><i class="fa-duotone fa-light fa-angle-left"></i></div>
                        </div>
                    </div>
                    <div class="host-modal-about">
                        <h4 class="mb-3" >@lang('Popular amenities for ')<span class="askHostName"></span></h4>
                        <div class="amenities-list">
                            <ul id="amenitiesList">

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .single-item-carousel {
        width: 100%;
        overflow: hidden;
    }

    .swiper-slide {
        width: 100%;
        flex-shrink: 0;
    }
</style>

