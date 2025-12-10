@if (isset($feature_section))
    <section class="feature-section">
        <div class="container">
            <div class="feature-section-inner">
                <div class="row g-4 ">
                    @foreach ($feature_section['multiple'] ?? [] as $key => $item)
                        <div class="col-xl-3 col-lg-4 col-sm-6">
                            <div class="feature-box">
                                <div class="top-box">
                                    <div class="icon-box">
                                        <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}"
                                            alt="{{ $item['title'] ?? '' }}" />
                                    </div>
                                    <div class="number">{{ '0' . $key + 1 }}</div>
                                </div>
                                <div class="bottom-box">
                                    <a href="" class="title">{{ $item['title'] ?? '' }}</a>
                                    <p>
                                        {{ $item['sub_title'] ?? '' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif
