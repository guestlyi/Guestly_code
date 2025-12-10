@extends('admin.layouts.app')
@section('page_title', __('Property Style Edit'))
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
                            <li class="breadcrumb-item active" aria-current="page">@lang('Property Style Add')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Property Style Edit')</h1>
                </div>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-lg-10">
                <div class="d-grid gap-3 gap-lg-5">
                    <div class="card pb-3">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title m-0">@lang('Property Style Edit')</h4>
                        </div>
                        <div class="card-body mt-2">
                            <form action="{{ route('admin.propertyStyle.update') }}" method="post"
                                  enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="style_id" value="{{ $style->id }}" />

                                <div class="row mb-4 d-flex align-items-center">
                                    <div class="col-md-6">
                                        <label for="nameLabel" class="form-label">@lang('Name')</label>
                                        <input type="text" class="form-control  @error('name') is-invalid @enderror"
                                               name="name" id="nameLabel" placeholder="Name" aria-label="Name"
                                               autocomplete="off"
                                               value="{{ old('name', $style->name) }}">
                                        @error('name')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <h2 class="card-title h4">@lang("Status")</h2>
                                        <label class="row form-check form-switch" for="breadcrumbSwitch">
                                            <span class="col-8 col-sm-9 ms-0">
                                              <span class="text-dark">@lang("Style Status")
                                                  <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip"
                                                     data-bs-placement="top"
                                                     aria-label="@lang("Enable status for publish")"
                                                     data-bs-original-title="@lang("Enable for user create property")"></i></span>
                                            </span>
                                            <span class="col-4 col-sm-3 text-end">
                                                <input type="hidden" name="status" value="0">
                                                <input type="checkbox" class="form-check-input" name="status"
                                                       id="breadcrumb" value="1" {{ old('status', $style->status) == 1 ? 'checked' : ' ' }}>
                                            </span>
                                        </label>
                                    </div>
                                    <div class="col-12">
                                        <div class="col-md-12">
                                            <label class="form-label card-title mt-2">
                                                @lang('Description')
                                            </label>
                                            <textarea class="summernote @error('description') is-invalid @enderror"
                                                      name="description">{{ old('description', $style->description) }}</textarea>
                                            @error('description')
                                            <span class="invalid-feedback d-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label  class="form-label mt-2">@lang('Image')</label>
                                        <label class="form-check form-check-dashed form-label mt-0" for="image">
                                            <img id="categoryImage"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($style->driver, $style->image) }}"
                                                 alt="@lang("Image")"
                                                 data-hs-theme-appearance="default">

                                            <img id="categoryImage"
                                                 class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                                 src="{{ getFile($style->driver, $style->image) }}"
                                                 alt="@lang("Image")" data-hs-theme-appearance="dark">
                                            <span class="d-block">@lang("Browse your file here")</span>
                                            <input type="file" class="js-file-attach form-check-input"
                                                   name="image" id="image"
                                                   data-hs-file-attach-options='{
                                                                  "textTarget": "#categoryImage",
                                                                  "mode": "image",
                                                                  "targetAttr": "src",
                                                                  "allowTypes": [".png", ".jpeg", ".jpg"]
                                                           }'>
                                        </label>
                                        @error("image")
                                        <span class="invalid-feedback d-block">{{ $message }}</span>
                                        @enderror
                                    </div>


                                </div>

                                <div class="d-flex justify-content-start mt-4">
                                    <button type="submit" class="btn btn-primary submit_btn"><i class="bi-check-circle pe-1"></i>@lang('update')</button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script>
        $(document).ready(function(e) {
            $('#image').on("change",function() {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#categoryImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('.summernote').summernote({
                height: 200,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                }
            });
        });
    </script>
@endpush








