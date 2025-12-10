@if(isset($service_three))
    <div class="services-three serviceThreeTwo">
        <div class="container">
            <div class="common-title">
                <h3>{{ $service_three['single']['title'] ?? '' }}</h3>
            </div>
            <div class="row g-4">
                @foreach($service_three['multiple'] ?? [] as $item)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="services-single">
                            <div class="service-icon">
                                <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="icon">
                            </div>
                            <div class="services-content">
                                <a>{{ $item['title'] ?? '' }}</a>
                                <p>{{ $item['description'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

