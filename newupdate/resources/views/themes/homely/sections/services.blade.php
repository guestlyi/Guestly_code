@if(isset($services))
    <section class="services">
        <div class="container">
            <div class="row g-4">
                @foreach($services['multiple'] ?? [] as $key => $item)
                    @php
                        $isActive = ($loop->iteration % 2 != 0) ? 'active' : '';
                    @endphp
                    <div class="col-lg-3 col-md-6">
                        <div class="services-single {{ $isActive }}">
                            <div class="service-icon-box">
                                <div class="service-icon">
                                    <img src="{{ getFile($item['media']->image->driver, $item['media']->image->path) }}" alt="icon" />
                                </div>
                                <div class="service-number">{{ '0'.$key+1 }}</div>
                            </div>
                            <div class="services-content">
                                <h5>{{ $item['title'] ?? '' }}</h5>
                                <p>
                                    {{ $item['description'] ?? '' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

