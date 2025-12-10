@extends('admin.layouts.app')
@section('page_title',__('Property Edit'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link" href="javascript:void(0)">@lang("Dashboard")</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang("Properties")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Property Edit")</h1>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form action="{{ route('admin.property.update') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="property_id" id="property_id" value="{{ $property->id }}"  />

                    <div class="card mb-3 mb-lg-5">
                        <div class="card-header d-flex align-items-center justify-content-between">

                            <h4 class="card-header-title"><span class="pe-1">@lang('Property information')</span>{!! $property->statusMessage !!}</h4>
                            <div class="d-flex align-items-center justify-content-end gap-2">

                                <a class="btn btn-icon btn-sm btn-white" data-bs-toggle="modal" data-bs-target="#action"
                                   href="#">
                                    <i class="bi-list-ul me-1"></i>
                                </a>
                                <a type="button" href="{{ route('admin.all.property') }}" class="btn btn-info float-end btn-sm"><i class="bi bi-arrow-left"></i>@lang('Back')</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label for="propertyTitle" class="form-label">@lang('Title') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Type property title here..."></i></label>
                                        <input type="text" class="form-control" name="title" id="propertyTitle" placeholder="e.g. Entire villa in Mashobra Living Himachal Pradesh" aria-label="title" value="{{ old('title', $property->title) }}">

                                        @error('name')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label for="slugLabel" class="form-label">@lang('Slug') <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Slug will be auto-generated based on the title."></i></label>
                                        <input type="text" class="form-control" name="slug" id="slugLabel" placeholder="e.g. entire-villa-in-mashobra-living-himachal-pradesh" aria-label="slug" value="{{ old('slug', $property->slug) }}" readonly>

                                        @error('slug')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row mb-4">
                                        <div class="col-md-4 col-sm-12">
                                            <label class="form-label" for="nightPriceLabel">@lang('Per Night Price')
                                                <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Per night rent price for this property."></i></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control wid1" name="nightly_rate" id="nightPriceLabel" value="{{ old('nightly_rate', $property->pricing?->nightly_rate) }}" placeholder="e.g 500" aria-label="@lang('nightly rate')">
                                                <h5 class="form-control wid2 mb-0 d-flex align-items-center justify-content-center">{{ basicControl()->base_currency }}</h5>
                                            </div>
                                            @error('nightly_rate')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <label class="form-label" for="weekPriceLabel">@lang('Per Week Price')
                                                <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Per week rent price for this property."></i></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control wid1" name="weekly_rate" id="weekPriceLabel" value="{{ old('weekly_rate', $property->pricing?->weekly_rate) }}" placeholder="e.g 500" aria-label="@lang('weekly rate')">
                                                <h5 class="form-control wid2 mb-0 d-flex align-items-center justify-content-center">{{ basicControl()->base_currency }}</h5>
                                            </div>
                                            @error('weekly_rate')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <label class="form-label" for="monthPriceLabel">@lang('Per month Price')
                                                <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Per week rent price for this property."></i></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control wid1" name="monthly_rate" id="monthPriceLabel" value="{{ old('monthly_rate', $property->pricing?->monthly_rate) }}" placeholder="e.g 500" aria-label="@lang('monthly rate')">
                                                <h5 class="form-control wid2 mb-0 d-flex align-items-center justify-content-center">{{ basicControl()->base_currency }}</h5>
                                            </div>
                                            @error('monthly_rate')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 col-sm-12 pt-2">
                                            <label class="form-label" for="cleaningFeeLabel">@lang('Cleaning Fee')
                                                <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Cleaning charge for this property."></i></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control wid1" name="cleaning_fee" id="cleaningFeeLabel" value="{{ old('cleaning_fee', $property->pricing?->cleaning_fee) }}" placeholder="e.g 500" aria-label="@lang('Cleaning Fee')">
                                                <h5 class="form-control wid2 mb-0 d-flex align-items-center justify-content-center">{{ basicControl()->base_currency }}</h5>
                                            </div>
                                            @error('cleaning_fee')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 col-sm-12 pt-2">
                                            <label class="form-label" for="serviceFeeLabel">@lang('Service Fee')
                                                <i class="bi-question-circle text-body ms-1" data-bs-toggle="tooltip" data-bs-placement="top" title="Service charge for this property."></i></label>
                                            <div class="input-group">
                                                <input type="text" class="form-control wid1" name="service_fee" id="serviceFeeLabel" value="{{ old('service_fee', $property->pricing?->service_fee) }}" placeholder="e.g 500" aria-label="@lang('Service Charge')">
                                                <h5 class="form-control wid2 mb-0 d-flex align-items-center justify-content-center">{{ basicControl()->base_currency }}</h5>
                                            </div>
                                            @error('service_fee')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-4 col-sm-12">
                                        <label class="form-label" for="bedroomsLabel">@lang('Total Bedrooms')</label>
                                        <input type="text" class="form-control" name="bedrooms" id="bedroomsLabel" value="{{ old('bedrooms', $property->features?->bedrooms) }}" placeholder="e.g 5" aria-label="@lang('Total Bedrooms')">

                                        @error('bedrooms')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label class="form-label" for="bathroomsLabel">@lang('Total Bathrooms')</label>
                                        <input type="text" class="form-control" name="bathrooms" id="bathroomsLabel" value="{{ old('bathrooms', $property->features?->bathrooms) }}" placeholder="e.g 5" aria-label="@lang('Total Bathrooms')">

                                        @error('bathrooms')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label class="form-label" for="guestsLabel">@lang('Maximum Guests')</label>
                                        <input type="text" class="form-control" name="max_guests" id="guestsLabel" value="{{ old('max_guests', $property->features?->max_guests) }}" placeholder="e.g 5" aria-label="@lang('Maximum Number of Guests')">

                                        @error('max_guests')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4 col-sm-12">
                                        <label class="form-label" for="category_id">@lang('Category')</label>
                                        <select class="form-control js-select" id="category_id" name="category_id">
                                            <option value="" disabled>@lang('Select Category')</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $property->category_id) == $category->id ? 'selected' : '' }}>
                                                    @lang($category->name)
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('category_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label class="form-label" for="type_id">@lang('Type')</label>
                                        <select class="form-control js-select" id="type_id" name="type_id">
                                            <option value="" disabled>@lang('Select Type')</option>
                                            @foreach($types as $type)
                                                <option value="{{ $type->id }}"
                                                    {{ old('category_id', $property->type_id) == $type->id ? 'selected' : '' }}>
                                                    @lang($type->name)
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('type_id')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 col-sm-12">
                                        <label class="form-label" for="style_id">@lang('Style')</label>
                                        <select class="form-control js-select" id="style_id" name="style_id">
                                            <option value="" disabled>@lang('Select Style')</option>
                                            @foreach($styles as $style)
                                                <option value="{{ $style->id }}"
                                                    {{ old('category_id', $property->style_id) == $style->id ? 'selected' : '' }}>
                                                    @lang($style->name)
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('style_id')
                                        <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    @php
                                        $amenitiesData = null;
                                        if ($property->allAmenity !== null && is_string($property->allAmenity->amenities)) {
                                            $amenitiesData = json_decode($property->allAmenity->amenities, true);
                                        } elseif ($property->allAmenity !== null) {
                                            $amenitiesData = $property->allAmenity->amenities;
                                        }

                                        $selectedAmenities = collect(array_merge(
                                            $amenitiesData['amenity'] ?? [],
                                            $amenitiesData['favourites'] ?? [],
                                            $amenitiesData['safety_item'] ?? []
                                        ));
                                    @endphp
                                    <div class="mb-4">
                                        <label class="form-label" for="amenities">@lang('Amenities')</label>
                                        <select class="form-select form-control" id="amenities" multiple="multiple" name="amenities_id[]">
                                            @foreach ($amenities ?? [] as $item)
                                                <option value="{{ $item->id }}" {{ $selectedAmenities->contains((string) $item->id) ? 'selected' : '' }}>
                                                    @lang($item->title)
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('amenities_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="destination_id">@lang('Destination')</label>
                                        <select class="form-control js-select" id="destination_id" name="destination_id">
                                            <option value="" disabled>@lang('Select Destination')</option>
                                            @foreach($destinations as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ old('destination_id', $property->destination_id) == $item->id ? 'selected' : '' }}>
                                                    @lang($item->title)
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('destination_id')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="available_from">@lang('Available Form')</label>
                                        <input type="date" class="form-control" name="available_from" id="available_from" value="{{ old('available_from', $property->availability?->available_from) }}" placeholder="e.g 2024-03-10" aria-label="@lang('available from')">

                                        @error('available_from')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="available_to">@lang('Available To')</label>
                                        <input type="date" class="form-control" name="available_to" id="available_to" value="{{ old('available_to', $property->availability?->available_to) }}" placeholder="e.g 2024-03-10" aria-label="@lang('available to')">

                                        @error('available_to')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="address">@lang('Address')</label>
                                        <input type="text" class="form-control" name="address" id="address" value="{{ old('address', $property->address) }}" placeholder="e.g 14/3 Khan Street" aria-label="@lang('Address')">

                                        @error('address')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label" for="zipCode">@lang('Zip Code')</label>
                                        <input type="text" class="form-control" name="zip_code" id="zipCode" value="{{ old('address', $property->zip_code) }}" placeholder="e.g 1000" aria-label="@lang('Zip Code')">

                                        @error('zip_code')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="row mb-4">
                                        <div class="col-md-4 col-sm-12">
                                            <label class="form-label" for="country">@lang('Country')</label>
                                            <input name="country" class="form-control" value="{{ old('country', $property->country) }}"  />
                                            @error('country')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <label class="form-label" for="state">@lang('State')</label>
                                            <input name="state" class="form-control" value="{{ old('state', $property->state) }}"  />
                                            @error('state')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-4 col-sm-12">
                                            <label class="form-label" for="city">@lang('City')</label>
                                            <input name="city" class="form-control" value="{{ old('city', $property->city) }}"  />

                                            @error('city')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="mb-4">
                                        <label class="form-label" for="details">@lang('Description')</label>
                                        <textarea
                                            name="description"
                                            class="form-control summernote"
                                            cols="30"
                                            rows="5"
                                            id="description"
                                            placeholder="@lang('Write Here Description ... ')"
                                        >{{ old('description', $property->description) }}</textarea>

                                        @error('description')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                l
                                <div class="row">
                                    <div class="col-12 mb-4">
                                        <label class="form-label">@lang('Refund Rules')</label>

                                        <div id="refund-rules-wrapper">
                                            @php
                                                $refundInfos = old('refund_infos', $property->pricing?->refund_infos ?? []);
                                            @endphp

                                            @foreach($refundInfos as $index => $rule)
                                                <div class="card mb-3 refund-rule-item">
                                                    <div class="card-body row g-3">
                                                        <div class="col-md-3">
                                                            <label class="form-label">@lang('Refund Percentage')</label>
                                                            <input type="number" name="refund_infos[{{ $index }}][percentage]"
                                                                   class="form-control"
                                                                   value="{{ $rule['percentage'] ?? '' }}"
                                                                   placeholder="e.g. 100">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label class="form-label">@lang('Days Before Check-in')</label>
                                                            <input type="number" name="refund_infos[{{ $index }}][days]"
                                                                   class="form-control"
                                                                   value="{{ $rule['days'] ?? '' }}"
                                                                   placeholder="e.g. 10">
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="form-label">@lang('Message')</label>
                                                            <textarea name="refund_infos[{{ $index }}][message]"
                                                                      class="form-control"
                                                                      rows="2"
                                                                      placeholder="Write message...">{{ $rule['message'] ?? '' }}</textarea>
                                                        </div>
                                                        <div class="col-md-1 d-flex align-items-end refundInfoDeleteArea">
                                                            <button type="button" class="btn btn-danger btn-sm remove-refund-rule">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <!-- Add button -->
                                        <button type="button" class="btn btn-outline-primary btn-sm" id="add-refund-rule">
                                            <i class="fas fa-plus"></i> @lang('Add Refund Rule')
                                        </button>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <div class="row align-items-center">
                                            <div class="col">
                                                <label class="form-label">@lang('Refundable')</label>
                                                <span class="d-block fs-6 text-body">
                                                @lang('Refundable or not after booking cancelation')
                                            </span>
                                            </div>
                                            <div class="col-auto">
                                                <label class="row form-check form-switch mb-3" for="refundable">
                                                <span class="col-4 col-sm-3 text-end">
                                                    <input type='hidden' value='0' name='refundable'>
                                                     <input
                                                         class="form-check-input @error('refundable') is-invalid @enderror"
                                                         type="checkbox" name="refundable"
                                                         id="refundable"
                                                         value="1" {{($property->pricing?->refundable == 1) ? 'checked' : ''}}>
                                                </span>
                                                    @error('refundable')
                                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                                    @enderror
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $others = $property->features?->others ?? [];
                                @endphp

                                <div class="row">
                                    <div class="col-md-12 mb-4">
                                        <div class="row align-items-center">
                                            <div id="featuresWrapper" class="d-flex align-items-center justify-content-between">
                                                <div class="col">
                                                    <div class="d-flex justify-content-start align-items-center">
                                                        <label class="form-label">@lang('Other Features')</label>
                                                    </div>
                                                    <span class="d-block fs-6 text-body">
                                                        @lang('Select applicable features for this property')
                                                    </span>
                                                </div>
                                                <div>
                                                    <div id="featuresContainer">
                                                        @foreach($others as $key => $value)
                                                            <label class="row form-check form-switch mb-3" for="{{ $key }}">
                                                                <span class="col-auto text-capitalize">{{ str_replace('_', ' ', $key) }}</span>
                                                                <span class="col-4 col-sm-3 text-end">
                                                                    <input type="hidden" value="0" name="others[{{ $key }}]">
                                                                    <input
                                                                        class="form-check-input @error('others.'.$key) is-invalid @enderror"
                                                                        type="checkbox" name="others[{{ $key }}]"
                                                                        id="{{ $key }}"
                                                                        value="1" {{ (!empty($value) && $value == '1') ? 'checked' : '' }}>
                                                                </span>

                                                                @error('others.'.$key)
                                                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                                                @enderror
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                    <button type="button" id="addFeatureButton" class="btn btn-white btn-sm">
                                                        <i class="bi bi-plus-circle pe-1"></i>@lang('Add New')
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3 mb-lg-5">
                        <div class="card-body">
                            <label class="form-label" >@lang('Thumbnail Image')</label>
                            <label class="form-check form-check-dashed" for="logoUploader" id="content_img">
                                <img id="previewImage"
                                     class="avatar avatar-xl avatar-4x3 avatar-centered h-100 mb-2"
                                     src="{{ getFile($property->photos?->images['thumb']['driver'], $property->photos?->images['thumb']['path']) }}"
                                     alt="Image Preview" data-hs-theme-appearance="default">
                                <span class="d-block">@lang("Browse your file here")</span>
                                <input type="file" class="js-file-attach form-check-input" name="thumb"
                                       id="logoUploader" data-hs-file-attach-options='{
                                                                  "textTarget": "#previewImage",
                                                                  "mode": "image",
                                                                  "targetAttr": "src",
                                                                  "allowTypes": [".png", ".jpeg", ".jpg"]
                                                               }'>
                            </label>
                            <p class="pt-2">@lang('For better resolution, please use an image with a size of') {{ config('filelocation.propertyThumb.size') }} @lang(' pixels.')</p>
                            @error('thumb')
                            <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="card mb-3 mb-lg-5">
                        <div class="card-body">
                            <label class="form-label" for="propertyImage">@lang('Images')</label>
                            <div class="input-images" id="propertyImage"></div>
                            @if($errors->has('images'))
                                <span class="invalid-feedback d-block">
                                    <strong>{{ $errors->first('images') }}</strong>
                                </span>
                            @endif
                            <p class="pt-2">@lang('For better resolution, please use an image with a size of') {{ config('filelocation.property.size') }} @lang(' pixels.')</p>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">@lang("Save")</button>
                </form>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fa fa-history"></i> @lang('Activity Log')</h5>
                        <ul class="step mt-4">
                            @forelse($activity as $k => $row)
                                <li class="step-item {{ $loop->last ? 'last-step' : '' }}">
                                    <div class="step-content-wrapper">
                                        <div class="step-avatar">
                                            <img class="step-avatar-img"
                                                 src="{{getFile(optional($row->activityable)->image_driver,optional($row->activityable)->image)}}"
                                                 alt="{{optional($row->activityable)->username}}">
                                        </div>

                                        <div class="step-content">
                                            <h5 class="mb-1">@lang($row->title) ({{diffForHumans($row->created_at)}}
                                                )</h5>

                                            <p class="fs-5 mb-1">@lang($row->description)
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <div class="text-center ms-6 p-4">
                                    <img class="dataTables-image mb-3"
                                         src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description"
                                         data-hs-theme-appearance="default">
                                    <img class="dataTables-image mb-3"
                                         src="{{ asset('assets/admin/img/oc-error-light.svg') }}"
                                         alt="Image Description" data-hs-theme-appearance="dark">
                                    <p class="mb-0">@lang('No data to show')</p>
                                </div>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="action" data-bs-backdrop="static" tabindex="-1" role="dialog"
         aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> @lang('Action')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.property.action') }}" method="post">
                    @csrf

                    <input type="hidden" name="property_id" value="{{$property->id}}">
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label class="font-weight-bold mb-2">@lang('Status') </label>
                            <select id="status" class="form-control js-select" name="status" aria-label=".form-select-lg example" required>
                                <option value="" selected disabled>@lang('Select Status')</option>
                                <option value="1" {{ ( $property->status == 1) ? 'selected' : '' }}>@lang('Approve')</option>
                                <option value="3" {{ ( $property->status == 3) ? 'selected' : '' }}>@lang('Hold')</option>
                                <option value="4" {{ ( $property->status == 4) ? 'selected' : '' }}>@lang('Soft Rejected')</option>
                                <option value="5" {{ ( $property->status == 5) ? 'selected' : '' }}>@lang('Hard Rejected')</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="comments" class="font-weight-bold mb-2"> @lang('Comment') </label>
                            <textarea name="comments" rows="4" class="form-control" value="" required></textarea>

                            @error('comments')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-white" data-bs-dismiss="modal">@lang('Close')</button>
                        <button type="submit" class="btn btn-soft-primary"><span>@lang('Submit')</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/image-uploader.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/tom-select.bootstrap5.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/summernote-bs5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/flatpickr.min.css') }}">

    <style>
        .note-editor.note-frame{
            border: .0625rem solid rgba(231, 234, 243, .7) !important;
        }
        .image-uploader{
            border:  .0625rem solid rgba(231, 234, 243, .7) !important;
        }
        .form-label {
            font-weight: bold;
            margin-bottom: 0;
        }

        .text-body {
            color: #6c757d;
            margin-top: 5px;
        }
        .step-item.last-step .step-avatar::after {
            content: none;
        }
        .refund-rule-item{
            position: relative;
        }
        .refundInfoDeleteArea{
            position: absolute;
            top: -9px;
            right: -20px;
        }
    </style>
