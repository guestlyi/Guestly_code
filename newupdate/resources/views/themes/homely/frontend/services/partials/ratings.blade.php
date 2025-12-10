@if(!empty($average_ratings))
    <section class="offer-two offer-rating">
        <div class="container">
            <div class="offer-rating-wrapper">
                <div class="row">
                    <div class="col-lg-2">
                        <h6>@lang('Overall rating')</h6>
                        @for ($i = 5; $i >= 1; $i--)
                            <div class="skills-section">
                                <h6>{{ $i }} @lang('star')</h6>
                                <div class="progress">
                                    <div class="progress-bar" data-progress="{{ $ratingDistribution[$i] ?? 0 }}" style="width: {{ $ratingDistribution[$i] ?? 0 }}%;">
                                        <span>{{ $ratingDistribution[$i] ?? 0 }}%</span>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                    <div class="col-lg-10">
                        <div class="row">
                            <div class="col-lg-2 col-md-6 col-6">
                                <div class="offer-two-container offer-rating-container">
                                    <h6>@lang('Cleanliness')</h6>
                                    <h5>{{ $average_ratings['cleanliness'] ?? 0 }}</h5>
                                    <div class="offer-two-icon">
                                        <img src="{{ asset(template(true).'img/icons/offer-7.png') }}" alt="icon">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-6">
                                <div class="offer-two-container offer-rating-container">
                                    <h6>@lang('Accuracy')</h6>
                                    <h5>{{ $average_ratings['accuracy'] ?? 0 }}</h5>
                                    <div class="offer-two-icon">
                                        <img src="{{ asset(template(true).'img/icons/offer-8.png') }}" alt="icon">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-6">
                                <div class="offer-two-container offer-rating-container">
                                    <h6>@lang('Check-in')</h6>
                                    <h5>{{ $average_ratings['checkin'] ?? 0 }}</h5>
                                    <div class="offer-two-icon">
                                        <img src="{{ asset(template(true).'img/icons/offer-9.png') }}" alt="icon">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-6">
                                <div class="offer-two-container offer-rating-container">
                                    <h6>@lang('Communication')</h6>
                                    <h5>{{ $average_ratings['communication'] ?? 0 }}</h5>
                                    <div class="offer-two-icon">
                                        <img src="{{ asset(template(true).'img/icons/offer-10.png') }}" alt="icon">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-6">
                                <div class="offer-two-container offer-rating-container">
                                    <h6>@lang('Location')</h6>
                                    <h5>{{ $average_ratings['location'] ?? 0 }}</h5>
                                    <div class="offer-two-icon">
                                        <img src="{{ asset(template(true).'img/icons/offer-11.png') }}" alt="icon">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-6 col-6">
                                <div class="offer-two-container offer-rating-container">
                                    <h6>@lang('Value')</h6>
                                    <h5>{{ $average_ratings['value'] ?? 0 }}</h5>
                                    <div class="offer-two-icon">
                                        <img src="{{ asset(template(true).'img/icons/offer-12.png') }}" alt="icon">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
