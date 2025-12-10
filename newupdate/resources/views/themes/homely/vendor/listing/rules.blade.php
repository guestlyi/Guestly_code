@extends(template().'layouts.user')
@section('title',trans('Rules'))
@section('content')
    <section class="listing-details-1 listing-location">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')
            <form id="safetyForm" action="{{ route('user.listing.rules.save') }}" method="post">
                @csrf

                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id ?? '' }}">

                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="safety-container">
                            <h2>@lang('Share house rules')</h2>
                            <div class="question-title">
                                @lang('Does your place have any of these?')
                            </div>
                            <div class="information-group">
                                <div class="house-rules-group mt-3">
                                    <label>@lang('House Rules')</label>
                                    <div id="house-rules-container">
                                        @if(!empty($property->rules) && count($property->rules) > 0)
                                            @foreach($property->rules as $index => $rule)
                                                <div class="input-group mb-2">
                                                    <input type="text" name="house_rules[]" class="form-control"
                                                           value="{{ $rule }}"
                                                           placeholder="@lang('Example: No smoking, No parties, Check-in before 10PM')">
                                                    <button type="button" class="btn btn-outline-danger remove-rule" {{ $index === 0 ? 'disabled' : '' }}>
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="input-group mb-2">
                                                <input type="text" name="house_rules[]" class="form-control"
                                                       placeholder="@lang('Example: No smoking, No parties, Check-in before 10PM')">
                                                <button type="button" class="btn btn-outline-danger remove-rule" disabled>
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="add-house-rule">
                                        @lang('Add Another Rule')
                                    </button>
                                </div>
                            </div>

                            <div class="info-box">
                                <strong>@lang('Tips for good house rules')</strong>
                                @lang("Be clear about restrictions (smoking, pets, noise). Specify check-in/out times. Mention any guest policies. Highlight safety requirements. Keep rules reasonable and enforceable.")
                            </div>
                        </div>
                    </div>
                </div>

                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.safety', ['property_id' => $property->id]) }}" class="prev-btn"> @lang('Back')</a>
                    <button type="submit" class="next-btn"> @lang('Create Listing')</button>
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
@endpush

@push('style')
    <style>
        .house-rules-group {
            margin-top: 20px;
            padding: 15px;
            border: 1px solid #eee;
            border-radius: 5px;
        }

        .input-group {
            margin-bottom: 10px;
        }

        .remove-rule {
            width: 40px;
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('add-house-rule').addEventListener('click', function() {
                const container = document.getElementById('house-rules-container');
                const newInputGroup = document.createElement('div');
                newInputGroup.className = 'input-group mb-2';
                newInputGroup.innerHTML = `
            <input type="text" name="house_rules[]" class="form-control" placeholder="@lang('Example: No smoking, No parties, Check-in before 10PM')">
            <button type="button" class="btn btn-outline-danger remove-rule">
                <i class="fas fa-times"></i>
            </button>`;
                container.appendChild(newInputGroup);

                document.querySelectorAll('.remove-rule').forEach(btn => {
                    btn.disabled = false;
                });
            });

            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-rule') || e.target.closest('.remove-rule')) {
                    const inputGroup = e.target.closest('.input-group');
                    if (inputGroup) {
                        inputGroup.remove();

                        const remainingInputs = document.querySelectorAll('#house-rules-container .input-group');
                        if (remainingInputs.length === 1) {
                            remainingInputs[0].querySelector('.remove-rule').disabled = true;
                        }
                    }
                }
            });
        });

        const form = document.getElementById('safetyForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.finish') }}';

        @include(template().'vendor.listing.partials.cmn_script')
    </script>
@endpush
