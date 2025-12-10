@extends(template().'layouts.user')
@section('title',trans('Property SEO'))
@section('content')
    <section class="listing-details-1 stand-out mb-4">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('Seo')</li>
                    </ul>
                    <h4>@lang('Seo Update')</h4>
                </div>
                <a class="btn-4" href="{{ route('user.property.list') }}"><i class="fas fa-arrow-left pe-1"></i>@lang('back')</a>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-lg-12">
                    <div class="d-grid gap-3 gap-lg-5">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="card-title h4">@lang('Edit SEO Meta')</h2>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('user.property.seo.update') }}" method="post"
                                      enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="property_id" value="{{ $property->id }}">

                                    <div class="row">
                                        <div class="col-sm-12 mb-3">
                                            <label for="PageTitleLabel" class="form-label">@lang('Page Title')</label>
                                            <input type="text"
                                                   class="cmn-input @error('page_title') is-invalid @enderror"
                                                   name="page_title" id="PageTitleLabel"
                                                   placeholder="@lang("Page Title")" aria-label="@lang("Page Title")"
                                                   value="{{ old('page_title', $property->seo?->page_title) }}" autocomplete="off">
                                            @error('page_title')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 mb-3">
                                            <label for="SeoTitleLabel" class="form-label">@lang('Meta Title')</label>
                                            <input type="text"
                                                   class="cmn-input @error('meta_title') is-invalid @enderror"
                                                   name="meta_title" id="SeoTitleLabel"
                                                   placeholder="@lang("Meta Title")" aria-label="@lang("Meta Title")"
                                                   value="{{ old('meta_title', $property->seo?->meta_title) }}">
                                            @error('meta_title')
                                                <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 mb-3">
                                            <label for="metaKeywordInput" class="form-label">@lang('Meta Keywords')</label>
                                            <div id="keyword-wrapper" class="cmn-input d-flex flex-wrap gap-2" onclick="document.getElementById('metaKeywordInput').focus()" style="min-height: 40px;">
                                                @php
                                                    $keywords = is_array($property->seo?->meta_keywords)
                                                        ? $property->seo->meta_keywords
                                                        : json_decode($property->seo?->meta_keywords ?? '[]', true);
                                                @endphp

                                                @foreach($keywords as $keyword)
                                                    <span class="keyword-tag badge bg-light text-dark border border-secondary pb-2">
                                                        {{ $keyword }}
                                                        <input type="hidden" name="meta_keywords[]" value="{{ $keyword }}">
                                                        <button type="button" class="btn-close btn-sm ms-2" aria-label="Remove"></button>
                                                    </span>
                                                @endforeach

                                                <input type="text" id="metaKeywordInput" class="border-0 flex-grow-1" style="min-width: 120px;" placeholder="@lang('Type and press Enter')">
                                            </div>

                                            @error("meta_keywords")
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-sm-12 mb-4">
                                            <label for="metaDescription"
                                                   class="form-label">@lang('Meta Description')</label>
                                            <textarea id="metaDescription" class="cmn-input" name="meta_description" placeholder="@lang("Meta Description")" rows="5">{{ old("meta_description", $property->seo?->meta_description) }}</textarea>
                                            @error('meta_description')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="col-sm-12 mb-4">
                                            <label for="ogDescription"
                                                   class="form-label">@lang('OG Description')</label>
                                            <textarea id="ogDescription" class="cmn-input" name="og_description"
                                                      placeholder="@lang("OG Description")"
                                                      rows="4">{{ old("og_description", $property->seo?->og_description) }}</textarea>
                                            @error('og_description')
                                            <span class="invalid-feedback d-block">{{ $message }}</span >
                                            @enderror
                                        </div>

                                    </div>
                                    @php
                                        $metaRobots = $property->seo?->getMetaRobots() ?? [];
                                        $robotOptions = ['index', 'noindex', 'follow', 'nofollow', 'noarchive', 'nosnippet'];
                                    @endphp

                                    <div class="row mb-3">
                                        <label class="form-label" for="metaRobotsInputs">@lang("Meta Robots")</label>
                                        <div class="custom-multiselect border rounded p-2 cmn-input" id="metaRobotsDropdown">
                                            <span class="placeholder text-muted">@lang('Select Meta Robots')</span>
                                            <div id="metaRobotsSelected" class="d-inline"></div>
                                        </div>

                                        <div id="metaRobotsInputs">
                                            @foreach($metaRobots as $tag)
                                                <input type="hidden" name="meta_robots[]" value="{{ $tag }}">
                                            @endforeach
                                        </div>

                                        <div id="metaRobotsList" class="border mt-1 p-2 d-none metaRobotList">
                                            @foreach($robotOptions as $option)
                                                <div class="dropdown-item d-flex justify-content-between align-items-center" data-value="{{ $option }}">
                                                    <span>{{ ucfirst($option) }}</span>
                                                    <span class="checkmark d-none text-success">&#10003;</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-5 mb-3 mb-md-0">
                                            <label class="form-label">@lang("Meta Image")</label>

                                            <label class="form-check form-check-dashed" for="imageUploader">
                                                <img id="SeoImg"
                                                     class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                     src="{{ getFile($property->seo?->meta_image_driver, $property->seo?->meta_image, true) }}"
                                                     alt="Image Description">
                                                <span class="d-block">@lang("Browse your file here")</span>
                                                <input type="file" class="js-file-attach form-check-input cmn-input"
                                                       id="imageUploader" name="seo_meta_image"
                                                       data-hs-file-attach-options='{
                                                  "textTarget": "#SeoImg",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg", ".json"]
                                               }'>
                                            </label>
                                            @error('seo_meta_image')
                                            <span class="invalid-feedback">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mt-4">
                                        <button type="submit"  class="btn-3">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    @lang('Save changes')
                                                </div>
                                                <div class="hover-text btn-single">
                                                    @lang('Save changes')
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </form>
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
        .placeholder{
            background-color: transparent !important;
        }
        .custom-multiselect{
            min-height: 45px;
            cursor: pointer;
            margin: 0 10px;
            width: 99% !important;
        }
        .metaRobotList{
            position: relative;
            background: #fff;
            z-index: 1000;
            top: -5px;
            left: 12px;
            max-width: 400px;
            width: 100%;
            border-radius: 5px;
        }
        #metaRobotsList .dropdown-item {
            padding: 6px 12px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }
        #metaRobotsList .dropdown-item.active {
            background-color: #f0f8ff;
        }
        #metaRobotsList .checkmark {
            font-size: 16px;
        }
    </style>
@endpush

@include(template().'vendor.properties.partials.seo_script')
