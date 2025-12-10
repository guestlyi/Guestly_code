<div class="profile-cover">
    <div class="profile-cover-img-wrapper">
        <img id="profileCoverImg" class="profile-cover-img" src="{{ asset('assets/admin/img/img1.jpg') }}" alt="Image Description"/>
    </div>
</div>

<div class="text-center mb-5">
    <label class="avatar avatar-xxl avatar-circle avatar-uploader profile-cover-avatar" for="editAvatarUploaderModal">
        <img id="editAvatarImgModal" class="avatar-img" src="{{ getFile($affiliate->image_driver, $affiliate->image) }}" alt="Image Description">
    </label>

    <h1 class="page-header-title">
        {{ $affiliate->firstname.' '.$affiliate->lastname }}
        <i class="bi bi-globe fs-3 text-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="Verified Affiliate"></i>
    </h1>

    <ul class="list-inline list-px-2">
        @if($affiliate->country)
            <li class="list-inline-item">
                <i class="bi-geo-alt me-1"></i>
                @if($affiliate->city)
                    <a class="text-secondary" href="#">{{ $affiliate->city }}@if($affiliate->state || $affiliate->country),@endif</a>
                @endif

                @if($affiliate->state)
                    <a class="text-secondary" href="#">{{ $affiliate->state }}@if($affiliate->country),@endif</a>
                @endif

                <a class="text-secondary" href="#">{{ $affiliate->country }}</a>
            </li>
        @endif

        <li class="list-inline-item">
            <i class="bi-calendar-week me-1"></i>
            <span>@lang('Joined') {{ \Carbon\Carbon::parse($affiliate->created_at)->format('F Y') }}</span>
        </li>
    </ul>
</div>
<div class="js-nav-scroller hs-nav-scroller-horizontal mb-5">
    <span class="hs-nav-scroller-arrow-prev display-none">
      <a class="hs-nav-scroller-arrow-link" href="javascript:;">
        <i class="bi-chevron-left"></i>
      </a>
    </span>

    <span class="hs-nav-scroller-arrow-next display-none">
      <a class="hs-nav-scroller-arrow-link" href="javascript:;">
        <i class="bi-chevron-right"></i>
      </a>
    </span>

    <ul class="nav nav-tabs align-items-center">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.affiliate.profile.view') ? 'active' : '' }}" href="{{ route('admin.affiliate.profile.view', $affiliate->id) }}">@lang('Profile')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.affiliate.profile.transaction') ? 'active' : '' }}" href="{{ route('admin.affiliate.profile.transaction', $affiliate->id) }}">@lang('Transaction')</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.affiliate.profile.withdraw') ? 'active' : '' }}" href="{{ route('admin.affiliate.profile.withdraw', $affiliate->id) }}">@lang('Withdraw')</a>
        </li>

        <li class="nav-item ms-auto">
            <div class="d-flex gap-2">
                <a class="btn btn-white btn-sm" href="{{ route('admin.affiliate.profile.edit', $affiliate->id) }}">
                    <i class="bi-person-plus-fill me-1"></i> @lang('Edit profile')
                </a>

                <div class="dropdown nav-scroller-dropdown">
                    <button type="button" class="btn btn-white btn-icon btn-sm" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi-three-dots-vertical"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="profileDropdown">
                        <span class="dropdown-header">@lang('Settings')</span>
                        <a class="dropdown-item" href="{{ route('admin.affiliate.profile.send.mail', $affiliate->id) }}"> <i
                                class="bi-envelope dropdown-item-icon"></i> @lang('Send Mail') </a>
                        <a class="dropdown-item blockProfile" href="javascript:void(0)"
                           data-route="{{ route('admin.affiliate.profile.block', $affiliate->id) }}"
                           data-bs-toggle="modal" data-bs-target="#blockProfileModal">
                            <i class="{{ ($affiliate->status == 1) ? 'bi-plus-circle' : 'bi-x-circle' }}  dropdown-item-icon"></i>@lang($affiliate->status == 1 ? 'Block Profile' : 'Unblock Profile')  </a>
                        <a class="dropdown-item loginAccount" href="javascript:void(0)"
                           data-route="{{ route('admin.affiliate.login.as', $affiliate->id) }}"
                           data-bs-toggle="modal" data-bs-target="#loginAsAffiliateModal">
                            <i class="bi bi-box-arrow-in-right dropdown-item-icon"></i>
                            @lang('Login As Affiliate')
                        </a>
                        <a class="dropdown-item addBalance" href="javascript:void(0)"
                           data-route="{{ route('admin.affiliate.profile.update.balance', $affiliate->id) }}"
                           data-balance="{{ currencyPosition($affiliate->balance) }}"
                           data-bs-toggle="modal" data-bs-target="#addBalanceModal">
                            <i class="bi bi-cash-coin dropdown-item-icon"></i>
                            @lang('Manage Balance')
                        </a>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</div>
