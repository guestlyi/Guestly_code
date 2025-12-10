@if(isset($faq))
    <section class="faq pt-0">
        <div class="container">
            <div class="faq-container">
                <div class="common-title">
                    <h3>{{ $faq['single']['title'] ?? '' }}</h3>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="faq-left-container">
                            <div class="faq-left-image">
                                <img src="{{ getFile($faq['single']['media']->image->driver, $faq['single']['media']->image->path) }}" alt="@lang('image')">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="faq-right-container">
                            <div class="faq-accordion">
                                <div class="accordion accordion-flush" id="accordionFlushExample">
                                    @foreach($faq['multiple'] ?? [] as $index => $item)
                                        @php
                                            $collapseId = 'flush-collapse-' . $index;
                                            $headingId = 'flush-heading-' . $index;
                                            $isFirst = $loop->first;
                                        @endphp
                                        <div class="accordion-item">
                                            <h6 class="accordion-header" id="{{ $headingId }}">
                                                <button class="accordion-button {{ $isFirst ? '' : 'collapsed' }}" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#{{ $collapseId }}"
                                                        aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                                        aria-controls="{{ $collapseId }}">
                                                    {{ $item['question'] ?? '' }}
                                                </button>
                                            </h6>
                                            <div id="{{ $collapseId }}"
                                                 class="accordion-collapse collapse {{ $isFirst ? 'show' : '' }}"
                                                 data-bs-parent="#accordionFlushExample"
                                                 aria-labelledby="{{ $headingId }}">
                                                <div class="accordion-body">
                                                    <p>{{ $item['answer'] ?? '' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

