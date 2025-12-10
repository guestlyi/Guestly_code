@extends(template().'layouts.user')
@section('title',trans('Location'))
@section('content')
    <section class="listing-details-1 listing-location">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')
            <form id="locationForm" action="{{ route('user.listing.location.save') }}" method="post">
                @csrf

                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id ?? '' }}">

                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <h3>@lang('Confirm your address')</h3>
                        <p>@lang('Your address is shared with guests when they visit your listings.')</p>
                        <div class="location-form">
                            <label>@lang('Country / region')</label>
                            <input type="text" class="form-control" name="country" value="{{ old('country', $property->country)  }}">
                            <label>@lang('State')</label>
                            <input type="text"  class="form-control" name="state" value="{{ old('state', $property->state)  }}">
                            <label>@lang('City')</label>
                            <input type="text"  class="form-control" name="city" value="{{ old('city', $property->city)  }}">
                            <label>@lang('Full Address')</label>
                            <input type="text"  class="form-control" name="address" value="{{ old('address', $property->address)  }}">
                            <label>@lang('Zip Code')</label>
                            <input type="text"  class="form-control" name="zip" value="{{ old('zip', $property->zip_code)  }}">
                        </div>
                    </div>
                </div>

                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.maps', ['property_id' => $property->id]) }}" class="prev-btn"> @lang('Back')</a>
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
        const form = document.getElementById('locationForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.nearby') }}';


        @include(template().'vendor.listing.partials.cmn_script')

    </script>
@endpush
