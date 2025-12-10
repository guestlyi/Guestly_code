@extends(template().'layouts.user')
@section('title',trans('Amenities'))
@section('content')
    <section class="listing-details-1 amenities-page">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')

            <form id="amenityForm" action="{{ route('user.listing.amenities.save') }}" method="POST">
                @csrf
                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id ?? '' }}">

                <div class="row">
                    @php
                        $savedAmenities = $property->allAmenity?->amenities ?? [];

                        $selectedIds = [];
                        if (is_array($savedAmenities)) {
                            foreach ($savedAmenities as $group) {
                                $selectedIds = array_merge($selectedIds, $group);
                            }
                        }

                        $selectedIds = array_map('strval', $selectedIds);
                    @endphp
                    <div class="col-lg-6 offset-lg-3">
                        <h3>@lang("What amenities do you offer?")</h3>
                        <p>@lang("Amenities help guests know what to expect at your place. Select all the amenities you provide to make your listing more appealing.")</p>
                        <div class="amenities-page-list">
                            <ul class="listing-house-list" id="amenity-list">
                                @foreach($amenities ?? [] as $item)
                                    <li data-id="{{ $item->id }}"
                                        class="{{ in_array((string)$item->id, $selectedIds) ? 'selected' : '' }}">
                                        <a href="#0"><i class="{{ $item->icon }}"></i>{{ $item->title }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="amenities" id="selected-amenities" value="{{ implode(',', $selectedIds) }}">


                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.stand.out', ['property_id' => $property->id]) }}" class="prev-btn"> @lang('Back')</a>
                    <button type="submit" class="next-btn"> @lang('Next')</button>
                </div>
            </form>
        </div>
    </section>
@endsection
@push('style')
    <style>
        li.selected {
            background-color: var(--bg-4);
            border: 1px solid var(--bg-3);
            border-radius: 11px;
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
            const listItems = document.querySelectorAll('#amenity-list li');
            const hiddenInput = document.getElementById('selected-amenities');
            const selectedIds = new Set(
                hiddenInput.value ? hiddenInput.value.split(',') : []
            );

            hiddenInput.value = Array.from(selectedIds).join(',');

            listItems.forEach(item => {
                item.addEventListener('click', function () {
                    const id = this.getAttribute('data-id');
                    if (selectedIds.has(id)) {
                        selectedIds.delete(id);
                        this.classList.remove('selected');
                    } else {
                        selectedIds.add(id);
                        this.classList.add('selected');
                    }
                    hiddenInput.value = Array.from(selectedIds).join(',');
                });
            });
        });

        const form = document.getElementById('amenityForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.photos') }}';

        @include(template().'vendor.listing.partials.cmn_script')

    </script>
@endpush
