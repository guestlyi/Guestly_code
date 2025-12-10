@extends(template().'layouts.user')
@section('title',trans('Types'))
@section('content')
    <section class="listing-details-1">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')
            <form id="typesForm" action="{{ route('user.listing.type.save') }}" method="post" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="type_id" id="type_id" value="{{ $property->type_id ?? '' }}">
                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id ?? '' }}">

                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="listing-details-1-content">
                            <h3>@lang('What type of place will guests have?')</h3>
                            <ul class="listing-house-list">
                                @foreach($types as $item)
                                    <li class="selectable-type {{ $property->type_id == $item->id ? 'selected' : '' }}" data-type="{{ $item->id }}">
                                        <div class="single-item">
                                            <div class="icon">
                                                <img src="{{ getFile($item->driver, $item->image) }}" alt="{{ $item->name }}">
                                            </div>
                                            <div class="house-listing-content">
                                                <h6>{{ $item->name }}</h6>
                                                <p>{{ \Illuminate\Support\Str::limit(strip_tags($item->description), 100) }}</p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.structure', ['property_id' => $property->id]) }}" class="prev-btn">@lang('Back')</a>
                    <button type="submit" class="next-btn">@lang('Next')</button>
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
        const form = document.getElementById('typesForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.styles') }}';

        document.querySelectorAll('.selectable-type').forEach(item => {
            item.addEventListener('click', function () {
                document.querySelectorAll('.selectable-type').forEach(el => el.classList.remove('selected'));
                this.classList.add('selected');
                document.getElementById('type_id').value = this.getAttribute('data-type');
            });
        });

        @include(template().'vendor.listing.partials.cmn_script')

    </script>
@endpush
