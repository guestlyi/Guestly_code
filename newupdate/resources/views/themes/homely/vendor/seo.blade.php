@extends(template().'layouts.user')
@section('title',trans('Property SEO'))
@section('content')
    <section class="seo-edit-section py-5">
        <div class="container">
            <div class="personal-info-title listing-top pt-5 pb-0">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('Seo')</li>
                    </ul>
                    <h4>@lang('Seo Update')</h4>
                </div>
                <a class="btn btn-outline-secondary" href="{{ route('user.property.list') }}">
                    <i class="fas fa-arrow-left pe-1"></i> @lang('Back')
                </a>
            </div>

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0 fw-semibold">@lang('SEO Meta Settings')</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('user.seo.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="seoable_id" value="{{ $id ?? '' }}">
                        <input type="hidden" name="seoable_type" value="{{ $title ?? '' }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">@lang('Page Title')</label>
                            <input type="text" name="page_title" class="form-control" placeholder="Enter Page Title" value="{{ old('page_title', $seo->page_title ?? '') }}">
                            @error('page_title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">@lang('Meta Title')</label>
                            <input type="text" name="meta_title" class="form-control" placeholder="Enter Meta Title" value="{{ old('meta_title', $seo->meta_title ?? '') }}">

                            @error('meta_title')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Meta Keywords -->
                        <div class="col-sm-12 mb-3">
                            <label for="metaKeywordLabel" class="form-label fw-semibold">@lang('Meta Keywords')</label>
                            <div class="tom-select-custom">
                                <select class="js-select form-select" name="meta_keywords[]" autocomplete="off" multiple
                                        data-hs-tom-select-options='{
                                            "create": true,
                                            "placeholder": "@lang('Type a keyword and press Enter')"
                                        }'>
                                    @if($seo->meta_keywords)
                                        @foreach($seo->meta_keywords as $keyword)
                                            <option value="{{ $keyword }}" selected>{{ $keyword }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error("meta_keywords")
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Meta Description -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">@lang('Meta Description')</label>
                            <textarea name="meta_description" class="form-control" rows="3" placeholder="Write meta description">{{ old("meta_description", $seo->meta_description ?? '') }}</textarea>
                            @error('meta_description')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- OG Description -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold">@lang('OG Description')</label>
                            <textarea name="og_description" class="form-control" rows="3" placeholder="Write Open Graph description">{{ old("og_description", $seo->og_description ?? '') }}</textarea>
                            @error('og_description')
                            <span class="invalid-feedback d-block">{{ $message }}</span >
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <label class="form-label fw-semibold">@lang("Meta Robots")</label>
                            <div class="tom-select-custom tom-select-custom-with-tags">
                                <select class="meta-js-select form-select meta-robot-select2" name="meta_robots[]" autocomplete="off" multiple
                                        data-hs-tom-select-options='{
                                            "placeholder": "@lang('Select Meta Robots')"
                                        }'>
                                    <option value="index" {{ in_array("index", $seo->metaRobots()) ? 'selected' : '' }}>@lang('Index')</option>
                                    <option value="noindex" {{ in_array("noindex", $seo->metaRobots()) ? 'selected' : '' }}>@lang('Noindex')</option>
                                    <option value="follow" {{ in_array("follow", $seo->metaRobots()) ? 'selected' : '' }}>@lang('Follow')</option>
                                    <option value="nofollow" {{ in_array("nofollow", $seo->metaRobots()) ? 'selected' : '' }}>@lang('Nofollow')</option>
                                    <option value="noarchive" {{ in_array("noarchive", $seo->metaRobots()) ? 'selected' : '' }}>@lang('Noarchive')</option>
                                    <option value="nosnippet" {{ in_array("nosnippet", $seo->metaRobots()) ? 'selected' : '' }}>@lang('Nosnippet')</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">@lang('Meta Image')</label>
                            <div class="upload-box text-center p-4 border rounded" id="uploadBox">
                                <div id="uploadContent" class="{{ $seo->meta_image ? 'd-none' : '' }}">
                                    <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                    <p class="upload-text">@lang('Click to upload or drag and drop')</p>
                                    <p class="upload-hint">@lang('JPG, JPEG or PNG (Max. 5MB)')</p>
                                </div>

                                @if($seo->meta_image)
                                    <img id="seoImgPreview" src="{{ getFile($seo->meta_image_driver, $seo->meta_image) }}" alt="Meta Image" class="preview-img mb-3">
                                @else
                                    <img id="seoImgPreview" src="" alt="Meta Image" class="preview-img mb-3 d-none">
                                @endif

                                <input type="file" id="seoImageInput" name="meta_image" class="file-input" accept="image/*">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary px-4 py-2">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <style>
        .seo-edit-section {
            background: #fafafa;
            min-height: 100vh;
        }

        .breadcrumb li {
            display: inline;
            color: #666;
            font-size: 14px;
        }

        .breadcrumb li a {
            color: var(--primary-color);
            text-decoration: none;
        }

        .card-header {
            border-bottom: 1px solid #eee;
        }

        .form-label {
            font-size: 15px;
        }

        .upload-box {
            background: #fff;
            transition: .3s;
            position: relative;
            cursor: pointer;
            border: 2px dashed #ddd !important;
        }

        .upload-box:hover {
            background: #f8f9fa;
            border-color: var(--primary-color) !important;
        }

        .preview-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
        }

        .keyword-wrapper {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 5px;
            background: #fff;
            min-height: 45px;
        }

        .keyword-wrapper ul {
            display: flex;
            flex-wrap: wrap;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .keyword-wrapper li {
            background: var(--primary-color);
            color: #fff;
            border-radius: 4px;
            padding: 3px 8px;
            margin: 3px;
            display: flex;
            align-items: center;
            font-size: 13px;
        }

        .keyword-wrapper li span.remove-keyword {
            margin-left: 6px;
            cursor: pointer;
            font-weight: bold;
        }

        .keyword-wrapper input {
            flex: 1;
            border: none;
            outline: none;
            font-size: 14px;
            padding: 5px;
            min-width: 100px;
        }

        .robots-dropdown {
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 8px;
            background: #fff;
            cursor: pointer;
            position: relative;
        }

        .robots-dropdown::after {
            content: "\f078";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
        }

        .robots-list {
            display: none;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #fff;
            margin-top: 4px;
            position: absolute;
            width: 100%;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .robot-item {
            padding: 8px 12px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .robot-item:hover {
            background: #f3f3f3;
        }

        .robot-item input {
            margin-right: 8px;
        }

        .selected-tags span {
            background: var(--primary-color);
            color: #fff;
            padding: 3px 8px;
            border-radius: 4px;
            margin: 2px;
            font-size: 13px;
            display: inline-block;
        }

        .upload-icon {
            font-size: 24px;
            color: #666;
            margin-bottom: 10px;
        }

        .upload-text {
            font-size: 14px;
            color: #666;
        }

        .file-input {
            display: none;
        }

        .upload-hint {
            font-size: 12px;
            color: #999;
            margin-top: 5px;
        }

        .robots-container {
            position: relative;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('assets/admin/js/hs-file-attach.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            new TomSelect(".js-select", {
                create: true,
                placeholder: "Type a keyword and press Enter"
            });

            new TomSelect(".meta-js-select", {
                create: false,
                persist: false,
                placeholder: "Select Meta Robots",
                plugins: ['remove_button'],
            });

            // -------------------------
            // Meta Image Upload
            // -------------------------
            const uploadBox = document.getElementById('uploadBox');
            const seoImageInput = document.getElementById('seoImageInput');
            const seoImgPreview = document.getElementById('seoImgPreview');
            const uploadContent = document.getElementById('uploadContent');

            uploadBox.addEventListener('click', function() {
                seoImageInput.click();
            });

            seoImageInput.addEventListener('change', handleFileUpload);
            uploadBox.addEventListener('dragover', handleDragOver);
            uploadBox.addEventListener('dragleave', handleDragLeave);
            uploadBox.addEventListener('drop', handleDrop);

            function handleFileUpload() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        seoImgPreview.src = e.target.result;
                        seoImgPreview.classList.remove('d-none');
                        uploadContent.classList.add('d-none');
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            }

            function handleDragOver(e) {
                e.preventDefault();
                uploadBox.style.borderColor = 'var(--primary-color)';
                uploadBox.style.backgroundColor = '#f0f8ff';
            }

            function handleDragLeave() {
                uploadBox.style.borderColor = '#ddd';
                uploadBox.style.backgroundColor = '#fff';
            }

            function handleDrop(e) {
                e.preventDefault();
                uploadBox.style.borderColor = '#ddd';
                uploadBox.style.backgroundColor = '#fff';

                if (e.dataTransfer.files && e.dataTransfer.files[0]) {
                    seoImageInput.files = e.dataTransfer.files;
                    handleFileUpload.call(seoImageInput);
                }
            }

        });
    </script>
@endpush

