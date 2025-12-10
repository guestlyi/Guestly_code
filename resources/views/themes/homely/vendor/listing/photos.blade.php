@extends(template().'layouts.user')
@section('title',trans('Images'))
@section('content')
    <section class="listing-details-1 photo-page">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')
            <form id="photosForm" action="{{ route('user.listing.photos.save') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id ?? '' }}">
                @php
                    $imagesData = [];

                    if ($property->photos) {
                        $imagesRaw = $property->photos->images;

                        if (is_string($imagesRaw)) {
                            $imagesData = json_decode($imagesRaw, true);
                        } elseif (is_array($imagesRaw)) {
                            $imagesData = $imagesRaw;
                        }
                    }

                    $thumb = null;
                    if (isset($imagesData['thumb'])) {
                        $thumb = getFile($imagesData['thumb']['driver'] ?? 'local', $imagesData['thumb']['path'] ?? '');
                    }

                    $existingImages = [];
                    if (isset($imagesData['images']) && is_array($imagesData['images'])) {
                        foreach ($imagesData['images'] as $img) {
                            $existingImages[] = [
                                'url' => getFile($img['driver'] ?? 'local', $img['path'] ?? ''),
                                'title' => $img['title'] ?? '',
                            ];
                        }
                    }
                @endphp
                <div class="row photo-area">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="texts">
                                <h3>@lang('Thumbnail Image')</h3>
                                <p>@lang('Select Thumbnail')</p>
                            </div>
                            @if(isAiAccess())
                                <div>
                                    <button type="button"
                                            class="btn-ai-glow"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#imagesGenerateOffcanvas"
                                            aria-controls="imagesGenerateOffcanvas">
                                        <i class="fas fa-bolt me-2"></i> @lang('Use AI')
                                    </button>
                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="imagesGenerateOffcanvas" aria-labelledby="imagesGenerateOffcanvasLabel">
                                        <div class="offcanvas-header">
                                            <h5 id="offcanvasRightLabel"><i class="bi-gear me-1"></i>@lang('Generate image with '. basicControl()->site_title.' AI assistant')</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <span class="text-dark font-weight-bold">
                                                @lang('Bring your tour packages to life with AI-generated images! 🖼️ Let us create stunning visuals to boost engagement and bookings. Ready to generate your images?')
                                            </span>

                                            <div class="col-12 mb-4 mt-4">
                                                <span class="text-cap text-body form-label mb-2" >@lang("Describe about your image")</span>
                                                <textarea class="form-control" id="generateImageTitle" rows="4" placeholder="@lang('e.g. Tropical beach at sunset with turquoise water, white sand, palm trees, and happy couple walking.')" autocomplete="off"></textarea>
                                            </div>

                                            <div class="col-12 mb-4">
                                                <span class="text-cap text-body form-label mb-2">@lang("Image Type")</span>
                                                <select class="form-control js-select" id="imageType">
                                                    <option value="thumbnail">@lang('Thumbnail')</option>
                                                    <option value="images">@lang('Images')</option>
                                                </select>
                                            </div>

                                            <div class="col-12 mb-4 d-none" id="imageCountWrapper">
                                                <span class="text-cap text-body d-block mb-2">
                                                    @lang("Number Of Images ")
                                                    <i class="fas fa-question-circle"
                                                       data-bs-toggle="tooltip"
                                                       data-bs-placement="top"
                                                       title="@lang('Choose how many images you want to generate (Max 3)')">
                                                    </i>
                                                </span>

                                                <div class="d-flex gap-2" id="imageCountOptions">
                                                    <div class="image-count-box" data-value="1">1</div>
                                                    <div class="image-count-box" data-value="2">2</div>
                                                    <div class="image-count-box" data-value="3">3</div>
                                                </div>

                                                <input type="hidden" name="image_count" id="imageCount">
                                            </div>

                                            <div class="row gx-2">
                                                <div class="col">
                                                    <div class="d-grid">
                                                        <button type="button" class="btn-ai-glow" id="generateImageBtn">
                                                            <i class="far fa-bolt pe-1"></i> @lang('Generate')
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="previewImages"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="photos-container">
                            <div class="photo-content">
                                <div class="photo-content-image">
                                    <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="display: block; height: 64px; width: 64px; fill: currentcolor;"><path d="M41.636 8.404l1.017 7.237 17.579 4.71a5 5 0 0 1 3.587 5.914l-.051.21-6.73 25.114A5.002 5.002 0 0 1 53 55.233V56a5 5 0 0 1-4.783 4.995L48 61H16a5 5 0 0 1-4.995-4.783L11 56V44.013l-1.69.239a5 5 0 0 1-5.612-4.042l-.034-.214L.045 14.25a5 5 0 0 1 4.041-5.612l.215-.035 31.688-4.454a5 5 0 0 1 5.647 4.256zm-20.49 39.373l-.14.131L13 55.914V56a3 3 0 0 0 2.824 2.995L16 59h21.42L25.149 47.812a3 3 0 0 0-4.004-.035zm16.501-9.903l-.139.136-9.417 9.778L40.387 59H48a3 3 0 0 0 2.995-2.824L51 56v-9.561l-9.3-8.556a3 3 0 0 0-4.053-.009zM53 34.614V53.19a3.003 3.003 0 0 0 2.054-1.944l.052-.174 2.475-9.235L53 34.614zM48 27H31.991c-.283.031-.571.032-.862 0H16a3 3 0 0 0-2.995 2.824L13 30v23.084l6.592-6.59a5 5 0 0 1 6.722-.318l.182.159.117.105 9.455-9.817a5 5 0 0 1 6.802-.374l.184.162L51 43.721V30a3 3 0 0 0-2.824-2.995L48 27zm-37 5.548l-5.363 7.118.007.052a3 3 0 0 0 3.388 2.553L11 41.994v-9.446zM25.18 15.954l-.05.169-2.38 8.876h5.336a4 4 0 1 1 6.955 0L48 25.001a5 5 0 0 1 4.995 4.783L53 30v.88l5.284 8.331 3.552-13.253a3 3 0 0 0-1.953-3.624l-.169-.05L28.804 14a3 3 0 0 0-3.623 1.953zM21 31a4 4 0 1 1 0 8 4 4 0 0 1 0-8zm0 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM36.443 6.11l-.175.019-31.69 4.453a3 3 0 0 0-2.572 3.214l.02.175 3.217 22.894 5.833-7.74a5.002 5.002 0 0 1 4.707-4.12L16 25h4.68l2.519-9.395a5 5 0 0 1 5.913-3.587l.21.051 11.232 3.01-.898-6.397a3 3 0 0 0-3.213-2.573zm-6.811 16.395a2 2 0 0 0 1.64 2.496h.593a2 2 0 1 0-2.233-2.496zM10 13a4 4 0 1 1 0 8 4 4 0 0 1 0-8zm0 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"></path></svg>
                                </div>
                                <h4>@lang('Drag and drop')</h4>
                                <p>@lang('or browse for photo')</p>
                                <div class="photo-input">
                                    <label for="thumbUpload" class="btn-1">@lang('Upload Image')</label>
                                    <input type="file" class="imageInput" id="thumbUpload" name="thumb" accept="image/*">
                                </div>
                            </div>
                            <div class="thumb-preview">
                                @if ($thumb)
                                    <div class="thumb-item">
                                        <img src="{{ $thumb }}" alt="Thumbnail" class="preview-image" />
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 offset-lg-3 mt-4">
                        <h3>@lang('Images')</h3>
                        <p>@lang('Select Images') <sub>(@lang(' maximum 19 photos'))</sub></p>
                        <div class="photos-container">
                            <div class="photo-content">
                                <div class="photo-content-image">
                                    <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" role="presentation" focusable="false" style="display: block; height: 64px; width: 64px; fill: currentcolor;"><path d="M41.636 8.404l1.017 7.237 17.579 4.71a5 5 0 0 1 3.587 5.914l-.051.21-6.73 25.114A5.002 5.002 0 0 1 53 55.233V56a5 5 0 0 1-4.783 4.995L48 61H16a5 5 0 0 1-4.995-4.783L11 56V44.013l-1.69.239a5 5 0 0 1-5.612-4.042l-.034-.214L.045 14.25a5 5 0 0 1 4.041-5.612l.215-.035 31.688-4.454a5 5 0 0 1 5.647 4.256zm-20.49 39.373l-.14.131L13 55.914V56a3 3 0 0 0 2.824 2.995L16 59h21.42L25.149 47.812a3 3 0 0 0-4.004-.035zm16.501-9.903l-.139.136-9.417 9.778L40.387 59H48a3 3 0 0 0 2.995-2.824L51 56v-9.561l-9.3-8.556a3 3 0 0 0-4.053-.009zM53 34.614V53.19a3.003 3.003 0 0 0 2.054-1.944l.052-.174 2.475-9.235L53 34.614zM48 27H31.991c-.283.031-.571.032-.862 0H16a3 3 0 0 0-2.995 2.824L13 30v23.084l6.592-6.59a5 5 0 0 1 6.722-.318l.182.159.117.105 9.455-9.817a5 5 0 0 1 6.802-.374l.184.162L51 43.721V30a3 3 0 0 0-2.824-2.995L48 27zm-37 5.548l-5.363 7.118.007.052a3 3 0 0 0 3.388 2.553L11 41.994v-9.446zM25.18 15.954l-.05.169-2.38 8.876h5.336a4 4 0 1 1 6.955 0L48 25.001a5 5 0 0 1 4.995 4.783L53 30v.88l5.284 8.331 3.552-13.253a3 3 0 0 0-1.953-3.624l-.169-.05L28.804 14a3 3 0 0 0-3.623 1.953zM21 31a4 4 0 1 1 0 8 4 4 0 0 1 0-8zm0 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4zM36.443 6.11l-.175.019-31.69 4.453a3 3 0 0 0-2.572 3.214l.02.175 3.217 22.894 5.833-7.74a5.002 5.002 0 0 1 4.707-4.12L16 25h4.68l2.519-9.395a5 5 0 0 1 5.913-3.587l.21.051 11.232 3.01-.898-6.397a3 3 0 0 0-3.213-2.573zm-6.811 16.395a2 2 0 0 0 1.64 2.496h.593a2 2 0 1 0-2.233-2.496zM10 13a4 4 0 1 1 0 8 4 4 0 0 1 0-8zm0 2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"></path></svg>
                                </div>
                                <h4>@lang('Drag and drop')</h4>
                                <p>@lang('or browse for photos')</p>
                                <div class="photo-input">
                                    <label for="fileUpload" class="btn-1">@lang('Upload Image')</label>
                                    <input type="file" class="imageInput" id="fileUpload" name="images[]" accept="image/*" multiple>
                                </div>
                            </div>
                            <div class="image-preview images-preview"></div>
                            <div id="hiddenInputs"></div>
                            <div class="title-input-box" id="titleInputBox">
                                <label for="titleInput" class="form-label d-flex align-items-center">@lang('Image Title')</label>
                                <input type="text" id="titleInput" class="cmn-input" placeholder="@lang('Enter image title')" />
                                <div class="title-btn-group">
                                    <button type="button" class="btn-3 save-title" id="saveTitleBtn">
                                        <div class="btn-wrapper">
                                            <div class="main-text btn-single">
                                                @lang('Save')
                                            </div>
                                            <div class="hover-text btn-single">
                                                @lang('Save')
                                            </div>
                                        </div>
                                    </button>
                                    <button type="button" class="btn-1 cancel-title" id="cancelTitleBtn">
                                        <div class="btn-wrapper">
                                            <div class="main-text btn-single">
                                                @lang('Cancel')
                                            </div>
                                            <div class="hover-text btn-single">
                                                @lang('Cancel')
                                            </div>
                                        </div>
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.amenities', ['property_id' => $property->id]) }}" class="prev-btn">@lang('Back')</a>
                    <button type="submit" class="next-btn">@lang('Next')</button>
                </div>
            </form>
        </div>
    </section>
@endsection
@push('script')
    <style>
        .image-count-box {
            width: 75px;
            height: 40px;
            border: 2px solid #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
            background-color: #fff;
            transition: all 0.2s ease-in-out;
        }

        .image-count-box:hover {
            border-color: var(--border-2);
            background-color: var(--primary-color);
            color: #fff;
        }

        .image-count-box.active {
            border-color: var(--border-2);
            background-color: var(--primary-color);
            color: #fff;
        }
    </style>
@endpush
@include(template().'vendor.listing.partials.photos_script')
