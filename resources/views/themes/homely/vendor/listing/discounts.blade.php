@extends(template().'layouts.user')
@section('title',trans('Discounts'))
@section('content')
    <section class="listing-details-1 listing-location">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')

            <form id="discountForm" action="{{ route('user.listing.discounts.save') }}" method="post">
                @csrf

                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id ?? '' }}">

                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="discount-container">
                            <h1 class="discount-title">@lang('Add discounts')</h1>
                            <p class="discount-subtitle">@lang('Help your place stand out to get booked faster and earn your first reviews.')</p>

                            <label class="discount-card">
                                <div class="discount-card-checkbox">
                                    <input
                                        type="checkbox"
                                        name="discounts[new_listing][enabled]"
                                        class="discount-checkbox"
                                        {{ old('discounts.new_listing.enabled', $property->discount_info['new_listing']['enabled'] ?? false) ? 'checked' : '' }}
                                    >
                                    <div class="custom-checkbox"></div>
                                </div>
                                <div class="discount-percent">
                                    <input
                                        type="number"
                                        name="discounts[new_listing][percent]"
                                        class="form-control discountForm"
                                        min="0" max="100"
                                        placeholder="%"
                                        value="{{ old('discounts.new_listing.percent', $property->discount_info['new_listing']['percent'] ?? '') }}"
                                    >
                                </div>
                                <div class="discount-details">
                                    <div class="discount-name">@lang('New listing promotion')</div>
                                    <div class="discount-description">@lang('Offer % off your first 3 bookings')</div>
                                </div>
                            </label>
                            <label class="discount-card">
                                <div class="discount-card-checkbox">
                                    <input
                                        type="checkbox"
                                        name="discounts[weekly][enabled]"
                                        class="discount-checkbox"
                                        {{ old('discounts.weekly.enabled', $property->discount_info['weekly']['enabled'] ?? false) ? 'checked' : '' }}
                                    >
                                    <div class="custom-checkbox"></div>
                                </div>
                                <div class="discount-percent">
                                    <input
                                        type="number"
                                        name="discounts[weekly][percent]"
                                        class="form-control discountForm"
                                        min="0" max="100"
                                        placeholder="%"
                                        value="{{ old('discounts.weekly.percent', $property->discount_info['weekly']['percent'] ?? '') }}"
                                    >
                                </div>
                                <div class="discount-details">
                                    <div class="discount-name">@lang('Weekly discount')</div>
                                    <div class="discount-description">@lang('Offer % of discount for stays of 7 nights or more')</div>
                                </div>
                            </label>
                            <label class="discount-card">
                                <div class="discount-card-checkbox">
                                    <input
                                        type="checkbox"
                                        name="discounts[monthly][enabled]"
                                        class="discount-checkbox"
                                        {{ old('discounts.monthly.enabled', $property->discount_info['monthly']['enabled'] ?? false) ? 'checked' : '' }}
                                    >
                                    <div class="custom-checkbox"></div>
                                </div>
                                <div class="discount-percent">
                                    <input
                                        type="number"
                                        name="discounts[monthly][percent]"
                                        class="form-control discountForm"
                                        min="0" max="100"
                                        placeholder="%"
                                        value="{{ old('discounts.monthly.percent', $property->discount_info['monthly']['percent'] ?? '') }}"
                                    >
                                </div>
                                <div class="discount-details">
                                    <div class="discount-name">@lang('Monthly discount')</div>
                                    <div class="discount-description">@lang('Offer % of discount for stays of 28 nights or more')</div>
                                </div>
                            </label>

                            <div id="custom-discounts-container">
                                @if(!empty($property->discount_info['others']) && is_array($property->discount_info['others']))
                                    @foreach($property->discount_info['others'] as $index => $discount)
                                        <label class="discount-card selected">
                                            <div class="discount-card-checkbox">
                                                <input
                                                    type="checkbox"
                                                    name="custom_discounts[custom_{{ $index }}][enabled]"
                                                    class="discount-checkbox"
                                                    {{ (isset($discount['enabled']) && $discount['enabled'] === 'on') ? 'checked' : '' }}
                                                >
                                                <div class="custom-checkbox {{ (isset($discount['enabled']) && $discount['enabled'] === 'on') ? 'checked' : '' }}"></div>
                                            </div>
                                            <div class="discount-percent">
                                                <input
                                                    type="number"
                                                    name="custom_discounts[custom_{{ $index }}][percent]"
                                                    class="form-control discountForm"
                                                    min="0"
                                                    max="100"
                                                    placeholder="%"
                                                    value="{{ $discount['percent'] ?? '' }}"
                                                >
                                            </div>
                                            <div class="discount-details">
                                                <input
                                                    type="text"
                                                    name="custom_discounts[custom_{{ $index }}][title]"
                                                    class="form-control mb-1"
                                                    placeholder="@lang('Title')"
                                                    value="{{ $discount['title'] ?? '' }}"
                                                >
                                                <input
                                                    type="text"
                                                    name="custom_discounts[custom_{{ $index }}][description]"
                                                    class="form-control mb-1"
                                                    placeholder="@lang('Description')"
                                                    value="{{ $discount['description'] ?? '' }}"
                                                >
                                            </div>
                                            <button type="button" class="position-absolute delete-feature remove-discount" aria-label="Remove"><i class="fas fa-times"></i></button>
                                        </label>
                                    @endforeach
                                @endif
                            </div>

                            <button type="button" id="addDiscountBtn" class="btn btn-outline-primary mt-3">
                                @lang('Add new discount')
                            </button>
                        </div>
                    </div>
                </div>

                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.pricing', ['property_id' => $property->id]) }}" class="prev-btn">@lang('Back')</a>
                    <button type="submit" class="next-btn">@lang('Next')</button>
                </div>
            </form>
        </div>
    </section>
