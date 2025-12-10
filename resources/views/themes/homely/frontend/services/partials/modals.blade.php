@php
    $criteria = [
        'cleanliness' => __('Cleanliness'),
        'accuracy' => __('Accuracy'),
        'checkin' => __('Check-in'),
        'communication' => __('Communication'),
        'location' => __('Location'),
        'value' => __('Value'),
    ];
@endphp

<div class="modal fade review-modal" id="exampleModalTwo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="exampleModalLabel">@lang('Add Review')</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('user.review.store') }}" method="post">
                    @csrf

                    <input type="hidden" name="property_id" value="{{ $property->id }}" />

                    <div class="review-form d-flex align-items-center gap-4 justify-content-between flex-wrap">
                        @foreach($criteria as $key => $label)
                            <div class="review-form-rating">
                                <h6 class="review-form-title">{{ $label }}</h6>
                                <div class="star-rating star-rating-{{ $loop->iteration }}">
                                    @for($i = 5; $i >= 1; $i--)
                                        <input type="radio" name="rating[{{ $key }}]" value="{{ $i }}" id="{{ $key }}-{{ $i }}">
                                        <label for="{{ $key }}-{{ $i }}">&#9733;</label>
                                    @endfor
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="review-input">
                        <label class="review-form-title">@lang('Write Your Review')</label>
                        <textarea name="message"  class="form-control"></textarea>
                    </div>

                    <button type="submit" class="btn-1 mt-4">
                        <div class="btn-wrapper">
                            <div class="main-text btn-single">
                                @lang('Submit Review')
                            </div>
                            <div class="hover-text btn-single">
                                @lang('Submit Review')
                            </div>
                        </div>
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
<div id="reportModal" class="modal fade" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reportModalLabel">@lang('Report')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="reportForm" action="{{ route('user.report.submit') }}" method="post" novalidate>
                @csrf

                <input type="hidden" name="property_id" id="property_id" value="{{ $property->id }}" />

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="reportName" class="form-label">@lang('Name')</label>
                        <input type="text" class="form-control" id="reportName" name="name" placeholder="@lang('Enter your name')" required>
                    </div>
                    <div class="mb-3">
                        <label for="reportDetails" class="form-label">@lang('Details')</label>
                        <textarea class="form-control" id="reportDetails" name="details" rows="5" placeholder="@lang('Enter your report details')"></textarea>
                    </div>
                </div>
                <div class="modal-footer bg-light border-top-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                    <button type="submit" class="btn btn-primary reportSubmit" >@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="priceDetailsModal" class="modal fade" tabindex="-1" aria-labelledby="priceDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content shadow rounded">
            <div class="modal-header bg-light border-bottom-0">
                <h5 class="modal-title fw-bold" id="priceDetailsModalLabel">@lang('Price Summary')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="price-summary p-3">
                    <div class="row mb-2">
                        <div class="col-8">@lang('Nightly Rate')</div>
                        <div class="col-4 text-end fw-medium" id="nightly-rate"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">@lang('Weeks')</div>
                        <div class="col-4 text-end fw-medium" id="weeks-price"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">@lang('Months')</div>
                        <div class="col-4 text-end fw-medium" id="months-price"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8">@lang('Remaining Nights')</div>
                        <div class="col-4 text-end fw-medium" id="remaining-nights"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8 text-danger">@lang('Cleaning Fee')</div>
                        <div class="col-4 text-end fw-medium text-danger" id="cleaning-fee"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8 text-danger">@lang('Service Fee')</div>
                        <div class="col-4 text-end fw-medium text-danger" id="service-fee"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-8 text-success">@lang('Discount Amount')</div>
                        <div class="col-4 text-end fw-medium text-success" id="discount-amount"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="custom-taxes">

                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-8 fw-bold fs-5">@lang('Total Price')</div>
                        <div class="col-4 text-end fw-bold fs-5 text-primary" id="total-price"></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bx-shadow-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade shareModalCs" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4 rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="shareModalLabel">@lang('Share this property')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
            </div>
            <div class="modal-body text-center">
                <div class="d-flex flex-wrap justify-content-center gap-3">
                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" class="btn btn-light shadow-sm rounded-pill px-3">
                        <i class="fa-brands fa-facebook-f me-1"></i> @lang('Facebook')
                    </a>
                    <a target="_blank" href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}" class="btn btn-light shadow-sm rounded-pill px-3">
                        <i class="fa-brands fa-x-twitter me-1"></i> @lang('Twitter')
                    </a>
                    <a target="_blank" href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}" class="btn btn-light shadow-sm rounded-pill px-3">
                        <i class="fa-brands fa-linkedin-in me-1"></i> @lang('LinkedIn')
                    </a>
                    <button type="button" id="copyLink" class="btn btn-light shadow-sm rounded-pill px-3">
                        <i class="fa-regular fa-copy me-1"></i> @lang('Copy Link')
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade review-modal" id="safety_item_modal" tabindex="-1" aria-labelledby="safety_item_modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="safety_item_modalLabel">@lang('Safety & Security Features')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="safety-items-container">
                    @if(!empty($property['safety_items']['core']))
                        <div class="safety-category mb-4">
                            <h6 class="category-title bg-light p-2 rounded">
                                <i class="fas fa-shield-alt me-2"></i> @lang('Essential Safety Features')
                            </h6>
                            <div class="items-grid">
                                @foreach($property['safety_items']['core'] as $item)
                                    <div class="safety-item">
                                        <span class="item-bullet">•</span>
                                        <span class="item-text">{{ $item }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(!empty($property['safety_items']['others']))
                        <div class="safety-category">
                            <h6 class="category-title bg-light p-2 rounded">
                                <i class="fas fa-plus-circle me-2"></i> Additional Safety Items
                            </h6>
                            <div class="items-grid">
                                @foreach($property['safety_items']['others'] as $item)
                                    <div class="safety-item">
                                        <span class="item-bullet">•</span>
                                        <span class="item-text">{{ $item }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="modal-footer bx-shadow-0">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="replyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content p-3">
            <div class="modal-header border-0">
                <h5 class="modal-title">@lang('Replies')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body d-flex flex-column reply-modal-body h-100">
                <div id="modalReplies" class="modal-replies mb-3"></div>

                <div class="reply-area-inside-modal">
                    <div class="reply-input d-flex gap-2">
                        <textarea id="replyMessage" class="form-control" rows="2" placeholder="@lang('Write your reply...')"></textarea>
                        <button type="button" class="btn-3" id="sendReplyBtn">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                    <input type="hidden" id="replyReviewId">
                </div>
            </div>
        </div>
    </div>
</div>
@php
    $refundRules = is_array($property->pricing->refund_infos)
        ? $property->pricing->refund_infos
        : json_decode($property->pricing->refund_infos ?? '[]', true);
@endphp

<div class="modal fade" id="cancellation_policy_modal" tabindex="-1" aria-labelledby="cancellationPolicyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancellationPolicyModalLabel">@lang('Cancellation Policy')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="cancellation-policy-details">

                    @if(!empty($refundRules))
                        <div class="detailed-policy">
                            <h6 class="policy-section-title">@lang('Detailed Cancellation Terms')</h6>
                            <ul class="policy-terms-list">
                                @foreach($refundRules as $rule)
                                    @php
                                        $percentage = isset($rule['percentage']) ? (int) $rule['percentage'] : 0;
                                        if ($percentage >= 75) {
                                            $badgeColor = 'bg-success';
                                        } elseif ($percentage >= 50) {
                                            $badgeColor = 'bg-warning text-dark';
                                        } else {
                                            $badgeColor = 'bg-danger';
                                        }
                                    @endphp
                                    <li class="policy-term-item mb-2">
                                        <div class="term-header d-flex align-items-center gap-2">
                                            <span class="term-badge {{ $badgeColor }} px-2 py-1 text-light rounded">
                                                {{ $percentage }}% @lang('Refund')
                                            </span>
                                            <span class="term-days text-muted">
                                                @lang('Before') {{ $rule['days'] ?? '-' }} @lang('days')
                                            </span>
                                        </div>
                                        <div class="term-description mt-1">
                                            {!! $rule['message'] ?? '' !!}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <p>@lang('Not Applicable')</p>
                    @endif

                </div>
            </div>
            <div class="modal-footer bx-shadow-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="houseRulesModal" tabindex="-1" aria-labelledby="houseRulesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="houseRulesModalLabel">@lang('All House Rules')</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="rules-section">
                    <ul class="rules-list">
                        @forelse(($property->rules ?? []) as $rule)
                            <li>
                                <i class="fas fa-check-circle rule-icon"></i>
                                <span>{{ $rule }}</span>
                            </li>
                        @empty
                            <li>
                                <i class="fas fa-check-circle rule-icon"></i>
                                <span>@lang('No specific rules listed')</span>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
            <div class="modal-footer bx-shadow-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Close')</button>
            </div>
        </div>
    </div>
</div>

