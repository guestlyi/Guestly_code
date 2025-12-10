@extends('admin.layouts.app')
@section('page_title', __('Booking Charge Configuration'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Settings')</li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('Socialite Configuration')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('Booking Charge Configuration')</h1>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.control_panel.components.sidebar', ['settings' => config('generalsettings.settings'), 'suffix' => 'settings'])
            </div>
            <div class="col-lg-7">
                <div class="d-grid gap-3 gap-lg-5">
                    <div id="socialAccountsSection" class="card">
                        <div class="card-header">
                            <h4 class="card-title">@lang("Booking Charge Configuration")</h4>
                        </div>
                        <div class="card-body">
                            <div class="page-header inHead">
                                <div class="row align-items-center">
                                    <div class="col-sm">
                                        <h2 class="page-header-title">@lang('Booking Fee')</h2>
                                        <p class="page-header-text">@lang("💸 Set Your Commission Percentage with Ease! Decide how much you'd like to earn from each customer booking by simply entering a percentage. Whether it's 10% or 25%, the system will automatically apply it to every transaction—no extra work needed. You're in control of your earnings! 📊🚀")</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <form action="{{ route('admin.basic.control.booking.charge.update') }}" method="post">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label" for="exampleFormControlInput1">@lang('Enter Amount ')<sup>(%)</sup></label>
                                        <input type="text" id="exampleFormControlInput1" name="charge" class="form-control" value="{{ old('charge', basicControl()->booking_charge) }}" placeholder="@lang('10')">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="exampleFormControlInput1">@lang('User Service Charge')<sup>(%)</sup></label>
                                        <input type="text" id="exampleFormControlInput1" name="user_service_charge" class="form-control" value="{{ old('charge', basicControl()->user_service_charge) }}" placeholder="@lang('10')">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="exampleFormControlInput1">@lang('User GST Charge < 7000')<sup>(%)</sup></label>
                                        <input type="text" id="exampleFormControlInput1" name="user_gst_charge_under_amount" class="form-control" value="{{ old('charge', basicControl()->user_gst_charge_under_amount) }}" placeholder="@lang('10')">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label" for="exampleFormControlInput1">@lang('User GST Charge > 7000')<sup>(%)</sup></label>
                                        <input type="text" id="exampleFormControlInput1" name="user_gst_charge_above_amount" class="form-control" value="{{ old('charge', basicControl()->user_gst_charge_above_amount) }}" placeholder="@lang('10')">
                                    </div>
                                    
                                    
                                    <button class="btn btn-primary btn-sm" type="submit">@lang('Update')</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

