@extends(template().'layouts.app')
@section('title',trans('Email Reset Link'))
@section('content')
    <section class="sign-in">
        <div class="bg-layer" style="background: url({{ asset(template(true).'img/signin-bg.png') }});"></div>
        <div class="container">
            <div class="sign-in-container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="sign-in-image text-end">
                            <img src="{{ getFile($singleContent->content->media->image_two->driver, $singleContent->content->media->image_two->path) }}"
                                 alt="image">
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center">
                        <div class="sign-in-form-container">
                            <div class="sign-in-logo">
                                <a href="{{ url('/') }}"><img src="{{ getFile(basicControl()->logo_driver, basicControl()->logo) }}" alt="logo"></a>
                            </div>
                            <div class="sign-in-title">
                                <h3>@lang('Enter Email')</h3>
                                <p>@lang('If the provided email is associated with an account, a password reset link will be sent to it').</p>
                            </div>
                            <div class="sign-in-form">
                                <form action="{{ route('affiliate.password.email') }}" method="POST">
                                    @csrf

                                    <div class="row g-4">
                                        <div class="col-lg-12">
                                            <input type="email" name="email" class="form-control" placeholder="@lang('e.g. johnDoe@example.com')">
                                        </div>
                                    </div>
                                    <div class="sign-in-btn">
                                        <button type="submit" class="btn-1">
                                            <div class="btn-wrapper">
                                                <div class="main-text btn-single">
                                                    @lang('Send Password Reset Link')
                                                </div>
                                                <div class="hover-text btn-single">
                                                    @lang('Send Password Reset Link')
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
@include(template().'auth.style')
@push('style')
    <style>
        .booking-signin .sign-in-btn button {
            margin-top: 10px !important;
            width: 100%;
            padding: 20px 16px;
            text-transform: capitalize;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            font-size: 18px;
        }
    </style>
@endpush
