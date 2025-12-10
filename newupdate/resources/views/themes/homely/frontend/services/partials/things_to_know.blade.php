<section class="service-details-things pb-5">
    <div class="container">
        <div class="service-details-things-container">
            <h1 class="things-title">@lang('Things to know')</h1>
            <div class="sections-grid">
                <div class="section-card">
                    <h2 class="section-title">@lang('House rules')</h2>
                    <ul class="section-list">
                        @if(!empty($property->rules) && is_array($property->rules))
                            @foreach($property->rules as $index => $rule)
                                @if($index < 2)
                                    <li>{{ $rule }}</li>
                                @endif
                            @endforeach
                        @else
                            <li>@lang('Not Applicable')</li>
                        @endif
                    </ul>

                    @if(isset($property->rules) && count($property->rules) > 2)
                        <button class="show-more-btn" data-bs-toggle="modal" data-bs-target="#houseRulesModal">
                            @lang('Show more') <span class="arrow">></span>
                        </button>
                    @endif
                </div>

                @php
                    $coreItems = $property['safety_items']['core'] ?? [];
                    $otherItems = $property['safety_items']['others'] ?? [];

                    $coreCount = count($coreItems);
                    $maxTotal = 3;

                    if ($coreCount >= 3) {
                        $coreShow = 2;
                        $otherShow = 1;
                    } elseif ($coreCount == 2) {
                        $coreShow = 2;
                        $otherShow = 1;
                    } elseif ($coreCount == 1) {
                        $coreShow = 1;
                        $otherShow = 2;
                    } else {
                        $coreShow = 0;
                        $otherShow = $maxTotal;
                    }

                    $coreShow = min($coreShow, $coreCount);
                    $otherShow = min($otherShow, count($otherItems));
                @endphp

                <div class="section-card">
                    <h2 class="section-title">@lang('Safety & Security')</h2>
                    <ul class="section-list">
                        @if(!empty($coreItems) || !empty($otherItems))
                            @if(!empty($coreItems))
                                @foreach(array_slice($coreItems, 0, $coreShow) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            @endif
                            @if(!empty($otherItems))
                                @foreach(array_slice($otherItems, 0, $otherShow) as $item)
                                    <li>{{ $item }}</li>
                                @endforeach
                            @endif
                        @else
                            <li>@lang('Not Applicable')</li>
                        @endif

                    </ul>
                    @if(!empty($coreItems) || !empty($otherItems))
                        <button class="show-more-btn" data-bs-target="#safety_item_modal" data-bs-toggle="modal">
                            @lang('Show more') <span class="arrow">></span>
                        </button>
                    @endif
                </div>
                <div class="section-card">
                    <h2 class="section-title">@lang('Cancellation policy')</h2>
                    <ul class="section-list">
                        @if(!empty($property->pricing->refund_infos))
                            @foreach(array_slice($property->pricing->refund_infos, 0, 2) as $rule)
                                <li>{{ $rule['message'] ?? '' }}</li>
                            @endforeach
                        @else
                            <li>@lang('Not Applicable')</li>
                        @endif
                    </ul>

                    @if(!empty($property->pricing->refund_infos))
                        <button class="show-more-btn" data-bs-target="#cancellation_policy_modal" data-bs-toggle="modal">
                            @lang('Show more') <span class="arrow">></span>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@include(template().'frontend.services.partials.things_style')
