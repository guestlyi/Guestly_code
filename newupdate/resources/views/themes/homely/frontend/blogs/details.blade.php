@extends(template() . 'layouts.app')
@section('title',trans('Blogs'))
@section('content')
    <section class="blog-details">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog-details-wrapper">
                        <div class="blog-details-top-title">
                            <h3>{{ $blogDetails->title ?? '' }}</h3>
                        </div>
                        <div class="blog-meta-list">
                            <ul>
                                <li><i class="fa-light fa-clock pe-1"></i>{{ dateTime(optional($blogDetails->blog)->created_at) }}</li>
                                <li><div class="border"></div></li>
                                <li><i class="fa-light fa-user"></i> @lang('By Admin')</li>
                                <li><div class="border"></div></li>
                                <li><i class="fa-light fa-eye"></i> {{ optional($blogDetails->blog)->total_view ?? 0 }} @lang('times viewed')</li>
                            </ul>
                        </div>
                        <div class="blog-thumbnail">
                            <img src="{{ getFile(optional($blogDetails->blog)->blog_image_driver, optional($blogDetails->blog)->blog_image) }}" alt="{{ $blogDetails->title ?? '' }}">
                        </div>

                        <div class="blog-post-wrapper">
                            <div class="blog-post-title">
                                {!! $blogDetails->description !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="blog-sidebar">

                        <div class="sidebar-content">
                            <div class="sidebar-search">
                                <form action="{{ route('blog') }}" method="GET">
                                    <input type="search" name="search" class="search" placeholder="Search blogs...">
                                    <div class="search-btn">
                                        <button type="submit"><i class="fa-thin fa-magnifying-glass"></i></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="sidebar-content">
                            <div class="recent-post">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="pills-popular-tab" data-bs-toggle="pill" data-bs-target="#pills-popular" type="button" role="tab" aria-controls="pills-popular" aria-selected="true">@lang('Popular')</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-recent-tab" data-bs-toggle="pill" data-bs-target="#pills-recent" type="button" role="tab" aria-controls="pills-recent" aria-selected="false">@lang('Recent')</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="pills-trendy-tab" data-bs-toggle="pill" data-bs-target="#pills-trendy" type="button" role="tab" aria-controls="pills-trendy" aria-selected="false">@lang('Trendy')</button>
                                    </li>
                                </ul>

                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-popular" role="tabpanel" aria-labelledby="pills-popular-tab">
                                        @forelse($popular as $popularItem)
                                            <div class="recent-post-list">
                                                <div class="recent-post-image">
                                                    <img src="{{ getFile($popularItem->blog_image_driver, $popularItem->blog_image) }}" alt="{{ $popularItem->details->title ?? '' }}">
                                                </div>
                                                <div class="recent-post-info">
                                                    <span><i class="fa-light fa-clock pe-1"></i>{{ dateTime($popularItem->created_at) }}</span>
                                                    <a href="{{ route('blog.details', $popularItem->slug) }}">{{ Str::limit($popularItem->details->title ?? '', 50) }}</a>
                                                </div>
                                            </div>
                                        @empty
                                            @include('empty')
                                        @endforelse
                                    </div>

                                    <div class="tab-pane fade" id="pills-recent" role="tabpanel" aria-labelledby="pills-recent-tab">
                                        @forelse($recent as $recentItem)
                                            <div class="recent-post-list">
                                                <div class="recent-post-image">
                                                    <img src="{{ getFile($recentItem->blog_image_driver, $recentItem->blog_image) }}" alt="{{ $recentItem->details->title ?? '' }}">
                                                </div>
                                                <div class="recent-post-info">
                                                    <span><i class="fa-light fa-clock pe-1"></i>{{ dateTime($recentItem->created_at) }}</span>
                                                    <a href="{{ route('blog.details', $recentItem->slug) }}"> {{ Str::limit($recentItem->details->title ?? '', 50) }}</a>
                                                </div>
                                            </div>
                                        @empty
                                            @include('empty')
                                        @endforelse
                                    </div>

                                    <div class="tab-pane fade" id="pills-trendy" role="tabpanel" aria-labelledby="pills-trendy-tab">
                                        @forelse($trending as $trendingItem)
                                            <div class="recent-post-list">
                                                <div class="recent-post-image">
                                                    <img src="{{ getFile($trendingItem->blog_image_driver, $trendingItem->blog_image) }}" alt="{{ $trendingItem->details->title ?? '' }}">
                                                </div>
                                                <div class="recent-post-info">
                                                    <span><i class="fa-light fa-clock pe-1"></i>{{ dateTime($trendingItem->created_at) }}</span>
                                                    <a href="{{ route('blog.details', $trendingItem->slug) }}">{{ Str::limit($trendingItem->details->title ?? '', 50) }}</a>
                                                </div>
                                            </div>
                                        @empty
                                            @include('empty')
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="sidebar-content pb_50">
                            <div class="tag-cloud">
                                <h5>@lang('Tag Cloud')</h5>
                                <div class="tag-cloud-list">
                                    @foreach($tags ?? [] as $tag)
                                        @php
                                            $cleanTag = trim($tag, '[]');
                                            $cleanTag = str_replace('"', '', $cleanTag);
                                        @endphp
                                        <a href="{{ route('blog', ['tag' => $cleanTag]) }}" class="btn-3 other_btn">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    {!! $cleanTag !!}
                                                </div>
                                                <div class="hover-text btn-single">
                                                    {!! $cleanTag !!}
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .btn-3.other_btn{
            background: #fff;
        }
    </style>
@endpush
