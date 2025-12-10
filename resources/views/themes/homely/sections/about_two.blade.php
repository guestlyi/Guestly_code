
@if(isset($about_two))
    <section class="about-two">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="about-two-left-content">
                    <div class="common-title">
                        <h3>{{ $about_two['single']['heading'] ?? '' }}</h3>
                        <h6>{{ $about_two['single']['title'] ?? '' }}</h6>
                        <p>{!! $about_two['single']['description'] ?? '' !!} </p>
                        
                        <a class="btn-2 mt-4" href="{{ $about_two['single']['media']->my_link }}">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                               {{ $about_two['single']['button_name'] ?? 'Explore' }}
                                
                                   <i class="fa-regular fa-angle-right"></i>
                               
                            </div>
                            <div class="hover-text btn-single">
                               {{ $about_two['single']['button_name'] ?? 'Explore' }}
                               
                                   <i class="fa-regular fa-angle-right"></i>
                               
                            </div>
                        </div>
                    </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-two-right-content">
                    <div class="about-two-right-image">
                        <img src="{{ getFile($about_two['single']['media']->image->driver, $about_two['single']['media']->image->path) }}" alt="@lang('About Two Image')">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
