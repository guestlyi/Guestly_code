@extends(template().'layouts.user')
@section('title',trans('Listing availability and features'))
@section('content')
    <section class="listing-details-1 listing-location">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')

            <form id="availabilityForm" action="{{ route('user.listing.availablityAndFeature.save') }}" method="post">
                @csrf

                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id ?? '' }}">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <h3>@lang('Share information about your property availability')</h3>
                        <p>@lang('This is core information for your listing item.')</p>

                        <div class="location-form">
                            <div class="mb-3">
                                <label for="available_from" class="form-label">@lang('Available From')</label>
                                <input type="text" name="available_from" id="available_from" class="form-control" placeholder="Select start date" value="{{ old('available_from', optional($property->availability)->available_from) }}">
                            </div>

                            <div class="mb-3">
                                <label for="available_to" class="form-label">@lang('Available To')<sub>(@lang('optional'))</sub></label>
                                <input type="text" name="available_to" id="available_to" class="form-control" placeholder="Select end date" value="{{ old('available_to', optional($property->availability)->available_to) }}">
                            </div>

                            @php
                                $existingFeatures = [];
                                if (!empty($property->features->others)) {
                                    $existingFeatures = is_array($property->features->others)
                                        ? $property->features->others
                                        : json_decode($property->features->others, true);
                                }
                            @endphp

                            <div class="mb-3 d-flex flex-column">
                                <label for="addCustomFeature" class="form-label">@lang('Custom Feature')<sub>(@lang('optional'))</sub></label>


                                <button type="button" class="btn-3 mb-4" id="addCustomFeature">
                                    <div class="btn-wrapper">
                                        <div class="main-text btn-single">
                                            <i class="fa fa-plus-circle pe-1"></i> @lang('Custom Feature')
                                        </div>
                                        <div class="hover-text btn-single">
                                            <i class="fa fa-plus-circle pe-1"></i> @lang('Custom Feature')
                                        </div>
                                    </div>
                                </button>
                            </div>

                            <div id="customFeatureWrapper">
                                @foreach($existingFeatures as $name => $enabled)
                                    <div class="custom-feature-group mb-4 border p-3 rounded position-relative">
                                        <button type="button" class=" position-absolute delete-feature" aria-label="Remove"><i class="fas fa-times"></i></button>
                                        <div class="d-flex align-items-center flex-wrap justify-content-between mb-3">
                                            <label class="form-label" for="custom_features[{{ $loop->index }}]">@lang('Custom Feature Name')</label>
                                            <div class="form-check">
                                                <label class="form-check-label" for="custom_feature_enabled_{{ $loop->index }}">
                                                    @lang('Enable this feature')
                                                </label>
                                                <input class="form-check-input inputCheckBx" type="checkbox" name="custom_features[{{ $loop->index }}][enabled]"
                                                       id="custom_feature_enabled_{{ $loop->index }}" value="1" {{ $enabled == '1' ? 'checked' : '' }}>
                                            </div>
                                        </div>

                                        <input type="text" name="custom_features[{{ $loop->index }}][name]" class="form-control mb-2" value="{{ $name }}" id="custom_features">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.informations', ['property_id' => $property->id]) }}" class="prev-btn"> @lang('Back')</a>
                    <button type="submit" class="next-btn"> @lang('Next')</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset(template(true).'css/flatpickr.min.css') }}">
    <style>
        .position-absolute.delete-feature {
            top: -14px;
            right: -14px;
            width: 32px;
            height: 32px;
            background-color: #dc3545;
            color: #fff;
            border: 3px solid #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            z-index: 10;
            cursor: pointer;
            transition: transform 0.2s ease, background-color 0.3s ease;
        }
        .position-absolute.delete-feature:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }
        .position-absolute.delete-feature i {
            pointer-events: none;
            font-size: 14px;
        }
        .custom-feature-group .form-check {
            display: flex;
            align-items: center;
            justify-content: start;
            gap: 8px;
            flex-direction: row-reverse;
        }
        .custom-feature-group .form-check .inputCheckBx{
            margin-left: 2px;
            cursor: pointer;
        }
    </style>
@endpush
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

    <script src="{{ asset(template(true).'js/flatpickr.min.js') }}"></script>

    <script>

        flatpickr("#available_from", {
            dateFormat: "Y-m-d",
            minDate: "today",
            disableMobile: true,
        });

        flatpickr("#available_to", {
            dateFormat: "Y-m-d",
            minDate: "today",
            disableMobile: true,
        });

        let featureIndex = {{ count($existingFeatures) }};

        document.getElementById('addCustomFeature').addEventListener('click', function () {
            const wrapper = document.getElementById('customFeatureWrapper');

            const featureGroup = document.createElement('div');
            featureGroup.classList.add('custom-feature-group', 'mb-4', 'border', 'p-3', 'rounded', 'position-relative');

            featureGroup.innerHTML = `
                <button type="button" class=" position-absolute delete-feature" aria-label="Remove"><i class="fas fa-times"></i></button>
                <div class="d-flex align-items-center flex-wrap justify-content-between mb-3">
                    <label class="form-label" for="custom_features[${featureIndex}]">@lang('Custom Feature Name')</label>
                    <div class="form-check">
                        <label class="form-check-label" for="custom_feature_enabled_${featureIndex}">
                                @lang('Enable this feature')
                        </label>
                        <input class="form-check-input inputCheckBx" type="checkbox" name="custom_features[${featureIndex}][enabled]" value="1" id="custom_feature_enabled_${featureIndex}">

                    </div>
                </div>

                <input type="text" name="custom_features[${featureIndex}][name]" class="form-control mb-2" placeholder="@lang('Enter feature name')" id="custom_features[${featureIndex}]">


            `;

            wrapper.appendChild(featureGroup);
            featureIndex++;
        });

        document.getElementById('customFeatureWrapper').addEventListener('click', function (e) {
            if (e.target.classList.contains('delete-feature')) {
                e.target.closest('.custom-feature-group').remove();
            }
        });

        const form = document.getElementById('availabilityForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.stand.out') }}';


        @include(template().'vendor.listing.partials.cmn_script')

    </script>
@endpush
