@extends(template().'layouts.error')
@section('title','419')


@section('content')
    <section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white error-section">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-10 items-center">

                <div class="d-flex align-items-center justify-content-center">
                    <img src="{{ asset('assets/global/img/errors/419.jpg')}}"
                         alt="419 Session Expired Illustration"
                         class="error-image max-w-sm drop-shadow-2xl animate-bounce-slow">
                </div>

                <div class="text-center md:text-left space-y-6">
                    <h1 class="text-5xl md:text-6xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-red-500">
                        @lang('419')
                    </h1>
                    <p class="text-xl md:text-2xl font-semibold">
                        {{ trans('Sorry, your session') }}
                        <span class="text-amber-400">{{ trans('has expired') }}</span>
                    </p>
                    <p class="text-gray-400">
                        @lang('Please refresh the page or return to the homepage to continue browsing.')
                    </p>

                    <div>
                        <a class="btn-3 mt-3" href="{{ url('/') }}">
                            <div class="btn-wrapper">
                                <div class="main-text btn-single">
                                    @lang('Back To Home')
                                </div>
                                <div class="hover-text btn-single">
                                    @lang('Back To Home')
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .btn-2:hover{
            color: #F23F3F !important;
        }
        .error-section{
            z-index: 1;
        }
        @keyframes bounce-slow {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .animate-bounce-slow {
            animation: bounce-slow 3s infinite;
        }
    </style>
@endsection
