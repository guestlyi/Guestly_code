@if(isset($popular_amenities_seciton))
    <section class="popular-amenities-seciton" style="background-image: url({{ getFile($popular_amenities_seciton['single']['media']->image->driver, $popular_amenities_seciton['single']['media']->image->path) }})">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title split-text">{{ $popular_amenities_seciton['single']['title'] ?? '' }}</h2>
            </div>
            <div class="row g-4">
                @foreach($popular_amenities_seciton['multiple'] ?? [] as $item)
                    <div class="col-lg-3 col-sm-6">
                        <div class="amenities-box">
                            <div class="icon-box">
                                <i class="{{ $item->icon }}"></i>
                            </div>
                            <div class="text-box">
                                <div class="title">{{ $item->title ?? '' }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

