@extends(template().'layouts.user')
@section('title',trans('KYC Verification History'))
@section('content')
    <section class="listing">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('KYC Verification')</li>
                    </ul>
                    <h4>@lang('KYC Verification History')</h4>
                </div>
                <a href="{{ route('user.verification.kyc') }}" class="btn-4"><i class="fas fa-arrow-left pe-1"></i>@lang('Back')</a>
            </div>
            <div class="listing-container">
                <div class="shop-view-content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="list-view-wrapper">
                            <div class="table-responsive d-flex flex-column-reverse">
                                <table class="table table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th class="text-center" scope="col">@lang('Serial')</th>
                                            <th class="text-center" scope="col">@lang('Type')</th>
                                            <th class="text-center" scope="col">@lang('Status')</th>
                                            <th class="text-center" scope="col">@lang('Submitted Date')</th>
                                            <th class="text-center" scope="col">@lang('Approved Date')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($userKyc ?? [] as $key => $item)
                                        <tr>
                                            <td data-label="@lang('Serial No.')" class="text-center">
                                                {{$loop->iteration}}
                                            </td>
                                            <td data-label="@lang('Kyc Type')" class="text-center">
                                                <span class="font-weight-bold"  class="text-center">
                                                    {{$item->kyc_type}}
                                                </span>
                                            </td>
                                            <td data-label="@lang('Kyc Status')" class="text-center">
                                                @if($item->status == 0)
                                                    <span class="badge bg-warning-subtle text-warning">@lang('Pending')</span>
                                                @elseif($item->status == 1)
                                                    <span class="badge bg-success-subtle text-success">@lang('Accepted')</span>
                                                @elseif($item->status == 2)
                                                    <span class="badge bg-danger-subtle text-danger">@lang('Rejected')</span>
                                                @endif
                                            </td>
                                            <td data-label="@lang('Submitted Date')" class="text-center">
                                                {{dateTime($item->created_at) }}
                                            </td>
                                            <td data-label="@lang('Approved Date')" class="text-center">
                                                {{ $item->approved_at ? dateTime($item->approved_at, 'd M Y') : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        @include('empty')
                                    @endforelse
                                    </tbody>
                                </table>
                                {{ $userKyc->appends(request()->query())->links(template().'partials.pagination') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

