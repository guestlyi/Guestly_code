@extends(template().'layouts.user')
@section('title',trans('Safety'))
@section('content')
    <section class="listing-details-1 listing-location">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')
            <form id="safetyForm" action="{{ route('user.listing.safety.save') }}" method="post">
                @csrf

                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id ?? '' }}">

                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="safety-container">
                            <h2>@lang('Share safety details')</h2>
                            <div class="question-title">
                                @lang('Does your place have any of these?')
                                <span title="Select all that apply">🛈</span>
                            </div>

                            <div class="checkbox-group">
                                @php
                                    $coreItems = [];

                                    if (isset($property->safety_items) && is_array($property->safety_items)) {
                                        $coreItems = $property->safety_items['core'] ?? [];
                                    } elseif (is_string($property->safety_items)) {
                                        $decoded = json_decode($property->safety_items, true);
                                        $coreItems = is_array($decoded) ? ($decoded['core'] ?? []) : [];
                                    }

                                    if (is_array(old('safety'))) {
                                        $coreItems = old('safety');
                                    }
                                @endphp
                                <label>
                                    @lang('Exterior security camera present')
                                    <input type="checkbox" name="safety[]" value="camera"
                                        {{ in_array('camera', $coreItems) ? 'checked' : '' }}>
                                </label>

                                <label>
                                    @lang('Noise decibel monitor present')
                                    <input type="checkbox" name="safety[]" value="monitor"
                                        {{ in_array('monitor', $coreItems) ? 'checked' : '' }}>
                                </label>

                                <label>
                                    @lang('Weapon(s) on the property')
                                    <input type="checkbox" name="safety[]" value="weapon"
                                        {{ in_array('weapon', $coreItems) ? 'checked' : '' }}>
                                </label>


                                <div id="custom-safety-container">
                                    @php
                                        $others = [];
                                        if (isset($property->safety_items) && is_array($property->safety_items)) {
                                            $others = $property->safety_items['others'] ?? [];
                                        } elseif (is_string($property->safety_items)) {
                                            $decoded = json_decode($property->safety_items, true);
                                            $others = is_array($decoded) ? ($decoded['others'] ?? []) : [];
                                        }
                                    @endphp

                                    @foreach ($others as $index => $item)
                                        <div class="custom-safety-item mb-2">
                                            <div class="custom-safety-wrapper position-relative w-100">
                                                <input type="text" name="safety_custom[{{ $index }}][label]" value="{{ $item }}"
                                                       class="form-control pe-5" placeholder="@lang('Custom safety item')">
                                                <button type="button" class="position-absolute delete-feature remove-custom-safety" aria-label="Remove"><i class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-custom-safety">
                                    @lang('Add Custom Option')
                                </button>
                            </div>

                            <div class="info-box">
                                <strong>@lang('Important things to know')</strong>
                                @lang("Security cameras that monitor indoor spaces are not allowed even if they're turned off. All exterior security cameras must be disclosed.")
                            </div>
                        </div>
                    </div>
                </div>

                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.discounts', ['property_id' => $property->id]) }}" class="prev-btn"> @lang('Back')</a>
                    <button type="submit" class="next-btn"> @lang('Next')</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .safety-container {
            max-width: 600px;
            margin: auto;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .question-title {
            font-weight: bold;
            margin-bottom: 15px;
        }

        .checkbox-group {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-bottom: 40px;
        }

        label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
        }

        input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }

        .info-box {
            border-top: 1px solid #ddd;
            padding-top: 30px;
            font-size: 14px;
            color: #555;
        }

        .info-box strong {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        .info-box a {
            color: #008489;
            text-decoration: underline;
        }

        .info-box a:hover {
            text-decoration: none;
        }

        .custom-safety-wrapper {
            position: relative;
            margin-top: 10px;
        }

        .custom-safety-wrapper input[type="text"] {
            padding-right: 2.5rem;
        }

        .delete-btn-inside {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            background-color: transparent;
            border: none;
            color: #dc3545;
            font-size: 14px;
            cursor: pointer;
        }

        .delete-btn-inside:hover {
            color: #a71d2a;
        }
        .position-absolute.delete-feature {
            top: -10px;
            right: -11px;
            width: 24px;
            height: 23px;
            background-color: #dc3545;
            color: #fff;
            border: 3px solid #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
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
            font-size: 11px;
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
@endpush

@push('script')
    <script>
        document.getElementById('add-custom-safety').addEventListener('click', function () {
            const container = document.getElementById('custom-safety-container');
            const index = container.querySelectorAll('.custom-safety-item').length;

            const wrapper = document.createElement('div');
            wrapper.classList.add('custom-safety-item', 'mb-2');

            wrapper.innerHTML = `
            <div class="custom-safety-wrapper position-relative w-100">
                <input type="text" name="safety_custom[${index}][label]" class="form-control pe-5" placeholder="@lang('Custom safety item')">
                <button type="button" class="position-absolute delete-feature remove-custom-safety" aria-label="Remove"><i class="fas fa-times"></i></button>
            </div>
        `;

            container.appendChild(wrapper);
        });

        document.addEventListener('click', function (e) {
            if (e.target.closest('.remove-custom-safety')) {
                e.target.closest('.custom-safety-item').remove();
            }
        });

        const form = document.getElementById('safetyForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.rules') }}';

        @include(template().'vendor.listing.partials.cmn_script')
    </script>
@endpush
