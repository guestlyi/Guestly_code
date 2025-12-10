@extends(template().'layouts.app')
@section('title',trans('Password Email'))
@section('content')
    @php
        $logInContent = logInContent();
        $socialData = getSocialData();

        $media = $logInContent['single']->content->media;
        $imagePath = $media->image->path ?? null;
        $imageDriver = $media->image->driver ?? null;
    @endphp

    <section class="sign-in">
        <div class="bg-layer"
             style="background: url({{ asset(template(true).'img/signin-bg.png') }});"></div>
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
                            <h4>@lang('Enter Email')</h4>
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <div class="sign-in-form">
                                <form action="{{ route('password.email') }}" method="post">
                                    @csrf

                                    <div class="sign-in-form-group">
                                        <input id="email" type="email" class="sign-in-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="e.g. john@example.com" autofocus>

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>
                                    <div class="sign-in-btn">
                                        <button type="submit" class="btn-1">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    @lang('Send Reset Link')
                                                </div>
                                                <div class="hover-text btn-single">
                                                    @lang('Send Reset Link')
                                                </div>
                                            </div>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