@endsection
@push('style')
    <style>
        .discountForm{
            width: 70px;
            font-size: 18px;
            font-weight: 600;
        }
        .discount-card {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            background-color: #fff;
            transition: box-shadow 0.2s ease-in-out;
        }

        .discount-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .discount-checkbox {
            margin-right: 0.5rem;
            margin-top: 2px;
        }

        .discount-input {
            width: 80px;
            min-width: 70px;
            border: 1px solid #ccc;
            border-radius: 6px;
            padding: 5px 10px;
            font-size: 14px;
        }

        .discount-info {
            flex: 1;
        }

        .discount-name {
            font-weight: 600;
            font-size: 16px;
        }

        .discount-description {
            font-size: 14px;
            color: #6b7280;
        }
        .discount-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 24px;
            margin-top: 20px;
        }

        .discount-title {
            font-size: 28px;
            font-weight: 600;
            margin: 0 0 16px 0;
            color: #222;
        }

        .discount-subtitle {
            font-size: 16px;
            color: #717171;
            margin-bottom: 32px;
        }

        .discount-card {
            border: 1px solid #ebebeb;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            display: flex;
            align-items: flex-start;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .discount-card-checkbox {
            margin-right: 16px;
            margin-top: 4px;
        }

        .discount-card.selected {
            position: relative;
            border-color: #222;
            background-color: #fafafa;
        }

        .discount-percent {
            font-size: 28px;
            font-weight: 600;
            color: #222;
            min-width: 60px;
        }

        .discount-details {
            flex-grow: 1;
        }

        .discount-name {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 4px;
            color: #222;
        }

        .discount-description {
            font-size: 14px;
            color: #717171;
            line-height: 1.4;
        }

        .disclaimer {
            font-size: 14px;
            color: #717171;
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #ebebeb;
        }

        .learn-more {
            color: #222;
            text-decoration: underline;
            font-weight: 500;
        }

        .custom-checkbox {
            position: relative;
            width: 20px;
            height: 20px;
            border: 2px solid #ddd;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .custom-checkbox.checked {
            background-color: #222;
            border-color: #222;
        }

        .custom-checkbox.checked::after {
            content: "\f00c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            color: white;
            font-size: 12px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .discount-card-checkbox {
            position: relative;
            margin-right: 16px;
            margin-top: 4px;
        }

        .custom-checkbox {
            position: relative;
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #ddd;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .custom-checkbox.checked {
            background-color: #222;
            border-color: #222;
        }

        .custom-checkbox.checked::after {
            content: "✓";
            position: absolute;
            color: white;
            font-size: 12px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .discount-checkbox {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .discount-card.selected {
            border-color: #222;
            background-color: #fafafa;
        }
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function handleCheckboxBehavior(card, checkbox, customCheckbox) {
                if (checkbox.checked) {
                    card.classList.add('selected');
                    customCheckbox.classList.add('checked');
                }

                checkbox.addEventListener('change', function () {
                    if (this.checked) {
                        card.classList.add('selected');
                        customCheckbox.classList.add('checked');
                    } else {
                        card.classList.remove('selected');
                        customCheckbox.classList.remove('checked');
                    }
                });

                card.addEventListener('click', function (e) {
                    const tag = e.target.tagName.toLowerCase();
                    if (tag !== 'input' && tag !== 'textarea' && tag !== 'select' && tag !== 'button' && !e.target.closest('button')) {
                        checkbox.checked = !checkbox.checked;
                        checkbox.dispatchEvent(new Event('change'));
                    }
                });
            }

            document.addEventListener('click', function (e) {
                if (e.target.classList.contains('custom-checkbox')) {
                    e.stopPropagation();
                    const card = e.target.closest('.discount-card');
                    const checkbox = card.querySelector('.discount-checkbox');
                    checkbox.checked = !checkbox.checked;
                    checkbox.dispatchEvent(new Event('change'));
                }
            });

            document.querySelectorAll('.discount-checkbox').forEach(checkbox => {
                const card = checkbox.closest('.discount-card');
                const customCheckbox = card.querySelector('.custom-checkbox');
                handleCheckboxBehavior(card, checkbox, customCheckbox);
            });

            let customCount = document.querySelectorAll('#custom-discounts-container .discount-card').length;
            const addBtn = document.getElementById('addDiscountBtn');
            const container = document.getElementById('custom-discounts-container');

            addBtn.addEventListener('click', function () {
                const index = `custom_${customCount++}`;

                const newCard = document.createElement('label');
                newCard.className = 'discount-card selected';

                newCard.innerHTML = `
                    <div class="discount-card-checkbox">
                        <input type="checkbox" name="custom_discounts[${index}][enabled]" class="discount-checkbox" checked>
                        <div class="custom-checkbox checked"></div>
                    </div>
                    <div class="discount-percent">
                        <input type="number" name="custom_discounts[${index}][percent]" class="form-control discountForm" min="0" max="100" placeholder="%">
                    </div>
                    <div class="discount-details">
                        <input type="text" name="custom_discounts[${index}][title]" class="form-control mb-2" placeholder="@lang('Title')">
                        <input type="text" name="custom_discounts[${index}][description]" class="form-control mt-1" placeholder="@lang('Description')">
                    </div>
                    <button type="button" class="position-absolute delete-feature remove-discount" aria-label="Remove"><i class="fas fa-times"></i></button>
                `;

                container.appendChild(newCard);

                const checkbox = newCard.querySelector('.discount-checkbox');
                const customCheckbox = newCard.querySelector('.custom-checkbox');
                handleCheckboxBehavior(newCard, checkbox, customCheckbox);

                newCard.querySelector('.remove-discount').addEventListener('click', function (e) {
                    e.preventDefault();
                    newCard.remove();
                });
            });
        });

        const form = document.getElementById('discountForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.safety') }}';

        @include(template().'vendor.listing.partials.cmn_script')
    </script>
@endpush
