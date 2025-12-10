@foreach($properties as $property)
    <div class="col-lg-4">
        <div class="categories-single">
            <div class="categories-single-image">
                <a href="{{ route('service.details', $property->slug) }}">
                    <img src="{{ getFile($property->photos->images['thumb']['driver'], $property->photos->images['thumb']['path']) }}" alt="{{ $property->title ?? '' }}">
                </a>
            </div>
            <div class="categories-single-content">
                <div class="categories-single-title">
                    <a href="{{ route('service.details', $property->slug) }}">{{ Str::limit($property->title, 40)}}</a>
                </div>
                <div class="categories-single-btn mt-2">
                    <div class="categories-single-btn-text w-100">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center justify-content-start gap-2">
                                <h5>{{ userCurrencyPosition(discountedPrice($property)) }} <span>/@lang('Night')</span></h5>
                                @if($property->discount == 1)
                                    <del class="text-danger">{{ userCurrencyPosition($property->pricing?->nightly_rate) }}<span>/@lang('Night')</span></del>
                                @endif
                            </div>
                            <div class="rat">
                                <i class="fa-thin fa-location-arrow"></i>
                                @if(isset($property->distance))
                                    {{ round($property->distance, 1) }} @lang('Km away')
                                @else
                                    N/A
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
