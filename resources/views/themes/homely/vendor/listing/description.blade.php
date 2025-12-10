@extends(template().'layouts.user')
@section('title',trans('Property Description'))
@section('content')
    <section class="listing-details-1 listing-location">
        <div class="container">
            @include(template().'vendor.listing.partials.cmn_header')

            <form id="descriptionForm" action="{{ route('user.listing.description.save') }}" method="post">
                @csrf

                <input type="hidden" name="property_id" value="{{ $property->id }}">

                <div class="row">
                    <div class="col-lg-6 offset-lg-3">
                        <div class="d-flex align-items-center justify-content-between description-area">
                            <div class="texts">
                                <h3>@lang('Create your description')</h3>
                                <p>@lang('Share what makes your place special.')</p>
                            </div>
                            @if(isAiAccess())
                                <div class="aiItem">
                                    <button class="btn-ai-glow" type="button" data-bs-toggle="offcanvas" data-bs-target="#detailsGenerateOffcanvas" aria-controls="detailsGenerateOffcanvas" id="generateDetailsBtn"><i class="fas fa-bolt pe-1"></i>@lang('Use AI')</button>
                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="detailsGenerateOffcanvas" aria-labelledby="detailsGenerateOffcanvasLabel">
                                        <div class="offcanvas-header">
                                            <h5 id="offcanvasRightLabel"><i class="bi-gear me-1"></i>@lang('Write with '. basicControl()->site_title.' AI assistant')</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <span class="text-dark font-weight-bold">@lang('Enhance your tour Products with AI! 🎯. Let us auto-generate rich, detailed descriptions to attract more bookings. Want to try it now?')</span>
                                            <div class="col-12 mb-4 mt-4">
                                                <span class="text-cap text-body mb-2">@lang("Type your package title")</span>
                                                <textarea class="form-control" id="title" rows="4" placeholder="@lang('e.g. Athens : A Historical Start')" autocomplete="off">{{ $property->title ?? '' }}</textarea>
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
                                                    <div class="image-count-box" data-value="500">500</div>
                                                    <div class="image-count-box" data-value="1000">1000</div>
                                                    <div class="image-count-box" data-value="1500">1500</div>
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
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="location-form">
                            <label class="form-label">@lang('Description')</label>
                            <textarea id="description" class="form-control tinymce-editor" name="description" cols="5">
                                {{ old('description', $property->description) }}
                            </textarea>
                        </div>
                    </div>
                </div>

                <div class="next-prev-btn d-flex align-items-center justify-content-between mt_30">
                    <a href="{{ route('user.listing.title', ['property_id' => $property->id]) }}" class="prev-btn"> @lang('Back')</a>
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
        .image-count-box.active {
            border-color: var(--border-2);
            background-color: var(--primary-color);
            color: #fff;
        }
        .tox-statusbar__branding{
            display: none !important;
        }
    </style>
@endpush

@push('script')
    <script src="{{ asset('assets/global/tinymce/tinymce.min.js') }}" referrerpolicy="origin"></script>

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

        tinymce.init({
            selector: 'textarea.tinymce-editor',
            height: 400,
            menubar: false,
            readonly: false,
            plugins: 'advlist autolink lists link image charmap preview anchor ' +
                'searchreplace visualblocks code fullscreen ' +
                'insertdatetime media table help wordcount',
            toolbar: 'undo redo | formatselect | bold italic underline | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image media | preview code',
            content_style: 'body { font-family: monospace; white-space: pre; }',
            forced_root_block: false,
            branding: false,
            license_key: 'gpl'
        });

        $('#generateBtn').on('click', function () {
            var title = $('#title').val().trim();
            var length = $('#length').val().trim();

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
                    type: 'description',
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function () {
                    Notiflix.Loading.standard('@lang("Generating...")');
                    $('#generateBtn').prop('disabled', true);
                },
                success: function (response) {
                    Notiflix.Loading.remove();

                    const aiText = response?.res?.ai_response;

                    if (!aiText || typeof aiText !== 'string') {
                        Notiflix.Notify.failure('AI response was empty or invalid.');
                        console.error('Invalid AI response format:', response);
                        return;
                    }

                    if (tinymce.activeEditor) {
                        const formattedContent = aiText
                            .replace(/\n/g, '<br>')
                            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');

                        tinymce.activeEditor.setContent(formattedContent);
                        tinymce.triggerSave();
                    } else {
                        Notiflix.Notify.failure('Editor is not ready.');
                    }
                },
                error: function (xhr) {
                    Notiflix.Loading.remove();
                    Notiflix.Notify.failure('An error occurred while generating the description.');
                },
                complete: function () {
                    $('#generateBtn').prop('disabled', false).html('<i class="bi bi-lightning"></i> @lang('Generate')');
                }
            });
        });

        const form = document.getElementById('descriptionForm');
        const postUrl = form.action;
        const redirectUrl = '{{ route('user.listing.finishing.setup') }}';


        @include(template().'vendor.listing.partials.cmn_script')
    </script>
@endpush