<div class="container mb-5">
    <div class="row g-5 review-show-container" id="review-container">
        @if($property->review->count() > 0)
            @foreach($property->review as $index => $item)
                <div class="col-lg-6 review-item {{ $index >= 6 ? 'd-none' : '' }}">
                    <div class="card shadow-sm border-0 rounded-3 mb-4 p-3">
                        <div class="d-flex align-items-center mb-2">
                            <img src="{{ getFile($item->guest?->image_driver, $item->guest?->image) }}"
                                 alt="{{ $item->guest?->firstname.' '. $item->guest?->lastname }}"
                                 class="rounded-circle me-3 guest-avatar">

                            <div>
                                <h6 class="mb-0 fw-bold">{{ $item->guest?->firstname . ' ' . $item->guest?->lastname }}</h6>
                                <small class="text-muted">
                                    @php
                                        $locationParts = [];
                                        if (!empty($item->guest?->city)) $locationParts[] = $item->guest->city;
                                        if (!empty($item->guest?->state)) $locationParts[] = $item->guest->state;
                                        if (!empty($item->guest?->country)) $locationParts[] = $item->guest->country;
                                        echo implode(', ', $locationParts);
                                    @endphp
                                </small>
                            </div>
                        </div>

                        <ul class="star-rating d-flex flex-row mb-1 justify-content-end">
                            @php
                                $rating = $item->avg_rating;
                                $fullStars = floor($rating);
                                $halfStar = ($rating - $fullStars) >= 0.25 && ($rating - $fullStars) < 0.75;
                                $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
                            @endphp

                            @for ($i = 0; $i < $fullStars; $i++)
                                <li><i class="fa-solid fa-star text-warning"></i></li>
                            @endfor

                            @if ($halfStar)
                                <li><i class="fa-solid fa-star-half-stroke text-warning"></i></li>
                            @endif

                            @for ($i = 0; $i < $emptyStars; $i++)
                                <li><i class="fa-regular fa-star text-warning"></i></li>
                            @endfor
                        </ul>

                        @php
                            $fullComment = $item->comment;
                            $shortComment = Illuminate\Support\Str::limit($fullComment, 80);
                        @endphp
                        <p class="comment-text mb-1"
                           data-full='@json($fullComment)'
                           data-short='@json($shortComment)'>
                            {{ $shortComment }}
                        </p>
                        @if(Str::length($fullComment) > 80)
                            <a href="#0" class="toggle-comment small text-primary">@lang('Read more')</a>
                        @endif

                        @php
                            $repliesData = $item->activeReplies->map(function($r) {
                                return [
                                    'id' => $r->id,
                                    'message' => $r->comment ?? $r->message ?? '',
                                    'user' => $r->guest?->firstname.' '.$r->guest?->lastname ?? 'You',
                                    'created_at' => $r->created_at->format('Y-m-d H:i'),
                                    'imageUrl' => getFile($r->guest?->image_driver, $r->guest?->image),
                                    'guest_id' => $r->guest_id
                                ];
                            });
                        @endphp


                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <small class="text-muted">{{ dateTime($item->created_at) }}</small>
                            <button class="btn btn-sm btn-link text-primary p-0 toggle-reply"
                                    data-review-id="{{ $item->id }}"
                                    data-guest-id="{{ $item->guest_id }}"
                                    data-replies='@json($repliesData)'>
                                <i class="fa-solid fa-reply me-1"></i> @lang('Reply')
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
    @if($property->review->count() > 2)
        <div class="text-center mt-4">
            <button class="btn-3" id="toggle-reviews">
                <div class="btn-wrapper">
                    <div class="main-text btn-single">
                        @lang('Show all ') {{ $property->review->count() }} @lang(' reviews')
                    </div>
                    <div class="hover-text btn-single">
                        @lang('Show all ') {{ $property->review->count() }} @lang(' reviews')
                    </div>
                </div>
            </button>
        </div>
    @endif
