@extends(template() . 'layouts.app')
@section('title',trans('Blogs'))
@section('content')
    <section class="blog-page">
        <div class="container">
            <div class="common-title">
                @if(isset($contentDetails))
                    @foreach($contentDetails as $content)
                        <h3>{{ $content->description->title ?? '' }}</h3>
                    @endforeach
                @endif
            </div>
            <div class="row g-4">
                @forelse($blogs as $blog)
                    <div class="col-lg-4 col-md-6">
                        <div class="related-single">
                            <div class="related-single-image">
                                <a href="{{ route('blog.details', $blog->slug) }}"><img src="{{ getFile($blog->blog_image_driver, $blog->blog_image) }}" alt="image"></a>
                            </div>
                            <div class="related-single-content">
                                <span class="blog-meta"><i class="fa-light fa-clock pe-1"></i>{{ dateTime($blog->created_at) }}</span>
                                <a href="{{ route('blog.details', $blog->slug) }}" class="related-single-title">{{ $blog->details->title ?? '' }}</a>
                                <p>{{ strip_tags(Str::limit($blog->details?->description, 100)) }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    @include('empty')
                @endforelse
            </div>
        </div>
    </section>
@endsection
