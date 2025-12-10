@extends(template().'layouts.user')
@section('title',trans('Listing information'))
@section('content')
    <section class="listing-details-1 listing-location">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')

            <form id="featureForm" action="{{ route('user.listing.information.save') }}" method="post">
                @csrf

                <input type="hidden" name="property_id" id="property_id" value="{{  $property->id }}">
                <input type="hidden" name="guests" id="guests" value="{{ $property->features?->max_guests ?? 0 }}">
                <input type="hidden" name="bedrooms" id="bedrooms" value="{{ $property->features?->bedrooms ?? 0 }}">
                <input type="hidden" name="beds" id="beds" value="{{ $property->features?->beds ?? 0 }}">
                <input type="hidden" name="bathrooms" id="bathrooms" value="{{ $property->features?->bathrooms ?? 0 }}">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <h3>@lang('Share information about your listings')</h3>
                        <p>@lang('This is core information for your listing item').</p>
                        <div class="location-form">
                            <div class="flor-count">
                                <div class="count-single">
                                    <div class="count-single-text">
                                        <h6>@lang('Guests')</h6>
                                    </div>
                                    <div class="count-single-inner">
                                        <button type="button"  class="decrement"><i class="fa-light fa-minus"></i></button>
                                        <span class="adult">0</span>
                                        <button type="button" class="increment"><i class="fa-light fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="count-single">
                                    <div class="count-single-text">
                                        <h6>@lang('Bedrooms')</h6>
                                    </div>
                                    <div class="count-single-inner">
                                        <button type="button"  class="decrementTwo"><i class="fa-light fa-minus"></i></button>
                                        <span class="childeren">0</span>
                                        <button type="button" class="incrementTwo"><i class="fa-light fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="count-single">
                                    <div class="count-single-text">
                                        <h6>@lang('Beds')</h6>
                                    </div>
                                    <div class="count-single-inner">
                                        <button type="button"  class="decrementThree"><i class="fa-light fa-minus"></i></button>
                                        <span class="room">0</span>
                                        <button type="button" class="incrementThree"><i class="fa-light fa-plus"></i></button>
                                    </div>
                                </div>
                                <div class="count-single">
                                    <div class="count-single-text">
                                        <h6>@lang('Bathrooms')</h6>
                                    </div>
                                    <div class="count-single-inner">
                                        <button type="button"  class="decrementFour"><i class="fa-light fa-minus"></i></button>
                                        <span class="bathrooms">0</span>
                                        <button type="button" class="incrementFour"><i class="fa-light fa-plus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.nearby', ['property_id' => $property->id]) }}" class="prev-btn"> @lang('Back')</a>
                    <button type="submit" class="next-btn"> @lang('Next')</button>
                </div>
            </form>
        </div>
    </section>
@endsection

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
        document.addEventListener("DOMContentLoaded", function () {
            function setupCounter(decrementBtn, incrementBtn, displaySpanSelector, hiddenInputId) {
                const decrement = document.querySelector(decrementBtn);
                const increment = document.querySelector(incrementBtn);
                const displaySpan = document.querySelector(displaySpanSelector);
                const hiddenInput = document.getElementById(hiddenInputId);

                let initialValue = parseInt(hiddenInput.value) || 0;
                displaySpan.textContent = initialValue;

                decrement.addEventListener("click", function () {
                    let value = parseInt(displaySpan.textContent);
                    if (value > 0) {
                        value--;
                        displaySpan.textContent = value;
                        hiddenInput.value = value;
                    }
                });

                increment.addEventListener("click", function () {
                    let value = parseInt(displaySpan.textContent);
                    value = value + 1;
                    displaySpan.textContent = value;
                    hiddenInput.value = value;
                });
            }

            setupCounter(".decrement", ".increment", ".adult", "guests");
            setupCounter(".decrementTwo", ".incrementTwo", ".childeren", "bedrooms");
            setupCounter(".decrementThree", ".incrementThree", ".room", "beds");
            setupCounter(".decrementFour", ".incrementFour", ".bathrooms", "bathrooms");
        });

        const form = document.getElementById('featureForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.availablityAndFeatures') }}';


        @include(template().'vendor.listing.partials.cmn_script')
    </script>
@endpush
