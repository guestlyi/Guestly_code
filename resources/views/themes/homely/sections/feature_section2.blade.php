@if(isset($feature_section2))
    <section class="feature-section2">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title split-text">{{ $feature_section2['single']['title'] }}</h2>
            </div>
            <div class="feature-section2-inner">
                <div class="row g-4">
                    @foreach($feature_section2['multiple'] ?? [] as $key => $item)
                        <div class="col-lg-3 col-sm-6 feature-box2-item">
                            <div class="feature-box2 box{{ $key+1 }}">
                                <div class="top-box">
                                    <div class="icon-box">
                                        <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="{{ $item['title'] ?? '' }}" />
                                    </div>
                                    <div class="number">{{ '0'.$key + 1 }}</div>
                                </div>

                                <div class="bottom-box">
                                    <h3 class="title">{{ $item['title'] ?? '' }}</h3>
                                    <p>
                                        {{ $item['sub_title'] ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="circle">
                    <div class="circle-inner"></div>
                </div>
            </div>
        </div>
    </section>
@endif