</div>
@push('style')
    <style>
        .reply-box .btn-3{
            padding: 6px 18px !important;
        }
        .guest-avatar {
            width: 45px;
            height: 45px;
            object-fit: cover;
        }
        .reply-item img{
            height: 45px;
            width: 45px;
            object-fit: cover;
        }
        .reply-modal-body {
            display: flex;
            flex-direction: column;
            height: 500px; /* adjust as needed */
        }

        /* Review box */
        .modal-review-box {
            flex-shrink: 0;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 0.5rem;
            background-color: #f8f9fa;
        }

        /* Replies container scroll */
        .modal-replies {
            flex-grow: 1;
            overflow-y: auto;
            max-height: 300px;
        }

        /* Reply bubbles */
        .reply-item {
            max-width: 100%;
            padding: 0.5rem 0.75rem;
            border-radius: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .reply-item.text-start {
            background-color: #f1f1f1;
        }

        .reply-item.text-end {
            background-color: #d1e7ff;
        }

        /* Scrollbar style */
        .modal-replies::-webkit-scrollbar {
            width: 6px;
        }

        .modal-replies::-webkit-scrollbar-thumb {
            background-color: rgba(0,0,0,0.2);
            border-radius: 3px;
        }

        /* Reply input */
        .reply-input {
            flex-shrink: 0;
        }
    </style>
@endpush
@push('script')
    <script>
        document.getElementById('toggle-reviews')?.addEventListener('click', function () {
            const btn = this;
            const hiddenReviews = document.querySelectorAll('.review-item.d-none');
            const isShowingAll = hiddenReviews.length === 0;

            const originalText = btn.getAttribute('data-original-text') || btn.textContent;
            btn.setAttribute('data-original-text', originalText);

            btn.innerHTML = `
                <div class="btn-wrapper">
                    <div class="main-text btn-single">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                    </div>
                    <div class="hover-text btn-single">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...
                    </div>
                </div>
            `;
            btn.disabled = true;

            setTimeout(() => {
                if (isShowingAll) {
                    document.querySelectorAll('.review-item').forEach((el, index) => {
                        if (index >= 6) el.classList.add('d-none');
                    });

                    btn.innerHTML = `
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                Show all {{ $property->review->count() }} reviews
                            </div>
                            <div class="hover-text btn-single">
                                Show all {{ $property->review->count() }} reviews
                            </div>
                        </div>
                    `;
                } else {
                    document.querySelectorAll('.review-item').forEach(el => el.classList.remove('d-none'));
                    btn.innerHTML = `
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                 Show less
                            </div>
                            <div class="hover-text btn-single">
                                 Show less
                            </div>
                        </div>
                    `;
                }
                btn.disabled = false;
            }, 500);
        });

        document.addEventListener("DOMContentLoaded", function () {
            const authUserId = {!! json_encode(auth()->id()) !!};
            const host_id = parseInt('{{ $property->host_id }}');

            document.querySelectorAll(".toggle-reply").forEach(btn => {
                let guestId = parseInt(btn.dataset.guestId);
                let replies = JSON.parse(btn.dataset.replies || '[]');
                const isHostOrGuest = authUserId === host_id || authUserId === guestId;

                if (replies.length === 0 && !isHostOrGuest) {
                    btn.classList.add('d-none');
                }

                btn.addEventListener("click", function () {
                    let reviewId = this.dataset.reviewId;

                    document.getElementById("replyReviewId").value = reviewId;
                    document.getElementById("replyMessage").value = "";

                    let modalReplies = document.getElementById("modalReplies");
                    modalReplies.innerHTML = "";

                    if (replies.length === 0) {
                        let emptyDiv = document.createElement("div");
                        emptyDiv.classList.add("text-center", "text-muted", "mt-3");
                        emptyDiv.textContent = "No replies yet.";
                        modalReplies.appendChild(emptyDiv);
                    } else {
                        replies.forEach(r => {
                            let replyDiv = document.createElement("div");

                            if (r.guest_id == authUserId) {
                                replyDiv.classList.add(
                                    "reply-item", "mt-2", "d-flex", "align-items-start",
                                    "gap-2", "justify-content-end", "text-end"
                                );
                            } else {
                                replyDiv.classList.add(
                                    "reply-item", "mt-2", "d-flex", "align-items-start",
                                    "gap-2", "justify-content-start", "text-start"
                                );
                            }

                            let isRight = r.guest_id == authUserId;
                            replyDiv.innerHTML = `
                                ${!isRight ? `<img src="${r.imageUrl}" alt="${r.user}" class="rounded-circle me-2" width="35" height="35">` : ''}
                                    <div class="reply-bubble">
                                        <strong>${r.user}</strong>
                                        <p class="mb-0">${r.message}</p>
                                        <small class="text-muted">${r.created_at}</small>
                                    </div>
                                ${isRight ? `<img src="${r.imageUrl}" alt="${r.user}" class="rounded-circle ms-2" width="35" height="35">` : ''}
                            `;

                            modalReplies.appendChild(replyDiv);
                        });
                    }

                    if (isHostOrGuest) {
                        $('.reply-area-inside-modal').removeClass('d-none');
                    } else {
                        $('.reply-area-inside-modal').addClass('d-none');
                    }

                    let replyModal = new bootstrap.Modal(document.getElementById("replyModal"));
                    replyModal.show();
                });
            });


            document.getElementById("sendReplyBtn").addEventListener("click", function () {
                let reviewId = document.getElementById("replyReviewId").value;
                let message = document.getElementById("replyMessage").value.trim();
                if (!message) {
                    Notiflix.Notify.failure("Please write a reply!");
                    return;
                }

                Notiflix.Loading.circle('Submitting reply...');

                fetch("{{ route('user.review.reply') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ review_id: reviewId, message: message })
                })
                    .then(res => res.json())
                    .then(data => {
                        Notiflix.Loading.remove();
                        if (data.success) {
                            Notiflix.Notify.success("Reply submitted successfully!");

                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            Notiflix.Notify.failure(data.message || "Something went wrong!");
                        }
                    })
                    .catch(err => {
                        Notiflix.Loading.remove();
                        Notiflix.Notify.failure("An error occurred. Please try again.");
                    });
            });
        });
    </script>
@endpush
