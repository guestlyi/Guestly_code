@extends(template().'layouts.user')
@section('title',trans('Structure'))
@section('content')
    <section class="listing-details-1 amenities-page">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')
            <form id="structureForm" action="{{ route('user.listing.structure.save') }}" method="post">
                @csrf
                <input type="hidden" name="category_id" id="category_id" value="{{ $property->category_id ?? '' }}">
                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id ?? '' }}">
                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <h3>@lang('Which of these best describes your place?')</h3>
                        <div class="amenities-page-list">
                            <ul class="listing-house-list">
                                @foreach($categories as $item)
                                    <li class="selectable-category {{ isset($property) && $item->id == $property->category_id ? 'selected' : '' }}" data-id="{{ $item->id }}">
                                        <div class="single-item">
                                            <img class="categoryImage" src="{{ getFile($item->image_driver, $item->image) }}">
                                            {{ $item->name }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.about.your.place') }}" class="prev-btn"> @lang('Back')</a>
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
        const form = document.getElementById('structureForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.types') }}';

        document.querySelectorAll('.selectable-category').forEach(item => {
            item.addEventListener('click', function () {
                document.querySelectorAll('.selectable-category').forEach(el => el.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('category_id').value = this.getAttribute('data-id');
            });
        });

        @include(template().'vendor.listing.partials.cmn_script')
    </script>
@endpush

