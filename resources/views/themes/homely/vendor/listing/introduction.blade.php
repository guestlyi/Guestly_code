@extends(template().'layouts.user')
@section('title',trans('Introduction'))
@section('content')
    <section class="listing-details-1 overview">
        <div class="container">
            <div class="listing-top">
                <div class="logo-box">
                    <div class="logo"><a href="{{ route('page','/') }}"><img src="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" alt="logo"></a></div>
                </div>
                <div class="save-btn">
                    <a href="{{ route('user.property.list') }}"  class="btn-1">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                @lang('Save & Exit')
                            </div>
                            <div class="hover-text btn-single">
                                @lang('Save & Exit')
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @foreach($content->contentDetails as $item)
                <div class="row">
                    <div class="col-lg-6">
                        <div class="stand-out-left-container">
                            <div class="common-title">
                                <h3>{{ $item->description?->title ?? '' }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="overview-list">
                            <ul>
                                <li>
                                    <div class="overview-list-content">
                                        <h4><span>1</span> {{ $item->description?->list_title_one ?? '' }}</h4>
                                        <p>{{ $item->description?->list_description_one ?? '' }}</p>
                                    </div>
                                    <div class="overview-list-image">
                                        <img src="{{ getFile($content->media?->image_one?->driver, $content->media?->image_one?->path) }}" alt="image">
                                    </div>
                                </li>
                                <li>
                                    <div class="overview-list-content">
                                        <h4><span>2</span> {{ $item->description?->list_title_two ?? '' }}</h4>
                                        <p>{{ $item->description?->list_description_two ?? '' }}</p>
                                    </div>
                                    <div class="overview-list-image">
                                        <img src="{{ getFile($content->media?->image_two?->driver, $content->media?->image_two?->path) }}" alt="image">
                                    </div>
                                </li>
                                <li>
                                    <div class="overview-list-content">
                                        <h4><span>3</span> {{ $item->description?->list_title_three ?? '' }}</h4>
                                        <p>{{ $item->description?->list_description_three ?? '' }}</p>
                                    </div>
                                    <div class="overview-list-image">
                                        <img src="{{ getFile($content->media?->image_three?->driver, $content->media?->image_three?->path) }}" alt="image">
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                <a href="#0" class="prev-btn"></a>
                <a href="{{ route('user.listing.about.your.place') }}" class="next-btn">@lang('Get started')</a>
            </div>

        </div>
    </section>
@endsection
@push('script')
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                @foreach ($errors->all() as $error)
                Notiflix.Notify.failure(@json($error));
                @endforeach
            });
        </script>
    @endif
@endpush
