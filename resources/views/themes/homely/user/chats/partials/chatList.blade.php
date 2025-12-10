@forelse($chatList ?? [] as $key => $item)
    <a class="all-message-content {{ request()->id ? (request()->id == $item->id ? 'active' : '') : ($key == 0 ? 'active' : '') }}" href="{{ route('user.chat.list', ['id' => $item->id]) }}">
        @php
            if($item->sender_id == auth()->id()){
                $showUser = $item->receiver;
            }elseif ($item->receiver_id == auth()->id()){
                $showUser = $item->sender;
            }else{
                $showUser = $item->user;
            }
        @endphp
{{--@dd($showUser)--}}
        <div class="all-message-image">
            <img src="{{ getFile($showUser->image_driver, $showUser->image) }}" alt="{{ $showUser->firstname.' '. $showUser->lastname }}">
        </div>
        <div class="all-message-info">
            <div class="all-message-info-top">
                <span>{{ $item->nickName ? $item->nickName : ($showUser->firstname.' '. $showUser->lastname) }}</span>
                <p class="mb-0">{{ Str::limit($item->message, 15) }}</p>
            </div>
            <div class="all-message-info-text">
                <span>{{ \Carbon\Carbon::parse($item->created_at)->format('l d') }}</span>
                @if(isset($item->property?->title))
                    <div class="redirect-property" data-route="{{ route('service.details', $item->property?->slug) }}">
                        <p class="property-title">{!! Str::limit($item->property?->title, 25) !!}</p>
                    </div>
                @endif
            </div>
        </div>
    </a>
@empty
    <div class="text-center py-4">
        <p class="text-muted mb-0">@lang('No conversation here')</p>
    </div>
@endforelse

@push('style')
    <style>
        .all-message-info-text img{
            height: 34px;
            width: 34px;
            border-radius: 50%;
        }
        .redirect-property{
            cursor: pointer;
        }
        .redirect-property .property-title{
            font-size: 12px;
        }
        .all-message-content{
            color: var(--text-color-1);
        }

    </style>
@endpush

@push('script')
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".redirect-property").forEach(function (el) {
                el.addEventListener("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    let route = this.getAttribute("data-route");
                    if (route) {
                        window.location.href = route;
                    }
                });
            });
        });
    </script>
@endpush
