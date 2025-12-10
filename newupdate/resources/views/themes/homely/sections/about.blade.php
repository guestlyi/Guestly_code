@if(isset($about))
    <section class="about">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="about-left-content">
                        <div class="about-left-content-inner">
                            <div class="common-title">
                                <h3>{{ $about['single']['heading'] ?? '' }}</h3>
                            </div>
                            <p>
                                {!! $about['single']['description'] ?? '' !!}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="about-right-container">
                        <div class="about-right-container-inner">
                            <div class="about-image-container">
                                <div class="about-image-left-item">
                                    <img src="{{ getFile($about['single']['media']->image->driver, $about['single']['media']->image->path) }}" alt="@lang('First About Image')">
                                </div>
                            </div>
                            <div class="about-image-container">
                                <div class="about-image-item">
                                    <img src="{{ getFile($about['single']['media']->image_two->driver, $about['single']['media']->image_two->path) }}" alt="@lang('Second About Image')">
                                </div>
                                <div class="about-image-item">
                                    <img src="{{ getFile($about['single']['media']->image_three->driver, $about['single']['media']->image_three->path) }}" alt="@lang('Third About Image')">
                                </div>
                            </div>
                        </div>
                        <div class="about-right-container-bg">
                            <img src="{{ getFile($about['single']['media']->background_image->driver, $about['single']['media']->background_image->path) }}" alt="@lang('Background Image')">
                        </div>
                        <div class="about-right-container-logo">
                            <img src="{{ getFile($about['single']['media']->shape_image->driver, $about['single']['media']->shape_image->path) }}" alt="@lang('Shape Image')">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

