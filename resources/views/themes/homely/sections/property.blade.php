@if(isset($property))
    <section class="property">
        <div class="container">
            <div class="property-container">
                <div class="bg-layer" style="background: url({{ getFile(optional(optional(optional($property['single'])['media'])->image)->driver,optional(optional(optional($property['single'])['media'])->image)->path) }});"></div>
                <div class="property-content">
                    <h3>{{ $property['single']['title'] ?? '' }}</h3>
                    <p>{{ $property['single']['sub_title'] ?? '' }}</p>
                    <a href="{{ route('user.enter.home') }}" class="btn-1">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">{{ $property['single']['button_name'] ?? '' }}</div>
                            <div class="hover-text btn-single">{{ $property['single']['button_name'] ?? '' }}</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>
@endif