@endpush
@push('script')
    <script src="{{ asset('assets/admin/js/summernote-bs5.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/tom-select.complete.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/image-uploader.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/timepicker-bs4.js') }}"></script>
    <script src="{{ asset('assets/admin/js/jquery.dateandtime.js') }}"></script>
    <script>
        "use strict";
        flatpickr('#available_to', {
            enableTime: false,
            dateFormat: "Y-m-d",
            minDate: 'today'
        });
        flatpickr('#available_from', {
            enableTime: false,
            dateFormat: "Y-m-d",
            minDate: 'today'
        });
        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.getElementById('propertyTitle');
            const slugInput = document.getElementById('slugLabel');
            nameInput.addEventListener('input', function () {
                slugInput.value = generateSlug(nameInput.value);
            });

            function generateSlug(text) {
                return text
                    .toString()
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/[^a-z0-9 -]/g, '')
                    .trim()
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
            }
        });
        document.getElementById('logoUploader').addEventListener('change', function() {
            let file = this.files[0];
            let reader = new FileReader();

            reader.onload = function(e) {
                document.getElementById('previewImage').src = e.target.result;
            }

            reader.readAsDataURL(file);
        });
        $(document).ready(function(){

            $(document).on('click', '.delete_desc', function () {
                $(this).closest('.input-group').parent().remove();
            });

            $('.summernote').summernote({
                height: 200,
                disableDragAndDrop: true,
                callbacks: {
                    onBlurCodeview: function () {
                        let codeviewHtml = $(this).siblings('div.note-editor').find('.note-codable').val();
                        $(this).val(codeviewHtml);
                    }
                }
            });

            ['#category_id', '#destination_id', '#type_id', '#style_id', '#amenities', '#country', '#state', '#city', '#status'].forEach(id => {
                HSCore.components.HSTomSelect.init(id, {
                    maxOptions: 250,
                    placeholder: `Select ${id.replace('#', '').replace('_', ' ')}`
                });
            });

            let images = @json($images);
            let oldImage = @json($oldimg);
            let preloaded = [];

            images.forEach(function(value, index) {
                preloaded.push({
                    id: oldImage[index],
                    src: value,
                });
            });

            $('.input-images').imageUploader({
                preloaded: preloaded
            });

            $('#country').on('change', function () {
                let idCountry = this.value;

                if ($('#state')[0].tomselect) {
                    $('#state')[0].tomselect.destroy();
                }
                $("#state").html('');

                $.ajax({
                    url: "{{route('admin.fetch.state')}}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "country_id": idCountry,
                    },
                    dataType: 'json',
                    success: function (result) {
                        let stateOptions = '<option value="">-- Select State --</option>';
                        $.each(result.states, function (key, value) {
                            stateOptions += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $("#state").html(stateOptions);

                        HSCore.components.HSTomSelect.init('#state', {
                            maxOptions: 250,
                            placeholder: 'Select State'
                        });
                    }
                });
            });

            //City Dropdown
            $('#state').on('change', function () {
                let idState = this.value;

                if ($('#city')[0].tomselect) {
                    $('#city')[0].tomselect.destroy();
                }

                $("#city").html('');

                $.ajax({
                    url: "{{route('admin.fetch.city')}}",
                    type: "POST",
                    data: {
                        state_id: idState,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (res) {
                        let cityOptions = '<option value="">-- Select City --</option>';
                        $.each(res.cities, function (key, value) {
                            cityOptions += '<option value="' + value.id + '">' + value.name + '</option>';
                        });
                        $("#city").html(cityOptions);

                        HSCore.components.HSTomSelect.init('#city', {
                            maxOptions: 250,
                            placeholder: 'Select City'
                        });
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            const addFeatureButton = document.getElementById('addFeatureButton');
            const featuresContainer = document.getElementById('featuresContainer');

            addFeatureButton.addEventListener('click', function() {
                const featureContainer = document.createElement('div');
                featureContainer.className = 'row form-check form-switch mb-3 align-items-center';

                const inputGroup = document.createElement('div');
                inputGroup.className = 'input-group col me-2';

                const inputField = document.createElement('input');
                inputField.type = 'text';
                inputField.placeholder = 'Enter feature name';
                inputField.className = 'form-control';

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'btn btn-white btn-sm';
                removeButton.innerHTML = '<i class="bi bi-trash"></i>';
                removeButton.addEventListener('click', function() {
                    featureContainer.remove();
                });

                const checkboxContainer = document.createElement('span');
                checkboxContainer.className = 'col-auto';

                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.value = '0';

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.value = '1';
                checkbox.className = 'form-check-input';

                checkbox.addEventListener('change', function() {
                    checkbox.value = checkbox.checked ? '1' : '0';
                    hiddenInput.value = checkbox.checked ? '0' : '0';
                });

                inputField.addEventListener('input', function() {
                    const inputName = inputField.value.trim().replace(/\s+/g, '_');

                    checkbox.name = `others[${inputName}]`;
                    hiddenInput.name = `others[${inputName}]`;
                    inputField.name = `others[${inputName}]`;
                });

                checkboxContainer.appendChild(hiddenInput);
                checkboxContainer.appendChild(checkbox);

                inputGroup.appendChild(inputField);
                inputGroup.appendChild(removeButton);

                featureContainer.appendChild(inputGroup);
                featureContainer.appendChild(checkboxContainer);

                featuresContainer.appendChild(featureContainer);

                featuresContainer.appendChild(addFeatureButton);
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            let wrapper = document.getElementById('refund-rules-wrapper');
            let addBtn = document.getElementById('add-refund-rule');
            let index = {{ count($refundInfos) }};

            addBtn.addEventListener('click', function () {
                let html = `
                <div class="card mb-3 refund-rule-item">
                    <div class="card-body row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Refund Percentage</label>
                            <input type="number" name="refund_infos[${index}][percentage]" class="form-control" placeholder="e.g. 100">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Days Before Check-in</label>
                            <input type="number" name="refund_infos[${index}][days]" class="form-control" placeholder="e.g. 10">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Message</label>
                            <textarea name="refund_infos[${index}][message]" class="form-control" rows="2" placeholder="Write message..."></textarea>
                        </div>
                        <div class="col-md-1 d-flex align-items-end refundInfoDeleteArea">
                            <button type="button" class="btn btn-danger btn-sm remove-refund-rule">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
                wrapper.insertAdjacentHTML('beforeend', html);
                index++;
            });

            // Remove rule
            wrapper.addEventListener('click', function (e) {
                if (e.target.closest('.remove-refund-rule')) {
                    e.target.closest('.refund-rule-item').remove();
                }
            });
        });
    </script>
@endpush

