
@if(isset($blog))
    <section class="blog-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">{{ $blog['single']['title'] ?? '' }}</h2>
            </div>

            <div class="row g-4">
                @foreach($blog['multiple'] ?? [] as $item)
                    <div class="col-lg-4 col-md-6">
                        <div class="blog-box">
                            @if($loop->iteration % 2 != 0)
                                <a class="img-box" href="{{ route('blog.details', $item->slug) }}">
                                    <img
                                        src="{{ getFile($item->blog_image_driver, $item->blog_image) }}"
                                        alt="{{ optional($item->details)->title }}"
                                    />
                                </a>
                                <div class="text-box">
                                    <ul class="blog-meta2">
                                        <li class="item">{{ dateTime($item->created_at) }}</li>
                                        <li class="item tag">
                                            <a href="{{ route('blog.details', $item->slug) }}" class="btn-4">
                                                @lang('Read More') <i class="fa-regular fa-arrow-up-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                    <a href="{{ route('blog.details', $item->slug) }}" class="title">
                                        {{ optional($item->details)->title }}
                                    </a>
                                </div>
                            @else
                                <div class="text-box">
                                    <ul class="blog-meta2">
                                        <li class="item">{{ dateTime($item->created_at) }}</li>
                                        <li class="item tag">
                                            <a href="{{ route('blog.details', $item->slug) }}" class="btn-4">
                                                @lang('Read More') <i class="fa-regular fa-arrow-up-right"></i>
                                            </a>
                                        </li>
                                    </ul>
                                    <a href="{{ route('blog.details', $item->slug) }}" class="title">
                                        {{ optional($item->details)->title }}
                                    </a>
                                </div>
                                <a class="img-box" href="{{ route('blog.details', $item->slug) }}">
                                    <img
                                        src="{{ getFile($item->blog_image_driver, $item->blog_image) }}"
                                        alt="{{ optional($item->details)->title }}"
                                    />
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif

