<div class="service-details-rating">
    <div class="row justify-content-center">
        <div class="col-lg-4">
            <div class="overall-rating">
                <div class="overall-rating-title">
                    <img src="{{ asset(template(true).'img/left.png') }}" alt="">
                    <h4 class="service-review-title"> {{ number_format($property->review_avg_avg_rating, 1) }} </h4>
                    <img src="{{ asset(template(true).'img/right.png') }}" alt="">
                </div>
                <div class="overall-rating-inner">
                    <h3>@lang('Overall rating')</h3>
                    <p>@lang('This home is a guest favorite based on ratings, reviews, and reliability')</p>
                </div>
                @if(auth()->check() && $property->isReviewable(auth()->user()))
                    <div class="review-details-btn mt-3">
                        <button type="button" class="btn-1" data-bs-toggle="modal" data-bs-target="#exampleModalTwo">
                            <div class="btn-wrapper">
                                <div class="main-text btn-single">
                                    @lang('WRITE A REVIEW')
                                </div>
                                <div class="hover-text btn-single">
                                    @lang('WRITE A REVIEW')
                                </div>
                            </div>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@push('style')
    <style>
        .star-rating {
            flex-direction: row-reverse !important;
        }

    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.toggle-comment').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const p = btn.previousElementSibling;
                    const isExpanded = btn.textContent.trim() === 'See less' || btn.textContent.trim() === 'Hide';

                    if (isExpanded) {
                        p.textContent = p.dataset.short;
                        btn.textContent = 'Read more';
                    } else {
                        p.textContent = p.dataset.full;
                        btn.textContent = 'See less';
                    }
                });
            });
        });
    </script>
@endpush
