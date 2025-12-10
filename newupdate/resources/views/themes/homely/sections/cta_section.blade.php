@if($cta_section)
    <section class="cta-section">
        <div class="container">
            <div class="cta-section-inner" style="background-image: url({{ getFile($cta_section['single']['media']->image->driver, $cta_section['single']['media']->image->path) }})">
                <div class="text-box">
                    <h2 class="section-title">
                        {{ $cta_section['single']['heading'] ?? '' }}
                        <span class="highlight">{{ basicControl()->site_title }}</span>
                    </h2>
                    <p class="description">
                        {{ $cta_section['single']['sub_heading'] ?? '' }}
                    </p>
                    <a href="{{ route('user.enter.home') }}" class="btn-1">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">{{ $cta_section['single']['button_text'] ?? '' }}</div>

                            <div class="hover-text btn-single">{{ $cta_section['single']['button_text'] ?? '' }}</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endif

