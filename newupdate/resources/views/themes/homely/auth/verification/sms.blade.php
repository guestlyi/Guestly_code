@extends(template().'layouts.app')
@section('title',$page_title)

@section('content')
    @php
        $logInContent = logInContent();
        $socialData = getSocialData();

        $media = $logInContent['single']->content->media;
        $imagePath = $media->image->path ?? null;
        $imageDriver = $media->image->driver ?? null;
    @endphp

    <section class="sign-in">
        <div class="bg-layer" style="background: url({{ asset(template(true).'img/signin-bg.png') }});"></div>
        <div class="container">
            <div class="sign-in-container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="sign-in-image text-end">
                            <img
                                src="{{ getFile($imageDriver, $imagePath) }}"
                                alt="image">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="sign-in-form-container">
                            <div class="sign-in-logo pb-3">
                                <a href="{{ route('page','/') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="{{ basicControl()->site_title.' logo' }}"></a>
                            </div>
                            <h4>@lang('Verify Your Mobile Number')</h4>
                            <div class="mt-3 mb-3">
                                <p class="d-flex flex-wrap">@lang("Your Mobile Number is") {!! maskString(auth()->user()->phone) !!}</p>
                            </div>
                            <div class="sign-in-form">
                                <form action="{{ route('user.sms.verify') }}" method="post">
                                    @csrf

                                    <div class="sign-in-form-group">
                                        <input class="sign-in-input" type="text" name="code" value="{{old('code')}}" placeholder="@lang('Code')" autocomplete="off">
                                        @error('code')
                                        <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="sign-in-btn">
                                        <button type="submit" class="btn-1">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    @lang('Submit')
                                                </div>
                                                <div class="hover-text btn-single">
                                                    @lang('Submit')
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </form>
                                <div class="swap-area">
                                    <h5>@lang('Didn\'t get Code? Click to ')<a href="{{route('user.resend.code')}}?type=mobile"  class="text-info"> @lang('Resend code')</a></h5>
                                    @error('resend')
                                    <p class="text-danger  mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
