<div class="listing-top">
    <div class="logo-box">
        <div class="logo"><a href="{{ route('page','/') }}"><img src="{{ getFile(basicControl()->favicon_driver, basicControl()->favicon) }}" alt="logo"></a></div>
    </div>
    <div class="save-btn">
        <a href="{{ route('user.property.list') }}"  class="btn-1">
            <div class="btn-wrapper">
                <div class="main-text btn-single">
                    @lang('Save & Exit')
                </div>
                <div class="hover-text btn-single">
                    @lang('Save & Exit')
                </div>
            </div>
        </a>
    </div>
</div>
<div class="row align-items-center justify-content-center">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="listing-progress enhanced-progress">
            <div class="progress-header d-flex justify-content-between">
                <span>@lang('Step') {{ $currentStep }} @lang('of') {{ $totalSteps }}</span>
                <span>{{ $phase }}</span>
                <span>{{ round(($currentStep / $totalSteps) * 100) }}%</span>
            </div>
            <div class="progress">
                <div class="progress-bar" style="width: {{ ($currentStep / $totalSteps) * 100 }}%;"></div>
            </div>
            <ul class="progress-steps d-flex justify-content-between">
                @for ($i = 1; $i <= $totalSteps; $i++)
                    <li class="{{ $i <= $currentStep ? 'active' : '' }}">
                        <span>{{ $i }}</span>
                    </li>
                @endfor
            </ul>
        </div>
    </div>
</div>
