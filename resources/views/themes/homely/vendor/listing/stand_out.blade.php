@extends(template().'layouts.user')
@section('title',trans('Place Stand Out'))
@section('content')
    <section class="listing-details-1 stand-out">
        <div class="container">
            <div class="listing-top">
                <div class="logo-box">
                    <div class="logo"><a href="{{ route('page','/') }}"><img src="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" alt="logo"></a></div>
                </div>
                <div class="save-btn">
                    <a href="{{ route('user.property.list') }}"  class="btn-1">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                @lang('Save & Exit')
                            </div>
                            <div class="hover-text btn-single">
                                @lang('Save & Exit')
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <div class="stand-out-left-container">
                        <div class="common-title">
                            <h5>@lang('Phase 2')</h5>
                            <h3>@lang('Make your place stand out')</h3>
                            <p>@lang('In this step, you’ll add some of the amenities your place offers, plus 5 or more photos. Then, you’ll create a title and description.')</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="stand-out-image">
                        <video data-testid="video-player"  autoplay="" crossorigin="anonymous" playsinline="" preload="auto" style="object-fit: cover;"><source src="https://stream.media.muscache.com/H0101WTUG2qWbyFhy02jlOggSkpsM9H02VOWN52g02oxhDVM.mp4?v_q=high" type="video/mp4"></video>
                    </div>
                </div>
            </div>

            <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                <a href="{{ route('user.listing.availablityAndFeatures', ['property_id' => $property->id]) }}" class="prev-btn"> @lang('Back')</a>
                <a href="{{ route('user.listing.amenities',['property_id' => $property->id]) }}" class="next-btn"> @lang('Next')</a>
            </div>
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
@endpush
