@extends(template().'layouts.affiliate')
@section('title',trans('Affiliate Item List'))
@section('content')
    <section class="listing">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('affiliate.dashboard') }}">@lang('Dashboard')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('Items')</li>
                    </ul>
                    <h4>@lang('Item List')</h4>
                </div>
            </div>
            <div class="listing-container">
                <div class="shop-view-content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="list-view-wrapper">
                            <div class="table-responsive d-flex flex-column-reverse">
                                <table class="table table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="text-center">@lang('Property')</th>
                                            <th scope="col" class="text-center">@lang('Landing Page')</th>
                                            <th scope="col" class="text-center">@lang('Vanity Link')</th>
                                            <th scope="col" class="text-center">@lang('Total Click')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($items ?? [] as $item)
                                        <tr>
                                            <td data-label="Listing">
                                                <div class="listing-image-container">
                                                    <div class="listing-image">
                                                        @if($item->thumb)
                                                            <img src="{{ $item->thumb }}" alt="{{ $item->title ?? '' }}">
                                                        @else
                                                            <img src="{{ asset(template(true).'img/no_image.png') }}" alt="@lang('No image available')">
                                                        @endif
                                                    </div>
                                                    <h6 title="{{ $item->title }}">{{ Str::limit($item->title, 40) }}</h6>
                                                </div>
                                            </td>
                                            <td data-label="landing page" class="landingItem">
                                                <a href="{{ $item->url }}" target="_blank">
                                                    {{ $item->url }}<i class="fas fa-external-link-alt"></i>
                                                </a>
                                            </td>
                                            <td data-label="Vanity Link" class="copyable landingItem" onclick="copyToClipboard(this)">
                                                <span class="copy_url">{{ $item->vanity_url }} <i class="far fa-copy ml-2"></i></span>
                                            </td>
                                            <td data-label="Total Click" class="text-center">
                                                {{ $item->total_click }}
                                            </td>
                                        </tr>
                                    @empty
                                        @include('empty')
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $items->appends(request()->query())->links(template().'partials.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('script')
    <script>
        function copyToClipboard(element) {
            const textToCopy = element.querySelector('.copy_url')?.textContent.trim();

            if (!textToCopy) return;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(textToCopy)
                    .then(() => {
                        Notiflix.Notify.success('URL copied to clipboard!', { timeout: 2000 });
                    })
                    .catch(err => {
                        Notiflix.Notify.failure('Failed to copy URL');
                    });
            } else {
                const tempInput = document.createElement('input');
                tempInput.value = textToCopy;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                Notiflix.Notify.success('URL copied to clipboard!', { timeout: 2000 });
            }
        }

    </script>
@endpush

