@if(isset($releted))
    <section class="related">
        <div class="container">
            <div class="common-title">
                <h3>{{ $releted['single']['title'] ?? '' }}</h3>
            </div>
            <div class="row g-4">
                @foreach($releted['releteds'] ?? [] as $item)
                    <div class="col-lg-4 col-md-6">
                        <div class="related-single">
                            <div class="related-single-image">
                                <a href="{{ route('blog.details', $item->slug) }}"><img src="{{ getFile($item->blog_image_driver, $item->blog_image) }}" alt="{{ $item->details?->title ?? '' }}"></a>
                            </div>
                            <div class="related-single-content">
                                <span class="blog-meta"><i class="fa-light fa-clock pe-1"></i>{{ dateTime($item->created_at) }}</span>
                                <a href="{{ route('blog.details', $item->slug) }}" class="related-single-title">{{ $item->details?->title ?? '' }}</a>
                                <p>{{ strip_tags(Str::limit($item->details?->description, 80)) }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

