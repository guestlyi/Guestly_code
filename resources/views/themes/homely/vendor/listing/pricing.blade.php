@extends(template().'layouts.user')
@section('title',trans('Pricing'))
@section('content')
    <section class="listing-details-1 listing-location pricing-listing">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')
            <form id="pricingForm" action="{{ route('user.listing.pricing.save') }}" method="post">
                @csrf
                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id ?? '' }}">

                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="price-container">
                            <h1 class="price-title">@lang('Now, set your base prices')</h1>
                            <p class="tip">@lang('Tip: Set different rates for nightly, weekly, and monthly stays.')</p>

                            @php $currency = basicControl()->base_currency; @endphp

                            @foreach([
                                ['id' => 'nightlyPrice', 'name' => 'nightly_price', 'label' => 'Nightly Rate', 'placeholder' => '15', 'value' => old('nightly_price', $property->pricing?->nightly_rate)],
                                ['id' => 'weeklyPrice', 'name' => 'weekly_price', 'label' => 'Weekly Rate', 'placeholder' => '90', 'value' => old('weekly_price', $property->pricing?->weekly_rate)],
                                ['id' => 'monthlyPrice', 'name' => 'monthly_price', 'label' => 'Monthly Rate', 'placeholder' => '300', 'value' => old('monthly_price', $property->pricing?->monthly_rate)],
                                ['id' => 'serviceFee', 'name' => 'service_fee', 'label' => 'Service Fee', 'placeholder' => '10', 'value' => old('service_fee', $property->pricing?->service_fee)],
                                ['id' => 'cleaningFee', 'name' => 'cleaning_fee', 'label' => 'Cleaning Rate', 'placeholder' => '20', 'value' => old('cleaning_fee', $property->pricing?->cleaning_fee)],
                            ] as $field)
                                <div class="price-input-container">
                                    <label for="{{ $field['id'] }}" class="form-label">@lang($field['label'])</label>
                                    <div class="d-flex align-items-center">
                                        <input
                                            type="text"
                                            id="{{ $field['id'] }}"
                                            name="{{ $field['name'] }}"
                                            class="price-input"
                                            placeholder="{{ $field['placeholder'] }}"
                                            value="{{ $field['value'] }}"
                                        >
                                        <span class="currency-symbol">{{ $currency }}</span>
                                    </div>
                                </div>
                            @endforeach


                            <div class="mb-3">
                                <label for="priceTypeSelect" class="form-label mb-2">@lang('Select Rate Type')</label>
                                <select id="priceTypeSelect" class="form-select">
                                    <option value="nightly" selected>@lang('Nightly')</option>
                                    <option value="weekly">@lang('Weekly')</option>
                                    <option value="monthly">@lang('Monthly')</option>
                                </select>
                            </div>

                            <div class="guest-price" id="priceToggle">
                                <span>@lang('Guest price with taxes') <strong id="guestPrice">0</strong></span>
                                <i class="fas fa-chevron-down toggle-icon"></i>
                            </div>

                            <div class="price-breakdown" id="priceBreakdown">
                                <div class="breakdown-row">
                                    <span id="baseLabel">@lang('Base price (Nightly)')</span>
                                    <span id="baseAmount">0</span>
                                </div>
                                <div class="breakdown-row">
                                    <span>@lang('Guest service fee')</span>
                                    <span id="guestFee">0</span>
                                </div>
                                <div class="breakdown-row">
                                    <span>@lang('You earn')</span>
                                    <span id="youEarn">0</span>
                                </div>
                                <div class="breakdown-row">
                                    <span>@lang('Show less')</span>
                                    <i class="fas fa-times"></i>
                                </div>
                            </div>

                            @php
                                $showRefund = old('is_refundable', $property->pricing->refundable ?? false);
                                $refundRules = old('refund_rules', $property->pricing->refund_infos ?? []);
                            @endphp

                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="hidden" name="is_refundable" value="0">
                                    <input class="form-check-input" type="checkbox" id="isRefundable" name="is_refundable" value="1"
                                        {{ $showRefund ? 'checked' : '' }}>
                                    <label class="form-check-label" for="isRefundable">
                                        @lang("Is this property's booking refundable?")
                                    </label>
                                </div>
                            </div>

                            <div class="refund-policy-container {{ $showRefund ? 'd-block' : 'd-none' }}" id="refundPolicyContainer">
                                <div class="mb-3">
                                    <button type="button" class="btn-3 mb-4" id="addRefundRuleBtn">
                                        <div class="btn-wrapper">
                                            <div class="main-text btn-single">
                                                <i class="far fa-plus-circle pe-1"></i>@lang('Add Refund Rule')
                                            </div>
                                            <div class="hover-text btn-single">
                                                <i class="far fa-plus-circle pe-1"></i>@lang('Add Refund Rule')
                                            </div>
                                        </div>
                                    </button>
                                </div>

                                <div id="dynamicRefundRules" class="mt-3">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.finishing.setup', ['property_id' => $property->id]) }}" class="prev-btn"> @lang('Back')</a>
                    <button type="submit" class="next-btn"> @lang('Next')</button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('style')
    <style>
        .price-container {
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 24px;
            margin-top: 20px;
        }

        .price-title {
            font-size: 22px;
            font-weight: 600;
            margin: 0 0 16px 0;
            color: #222;
        }

        .tip {
            font-size: 14px;
            color: #717171;
            margin-bottom: 24px;
        }

        .price-input-container {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 12px 16px;
        }

        .dollar-sign {
            font-size: 24px;
            font-weight: 600;
            margin-right: 8px;
            color: #222;
        }

        .price-input {
            border: none;
            font-size: 24px;
            font-weight: 600;
            width: 100%;
            outline: none;
            color: #222;
        }

        .price-input::placeholder {
            color: #b0b0b0;
        }

        .guest-price {
            font-size: 14px;
            color: #717171;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #ebebeb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
        }

        .guest-price strong {
            color: #222;
        }

        .price-breakdown {
            font-size: 14px;
            color: #717171;
            margin-bottom: 16px;
            padding-bottom: 16px;
            border-bottom: 1px solid #ebebeb;
            display: none;
        }

        .breakdown-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .price-link {
            display: block;
            color: #222;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .learn-more {
            display: block;
            color: #717171;
            font-size: 14px;
            text-decoration: underline;
        }

        .toggle-icon {
            transition: transform 0.2s ease;
        }

        .toggle-icon.rotated {
            transform: rotate(180deg);
        }
        .price-container {
            background: #fff;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        .price-input-container {
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 12px 16px;
            background: #f9f9f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .price-input-container + .price-input-container {
            margin-top: 16px;
        }

        .form-label {
            margin: 0;
            font-weight: 500;
            font-size: 16px;
            color: #333;
        }

        .price-input {
            border: none;
            background: transparent;
            text-align: right;
            font-weight: 600;
            font-size: 16px;
            width: 80px;
        }

        .price-input:focus {
            outline: none;
        }

        .currency-symbol {
            font-weight: 500;
            margin-left: 6px;
        }
        .breakdown-row{
            color: #222;
            font-weight: 600;
            cursor: pointer;
        }
        .tox.tox-tinymce{
            height: 250px !important;
        }
        .tox-statusbar__branding{
            display: none;
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
        document.addEventListener('DOMContentLoaded', function() {
            const priceToggle = document.getElementById('priceToggle');
            const priceBreakdown = document.getElementById('priceBreakdown');
            const toggleIcon = priceToggle.querySelector('.toggle-icon');

            priceToggle.addEventListener('click', function() {
                if (priceBreakdown.style.display === 'block') {
                    priceBreakdown.style.display = 'none';
                    toggleIcon.classList.remove('rotated');
                } else {
                    priceBreakdown.style.display = 'block';
                    toggleIcon.classList.add('rotated');
                }
            });


            const showLess = priceBreakdown.querySelector('.breakdown-row:last-child');
            showLess.addEventListener('click', function() {
                priceBreakdown.style.display = 'none';
                toggleIcon.classList.remove('rotated');
            });
        });
        document.addEventListener("DOMContentLoaded", function () {
            const currencySymbol = @json(basicControl()->currency_symbol);
            const charge = {{ basicControl()->booking_charge }};

            const rateInputs = {
                nightly: document.getElementById("nightlyPrice"),
                weekly: document.getElementById("weeklyPrice"),
                monthly: document.getElementById("monthlyPrice")
            };

            const serviceFeeInput = document.getElementById("serviceFee");
            const cleaningFeeInput = document.getElementById("cleaningFee");
            const priceTypeSelect = document.getElementById("priceTypeSelect");

            const guestPrice = document.getElementById("guestPrice");
            const baseLabel = document.getElementById("baseLabel");
            const baseAmount = document.getElementById("baseAmount");
            const guestFee = document.getElementById("guestFee");
            const youEarn = document.getElementById("youEarn");

            function parsePrice(value) {
                return parseFloat(value) || 0;
            }

            function updateBreakdown() {
                const type = priceTypeSelect.value;
                const base = parsePrice(rateInputs[type].value);
                const serviceFee = parsePrice(serviceFeeInput.value);
                const cleaningFee = parsePrice(cleaningFeeInput.value);

                const subtotal = base + serviceFee + cleaningFee;
                const totalWithCharge = subtotal + charge;

                baseLabel.textContent = `Base price (${type.charAt(0).toUpperCase() + type.slice(1)})`;
                baseAmount.textContent = currencySymbol + base.toFixed(2);
                guestFee.textContent = currencySymbol + (serviceFee + cleaningFee).toFixed(2);
                guestPrice.textContent = currencySymbol + totalWithCharge.toFixed(2);
                youEarn.textContent = currencySymbol + subtotal.toFixed(2);
            }

            [
                priceTypeSelect,
                ...Object.values(rateInputs),
                serviceFeeInput,
                cleaningFeeInput
            ].forEach(el => el.addEventListener("input", updateBreakdown));

            updateBreakdown();
        });

        document.addEventListener('DOMContentLoaded', function() {
            const refundCheckbox = document.getElementById('isRefundable');
            const refundContainer = document.getElementById('refundPolicyContainer');
            const addRuleBtn = document.getElementById('addRefundRuleBtn');
            const rulesContainer = document.getElementById('dynamicRefundRules');

            if (!refundCheckbox || !refundContainer || !addRuleBtn || !rulesContainer) return;

            const existingRules = @json($refundRules);
            if (refundCheckbox.checked || existingRules.length > 0) {
                refundContainer.classList.remove('d-none');
                refundContainer.classList.add('d-block');
            }

            if (existingRules.length > 0) {
                existingRules.forEach(rule => addRefundRule(rule));
            } else if (refundCheckbox.checked) {
                addRefundRule();
            }

            refundCheckbox.addEventListener('change', function() {
                Notiflix.Loading.circle('Loading...');
                setTimeout(() => {
                    if (this.checked) {
                        refundContainer.classList.remove('d-none');
                        refundContainer.classList.add('d-block');
                        if (rulesContainer.children.length === 0) addRefundRule();
                    } else {
                        refundContainer.classList.remove('d-block');
                        refundContainer.classList.add('d-none');
                    }
                    Notiflix.Loading.remove();
                }, 300);
            });

            addRuleBtn.addEventListener('click', function() {
                addRefundRule();
            });

            rulesContainer.addEventListener('click', function(e) {
                if (e.target.closest('.remove-place-btn')) {
                    e.target.closest('.refund-rule').remove();
                }
            });

            function addRefundRule(rule = {}) {
                const ruleHTML = `
                    <div class="refund-rule mb-3 p-3 border rounded position-relative">
                        <button type="button" class="position-absolute delete-feature remove-place-btn" aria-label="Remove">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="mb-2">
                            <label>@lang('Refund Percentage')</label>
                            <div class="input-group">
                                <input type="number" name="refund_rules[percentage][]" value="${rule.percentage ?? ''}" class="form-control" min="0" max="100" placeholder="0">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div class="mb-2">
                            <label>@lang('Days Before Check-in')</label>
                            <input type="number" name="refund_rules[days][]" value="${rule.days ?? ''}" class="form-control" min="0">
                        </div>
                        <div class="mb-2">
                            <label>@lang('Refund Message')</label>
                            <textarea name="refund_rules[message][]" class="form-control" rows="2">${rule.message ?? ''}</textarea>
                        </div>
                    </div>
                `;
                rulesContainer.insertAdjacentHTML('beforeend', ruleHTML);
            }
        });


        const form = document.getElementById('pricingForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.discounts') }}';

        @include(template().'vendor.listing.partials.cmn_script')
    </script>
@endpush
