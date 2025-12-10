@extends(template().'layouts.user')
@section('title',trans('List Introduction'))
@section('content')
    <section class="listing-details-1">
        <div class="container">
            <div class="personal-info-title listing-top">
            </div>
            <div class="row">
                <div class="col-lg-6 offset-lg-3">
                    <div class="listing-details-1-content">
                        <h3>@lang('Welcome back'), {{ auth()->user()->firstname }}</h3>
                        <h5>@lang('Finish your listing')</h5>
                        <ul class="listing-details-1-list">
                            <li>
                                <a href="#" id="get-incomplete-listing">
                                    <div class="icon">
                                        <i class="fa-solid fa-house"></i>
                                    </div>
                                    <span>@lang('Finish Your Listing')</span>
                                </a>
                            </li>
                        </ul>
                        <h5 class="pt_50">@lang('Create a new listing') </h5>
                        <ul class="listing-details-1-list">
                            <li>
                                <a href="{{ route('user.listing.introduction.guide') }}">
                                    <div class="icon">
                                        <i class="fa-regular fa-house"></i>
                                    </div>
                                    <span>@lang('Create a new listing')</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div id="incomplete-listing-modal" class="d-none">
            <div class="modal-content">
                <button class="close-btn" aria-label="Close"><i class="fas fa-xmark"></i></button>
                <div id="listing-data">@lang('Loading...')</div>
            </div>
        </div>
    </section>

@endsection
@push('style')
    <style>
        #incomplete-listing-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            z-index: 1050;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #incomplete-listing-modal .modal-content {
            background: #fff;
            padding: 20px 20px 15px;
            border-radius: 12px;
            width: 90%;
            max-width: 450px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        #incomplete-listing-modal .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            font-size: 13px;
            cursor: pointer;
            color: #888;
            transition: color 0.2s ease-in-out;
            padding: 3px 8px;
            border: 1px solid #e6e3e3;
            border-radius: 6px;
        }

        #incomplete-listing-modal .close-btn:hover {
            color: #000;
        }
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            border: 2px dashed #ddd;
            border-radius: 12px;
            background: #f9fafb;
        }

        .empty-state-img {
            width: 120px;
            margin-bottom: 20px;
            opacity: 0.8;
        }

        .empty-state-title {
            margin-bottom: 10px;
            font-size: 18px;
            font-weight: 600;
            color: #333;
        }

        .empty-state-text {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
@endpush

@push('script')
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                @foreach ($errors->all() as $error)
                Notiflix.Notify.failure(@json($error));
                @endforeach
            });
        </script>
    @endif
    <script>
        $(document).ready(function () {
            $('#get-incomplete-listing').on('click', function (e) {
                e.preventDefault();

                Notiflix.Loading.standard('Loading...');

                $.ajax({
                    url: "{{ route('user.property.incomplete.list') }}",
                    method: 'GET',
                    success: function (response) {
                        Notiflix.Loading.remove();

                        if (response.length === 0) {
                            let noListingHtml = `
                                <div class="empty-state">

                                    <h5 class="empty-state-title">
                                        @lang('No incomplete listings found')
                                    </h5>
                                    <p class="empty-state-text">
                                        @lang('You don’t have any incomplete listings yet. Start creating one now!')
                                    </p>
                                </div>
                            `;
                            $('#listing-data').html(noListingHtml);
                        } else {
                            let html = '<ul class="incomplete-listing">';

                            response.forEach(function (item) {
                                html += `
                                    <li style="display:flex;align-items:center;margin-bottom:15px;">
                                        <img src="${item.thumb}" alt="Listing Image" style="width:80px;height:60px;object-fit:cover;border-radius:6px;margin-right:15px;">
                                        <div>
                                            <p style="margin:0;font-weight:bold;">${item.title ?? 'Untitled Listing'}</p>
                                            <a href="${item.url}" class="btn-4 mt-1">@lang('Continue Editing')</a>
                                        </div>
                                    </li>
                                `;
                            });

                            html += '</ul>';
                            $('#listing-data').html(html);
                        }

                        $('#incomplete-listing-modal').removeClass('d-none');
                    },
                    error: function (xhr) {
                        Notiflix.Loading.remove();
                        Notiflix.Notify.failure('Failed to fetch incomplete listings.');
                    }
                });
            });

            $('#incomplete-listing-modal .close').on('click', function () {
                $('#incomplete-listing-modal').addClass('d-none');
            });

            $(window).on('click', function (e) {
                if ($(e.target).is('#incomplete-listing-modal')) {
                    $('#incomplete-listing-modal').addClass('d-none');
                }
            });
        });
        $(document).on('click', '.close-btn', function (e) {
            e.preventDefault();
            $('#incomplete-listing-modal').addClass('d-none');
        });
    </script>
@endpush
