@if(isset($counter))
    <section class="counter">
        <div class="container">
            <div class="common-title text-center">
                <h3>{{ $counter['single']['title'] ?? '' }}</h3>
            </div>
            <div class="counter-container">
                <div class="row">
                    @foreach($counter['multiple'] as $item)
                        <div class="col-lg-3 col-md-6">
                            <div class="counter-single">
                                <div class="counter-single-inner">
                                    <div class="odometer-box">
                                        <h2 class="odometer" data-count="{{ $item['counter_data'] ?? 0 }}">00</h2>
                                        <h2 class="odometer-text">{{ $item['counter_aft'] ?? '' }}</h2>
                                    </div>
                                    <p>{{ $item['description'] ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endif

