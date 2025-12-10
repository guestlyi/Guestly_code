@extends(template().'layouts.error')
@section('title','500')
@section('content')
    <section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-gray-800 to-black text-white error-section">
        <div class="container mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-10 items-center">

                <div class="d-flex align-items-center justify-content-center ">
                    <img src="{{ asset('assets/global/img/errors/500.jpg')}}" alt="500 Internal Server Error Illustration" class="error-image max-w-sm drop-shadow-2xl animate-bounce-slow">
                </div>

                <div class="text-center md:text-left space-y-6">
                    <h1 class="text-4xl md:text-5xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-red-500 to-purple-600">
                        @lang('Internal Server Error')
                    </h1>
                    <p class="text-lg md:text-xl font-semibold">
                        {{ trans('The server encountered an internal error misconfiguration and was unable to complete your request.') }}
                        <span class="text-red-400">{{ trans('Please contact the server administrator.') }}</span>
                    </p>
                    <p class="text-gray-400">
                        @lang('We’re working to fix this issue. Please try again later.')
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
