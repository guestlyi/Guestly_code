@extends(template() . 'layouts.app')
@section('title', trans('Properties Images'))
@section('content')
    <div class="img-gallery">
        <div class="container py-5">
            <h2 class="mb-4">@lang('Photo Tour')</h2>

            <div class="d-flex flex-wrap gap-3 mb-4 photo-thumbnails">
                @php
                    $hasUntitled = collect($property->photos->images['images'])->contains(function ($img) {
                        return trim($img['title'] ?? '') === '';
                    });
                @endphp

                @foreach ($property->photos->images['images'] as $image)
                    @php $title = trim($image['title'] ?? ''); @endphp
                    @if ($title)
                        <div class="text-center">
                            <a href="#{{ Str::slug($title) }}" class="d-block text-decoration-none">
                                <img src="{{ getFile($image['driver'], $image['path']) }}" class="img-thumbnail rounded"
                                    alt="{{ $title }}">
                                <div class="small mt-2 text-dark">{{ $title }}</div>
                            </a>
                        </div>
                    @endif
                @endforeach

                @if ($hasUntitled)
                    <div class="text-center">
                        <a href="#other" class="d-block text-decoration-none">
                            @php
                                $firstUntitled = collect($property->photos->images['images'])->first(
                                    fn($img) => trim($img['title'] ?? '') === '',
                                );
                            @endphp
                            @if ($firstUntitled)
                                <img src="{{ getFile($firstUntitled['driver'], $firstUntitled['path']) }}"
                                    class="img-thumbnail rounded" alt="@lang('Other')">
                            @endif
                            <div class="small mt-2 text-dark">@lang('Other')</div>
                        </a>
                    </div>
                @endif
            </div>

            @php
                $grouped = collect($property->photos->images['images'])->groupBy(function ($img) {
                    $title = trim($img['title'] ?? '');
                    return $title === '' ? null : $title;
                });

                $titledGroups = $grouped->filter(fn($_, $key) => !is_null($key) && $key !== '');
                $untitledGroup = $grouped->get(null, []);
            @endphp

            @foreach ($titledGroups as $title => $images)
                <h4 id="{{ Str::slug($title) }}" class="mt-5 mb-3">{{ $title }}</h4>
                <div class="row g-3">
                    @foreach ($images as $img)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="image-wrapper border rounded overflow-hidden">
                                <a data-fancybox="gallery" data-src="{{ getFile($img['driver'], $img['path']) }}"
                                    data-caption="{{ $title }}"><img
                                        src="{{ getFile($img['driver'], $img['path']) }}" class="img-fluid"
                                        alt="{{ $title }}"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            @if (!empty($untitledGroup))
                <h4 id="other" class="mt-5 mb-3">@lang('Other')</h4>
                <div class="row g-3">
                    @foreach ($untitledGroup as $img)
                        <div class="col-6 col-md-4 col-lg-3">
                            <div class="image-wrapper border rounded overflow-hidden">

                                <a data-fancybox="gallery" data-src="{{ getFile($img['driver'], $img['path']) }}"
                                    data-caption="@lang('Other')"><img src="{{ getFile($img['driver'], $img['path']) }}"
                                        class="img-fluid" alt="@lang('Other')"></a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection

@push('style')
    <style>
        .photo-thumbnails img {
            height: 80px;
            object-fit: cover;
            width: 100px;
        }

        .image-wrapper img {
            transition: transform 0.3s ease;
        }

        .image-wrapper:hover img {
            transform: scale(1.03);
        }

        .modal-images {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .modal-image {
            margin: 8px 0;
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
@endpush
