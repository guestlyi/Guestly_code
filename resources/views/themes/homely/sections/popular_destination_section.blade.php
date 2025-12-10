@if(isset($popular_destination_section) && 0 <  count($popular_destination_section['destinations']))
    <section class="popular-destination-section">
        <div class="container">
            <div class="popular-destination-grid-container">
                    <div class="destination-box2 large">
                        <div class="img-box">
                            <img src="{{ getFile($popular_destination_section['destinations'][0]->thumb_driver, $popular_destination_section['destinations'][0]->thumb) }}" alt="{{ $popular_destination_section['destinations'][0]->title }}"/>
                            <div class="arrow-btn2-wrapper">
                                <a href="{{ route('services', ['destination' => $popular_destination_section['destinations'][0]->slug]) }}" class="arrow-btn2">
                                    <i class="fa-light fa-arrow-up-right"></i>
                                </a>
                            </div>
                        </div>
                        <div class="text-box">
                            <div class="title">
                                {{ $popular_destination_section['destinations'][0]->title }}
                            </div>
                        </div>
                    </div>

                <div class="destination-box2">
                    <div class="img-box">
                        <img src="{{ getFile($popular_destination_section['destinations'][1]->thumb_driver, $popular_destination_section['destinations'][1]->thumb) }}" alt="{{ $popular_destination_section['destinations'][1]->title }}"/>
                        <div class="arrow-btn2-wrapper">
                            <a href="{{ route('services', ['destination' => $popular_destination_section['destinations'][2]->slug]) }}" class="arrow-btn2">
                                <i class="fa-light fa-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="text-box">
                        <div class="title">
                            {{ $popular_destination_section['destinations'][1]->title }}
                        </div>
                    </div>
                </div>
                <div class="destination-box2">
                    <div class="img-box">
                        <img src="{{ getFile($popular_destination_section['destinations'][2]->thumb_driver, $popular_destination_section['destinations'][2]->thumb) }}" alt="{{ $popular_destination_section['destinations'][2]->title }}"/>
                        <div class="arrow-btn2-wrapper">
                            <a href="{{ route('services', ['destination' => $popular_destination_section['destinations'][2]->slug]) }}" class="arrow-btn2">
                                <i class="fa-light fa-arrow-up-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="text-box">
                        <div class="title">
                            {{ $popular_destination_section['destinations'][2]->title }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

