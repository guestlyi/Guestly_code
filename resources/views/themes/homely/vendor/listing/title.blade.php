@extends(template().'layouts.user')
@section('title',trans('Property Title'))
@section('content')
    <section class="listing-details-1 listing-location">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')
            <form id="titleForm" action="{{ route('user.listing.title.save') }}" method="post">
                @csrf

                <input type="hidden" name="property_id" value="{{ $property->id }}">

                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="heading-box d-flex align-items-center justify-content-between">
                            <div class="heading">
                                <h3>@lang("Now, let's give your listings a title")</h3>
                                <p>@lang("Short titles work best. Have fun with it—you can always change it later.")</p>
                            </div>
                            @if(isAiAccess())
                                <div class="title-area">
                                    <button type="button"
                                            class="btn-ai-glow"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#detailsGenerateOffcanvas"
                                            aria-controls="detailsGenerateOffcanvas">
                                        <i class="fas fa-bolt me-2"></i> @lang('Use AI')
                                    </button>
                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="detailsGenerateOffcanvas" aria-labelledby="detailsGenerateOffcanvasLabel">
                                        <div class="offcanvas-header">
                                            <h5 id="offcanvasRightLabel"><i class="bi-gear me-1"></i>@lang('Write with '. basicControl()->site_title.' AI assistant')</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <span class="text-dark font-weight-bold">@lang('🚀 Boost Your Property Listings with AI Magic! Struggling to write catchy titles? Let our AI do the work! 🎯We’ll auto-generate rich, eye-catching titles that bring in more bookings — effortlessly. Ready to level up your tour product? 👉 Try it now!')</span>
                                            <div class="col-12 mb-4 mt-4">
                                                <span class="text-cap text-body mb-2">@lang("Write some text about your property type")</span>
                                                <textarea class="form-control" id="title" rows="4" placeholder="@lang('e.g. Kerela house boat package')" autocomplete="off">{{ $property->title ?? '' }}</textarea>
                                            </div>
                                            <div class="col-12 mb-4" id="wordCountWrapper">
                                                <span class="text-cap text-body d-block mb-2">
                                                    @lang("Number Of Words ")
                                                    <i class="fas fa-question-circle"
                                                       data-bs-toggle="tooltip"
                                                       data-bs-placement="top"
                                                       title="@lang('Choose how many words you want to generate')">
                                                    </i>
                                                </span>

                                                <div class="d-flex gap-2" id="wordCountOptions">
                                                    <div class="image-count-box" data-value="20">20</div>
                                                    <div class="image-count-box" data-value="50">50</div>
                                                    <div class="image-count-box" data-value="100">100</div>
                                                </div>

                                                <input type="hidden" name="length" id="length" value="">
                                            </div>
                                            <div class="row gx-2">
                                                <div class="col">
                                                    <div class="d-grid">
                                                        <button type="button" class="btn-ai-glow" id="generateBtn"><i class="fas fa-bolt pe-1"></i> @lang('Generate')</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="previewOptions d-none"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="location-form">
                            <label>@lang('Title')</label>
                            <textarea class="form-control" name="title" id="propertyTitle" cols="5">{{ old('title', $property->title) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.photos', ['property_id' => $property->id]) }}" class="prev-btn"> @lang('Back')</a>
                    <button type="submit" class="next-btn"> @lang('Next')</button>
                </div>
            </form>
        </div>
    </section>
@endsection
@push('style')
    <style>
        .image-count-box {
            width: 75px;
            height: 40px;
            border: 2px solid #ddd;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-weight: bold;
            background-color: #fff;
            transition: all 0.2s ease-in-out;
        }

        .image-count-box:hover {
            border-color: var(--border-2);
            background-color: var(--primary-color);
            color: #fff;
        }

        .image-count-box.active, .ai-title-option.active {
            border-color: var(--border-2);
            background-color: var(--primary-color);
            color: #fff;
        }
        .previewOptions{
            margin: 30px 10px;
            border: 1px solid var(--border-2);
            border-radius: 10px;
            padding: 20px;
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
            const boxes = document.querySelectorAll('#wordCountOptions .image-count-box');
            const lengthInput = document.getElementById('length');

            boxes.forEach(box => {
                box.addEventListener('click', () => {
                    lengthInput.value = box.getAttribute('data-value');

                    boxes.forEach(b => b.classList.remove('active'));
                    box.classList.add('active');
                });
            });
        });
        $('#generateBtn').on('click', function () {
            var title = $('#title').val().trim();
            var length = $('#length').val().trim();
            const previewDiv = $('.previewOptions');

            if (!title || !length) {
                Notiflix.Notify.failure('Please fill in both fields.');
                return;
            }

            $.ajax({
                url: '{{ route('user.listing.ai.generate') }}',
                method: 'POST',
                data: {
                    title: title,
                    length: length,
                    type: 'title',
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function () {
                    Notiflix.Loading.standard('@lang("Generating...")');
                    $('#generateBtn').prop('disabled', true);
                },
                success: function (response) {
                    Notiflix.Loading.remove();
                    if(response.type === 'title' && response.res.status === 'success'){
                        const aiText = response.res.ai_response;

                        const lines = aiText.split('\n').filter(line => line.trim() !== '');
                        const heading = lines[0];

                        const titles = lines.filter(line => /^\d+\.\s/.test(line));


                        previewDiv.empty();
                        previewDiv.removeClass('d-none');

                        previewDiv.append(`<p class="fw-bold mb-3">${heading}</p>`);

                        const optionsContainer = $('<div class="ai-title-options d-flex flex-column gap-2"></div>');

                        titles.forEach(titleLine => {
                            let cleanTitle = titleLine.replace(/^\d+\.\s*/, '').trim();

                            cleanTitle = cleanTitle.replace(/\*\*/g, '');

                            const option = $(`
                                <div class="ai-title-option border rounded px-3 py-2" style="cursor:pointer;">
                                    ${cleanTitle}
                                </div>
                            `);

                            option.on('click', function () {
                                const isActive = $(this).hasClass('active');

                                $('.ai-title-option').removeClass('active');

                                if (!isActive) {
                                    $(this).addClass('active');

                                    $('#title').val(cleanTitle);
                                    $('#propertyTitle').val(cleanTitle);
                                } else {
                                    $('#title').val('');
                                    $('#propertyTitle').val(`{{ old('title', $property->title) }}`);
                                }
                            });

                            optionsContainer.append(option);
                        });

                        previewDiv.append(optionsContainer);
                    } else {
                        previewDiv.addClass('d-none');
                        Notiflix.Notify.failure('Failed to generate titles.');
                    }
                },
                error: function (xhr) {
                    Notiflix.Loading.remove();
                    previewDiv.addClass('d-none');
                    Notiflix.Notify.failure('An error occurred while generating the description.');
                },
                complete: function () {
                    $('#generateBtn').prop('disabled', false).html('<i class="bi bi-lightning"></i> @lang('Generate')');
                }
            });
        });

        const form = document.getElementById('titleForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.description') }}';


        @include(template().'vendor.listing.partials.cmn_script')
    </script>
@endpush
