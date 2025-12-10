@if(isset($contact))
    <section class="contact-details">
        <div class="container">
            <div class="row g-4 gy-sm-5">
                @foreach($contact['multiple'] as $item)
                    <div class="col-lg-3 col-sm-6">
                        <div class="contact-details-content">
                            <div class="contact-details-icon">
                                <i class="{{ $item['icon_class'] ?? 'fa-light fa-question' }}"></i>
                            </div>
                            <div class="contact-details-info">
                                <h6>{{ $item['title'] ?? '' }}</h6>
                                @if(strpos($item['value'] ?? '', '@') !== false)
                                    <a href="mailto:{{ $item['value'] }}">{{ $item['value'] }}</a>
                                @else
                                    <a href="tel:{{ $item['value'] }}">{{ $item['value'] }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="contact">
        <div class="container">
            <div class="contact-container">
                <div class="row">
                    <div class="col-lg-7 col-md-9">
                        <div class="contact-form">
                            <h3>{{ $contact['single']['send_message_title'] ?? '' }}</h3>

                            <form action="{{ route('contact.send') }}" method="post">
                                @csrf

                                <div class="row">
                                    <div class="col-lg-6">
                                        <input type="email" class="cmn-input" name="email" placeholder="@lang('Your Email')" required>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="text" class="cmn-input" name="subject" placeholder="@lang('Your Subject')" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <textarea class="cmn-input" name="message" placeholder="@lang('Your Message')" required>{{ old('message', $contact['single']['send_message_text'] ?? '') }}</textarea>

                                        <div class="checkbox-input">
                                            <input type="checkbox" class="form-check-input" id="agree_terms" required>
                                            <label for="agree_terms">@lang($contact['single']['agree_with_term_text'])</label>
                                        </div>

                                        <button type="submit" class="btn-1">
                                            {{ $contact['single']['submit_button'] ?? __('Submit') }}
                                            <i class="icon-arrow-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="contact-image">
                    <img src="{{ getFile($contact['single']['media']->background_image->driver, $contact['single']['media']->background_image->path) }}" alt="image">
                </div>
            </div>
        </div>
    </section>

    <section class="contact-map">
        <div class="container">
            <iframe src="{{ $contact['single']['media']->map_link ?? '' }}" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </section>
@endif

