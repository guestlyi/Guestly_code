@extends('admin.layouts.app')
@section('page_title', __('Badge Create'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Membership Badge')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Badge Create')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Badge Create Form')</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-12">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title m-0">@lang('Add Badge')</h4>
                            <a type="button" href="{{ route('admin.badge.list') }}" class="btn btn-info float-end"><i class="bi bi-arrow-left"></i>@lang('Back')</a>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.badge.store') }}" method="post" enctype="multipart/form-data">
                                @csrf

                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="titleLabel" class="form-label">@lang('Badge Title')</label>
                                        <input type="text" class="form-control  @error('title') is-invalid @enderror"
                                               name="title" id="titleLabel" placeholder="e.g. Legendary Vendor" aria-label="Title"
                                               autocomplete="off"
                                               value="{{ old('title') }}">

                                        @error('title')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row align-items-center mt-4">
                                            <div class="col-sm mb-2 mb-sm-0">
                                                <h5 class="mb-0">@lang('Status')</h5>
                                                <p class="fs-5 text-body mb-0">@lang('Badge status enable or Disable for hide or unhide badge. ')</p>
                                            </div>
                                            <div class="col-sm-auto d-flex align-items-center">
                                                <div class="form-check form-switch form-switch-google">
                                                    <input type="hidden" name="status" value="0">
                                                    <input class="form-check-input" name="status"
                                                           type="checkbox" id="status" value="1">
                                                    <label class="form-check-label"
                                                           for="status"></label>
                                                </div>
                                            </div>
                                        </div>

                                        @error('status')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="col-md-6 mt-5">
                                        <label for="description" class="form-label">@lang("Description")</label>
                                        <textarea class="form-control summernote" name="description" id="description" rows="10">{{ old("description") }}</textarea>
                                        @error("description")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="col-md-6 mt-5">
                                        <div class="card">
                                            <div class="card-body">
                                                <label class="form-label" for="logoUploader">@lang('Icon')</label>
                                                <label class="form-check form-check-dashed @error('icon') is-invalid @enderror" for="logoUploader" id="category_icon">
                                                    <img id="previewImage"
                                                         class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                         src="{{ asset("assets/admin/img/oc-browse-file.svg") }}"
                                                         alt="Image Preview" data-hs-theme-appearance="default">
                                                    <span class="d-block">@lang("Browse your file here")</span>
                                                    <input type="file" class="js-file-attach form-check-input @error('icon') is-invalid @enderror" name="icon"
                                                           id="logoUploader" data-hs-file-attach-options='{
                                                  "textTarget": "#previewImage",
                                                  "mode": "image",
                                                  "targetAttr": "src",
                                                  "allowTypes": [".png", ".jpeg", ".jpg"]
                                               }'>
                                                    @error('icon')
                                                    <span class="invalid-feedback">{{ $message }}</span>
                                                    @enderror
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-start">
                                    <button type="submit"
                                            class="btn btn-primary submit_btn">@lang('Create')</button>
                                </div>

                            </form>
                        </div>

                        @push('script')
                            <script>
                                document.getElementById('logoUploader').addEventListener('change', function() {
                                    let file = this.files[0];
                                    let reader = new FileReader();

                                    reader.onload = function(e) {
                                        document.getElementById('previewImage').src = e.target.result;
                                    }

                                    reader.readAsDataURL(file);
                                });
                            </script>
                        @endpush
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote.min.css') }}">
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.summernote').summernote({
                placeholder: 'Badge Description.',
                height: 200,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                },
            });
        });
    </script>
@endpush
