@extends(template().'layouts.user')
@section('title',trans('KYC Verification Center'))
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
                    <h4>@lang('KYC Verification')</h4>
                </div>
                <a href="{{ route('user.verification.kyc.history') }}" class="btn-3 other_btn">
                    <div class="btn-wrapper">
                        <div class="main-text btn-single">
                            @lang('History')
                        </div>
                        <div class="hover-text btn-single">
                            @lang('History')
                        </div>
                    </div>
                </a>
            </div>
            <div class="listing-container">
                <div class="shop-view-content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="list-view-wrapper">
                            <div class="table-responsive d-flex flex-column-reverse">
                                @php
                                    $rows = [];
                                    if (config('services.sumsub.status')) {
                                        $rows[] = [
                                            'name' => __('KYC Verification'),
                                            'action' => route('user.sumsub.kyc.check'),
                                        ];
                                    }

                                    foreach ($kyc ?? [] as $item) {
                                        $rows[] = [
                                            'name' => $item->name,
                                            'action' => route('user.verification.kyc.form', $item->id),
                                        ];
                                    }
                                @endphp

                                <table class="table table-striped align-middle">
                                    <thead>
                                    <tr>
                                        <th class="text-center w-10" scope="col">@lang('SL')</th>
                                        <th class="text-center w-50" scope="col">@lang('Type')</th>
                                        <th class="text-center w-40" scope="col">@lang('Action')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse ($rows as $index => $row)
                                        <tr>
                                            <td class="text-center" data-label="@lang('SL')">{{ $index + 1 }}</td>
                                            <td class="text-center" data-label="@lang('Type')">
                                                <span class="font-weight-bold">{{ $row['name'] }}</span>
                                            </td>
                                            <td class="text-center" data-label="@lang('Action')">
                                                <a class="btn-3" href="{{ $row['action'] }}">
                                                    @lang('Verify')
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center">@lang('No KYC types available.')</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset(template(true) . 'css/flatpickr.min.css') }}"/>
@endpush

