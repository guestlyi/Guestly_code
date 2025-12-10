@if(isset($escape))
    <section class="escape">
        <div class="container">
            <div class="common-title">
                <h3>{{ $escape['single']['title'] ?? '' }}</h3>
            </div>
            <div class="row">
                @foreach($escape['multiple'] ?? [] as $item)
                    <div class="col-lg-3 col-md-6">
                        <div class="popular-two-single">
                            <a href="{{ route('services', ['style' => slug($item->name)]) }}" class="escape-image">
                                <img src="{{ getFile($item->driver, $item->image) }}" alt="{{ $item->name ?? '' }}">
                            </a>
                            <div class="popular-two-content">
                                <a href="{{ route('services', ['style' => slug($item->name)]) }}" class="popular-two-single-title">{{ $item->name ?? '' }}</a>
                                <div class="escape-content-btn">
                                    <a href="{{ route('services', ['style' => slug($item->name)]) }}">{{ $escape['single']['button_name'] ?? 'Explore rental' }}<i class="fa-light fa-angle-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

