@extends('admin.layouts.app')
@section('page_title', __('KYC Setting'))
@section('content')
    <div class="content container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item">
                                <a class="breadcrumb-link" href="javascript:void(0)">
                                    @lang('Dashboard')
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">@lang('KYC Setting')</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang('KYC Setting')</h1>
                </div>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center bg-light p-3">
                <h5 class="mb-0">@lang('Kyc verification is mandatory for all?')</h5>

                <div class="option">
                    <div class="form-check form-check-inline">
                        <input type="radio" id="formInlineRadio1" class="form-check-input" name="kycMandatory" value="1"
                            {{ old('kycMandatory', basicControl()->isKycMandatory) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="formInlineRadio1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" id="formInlineRadio2" class="form-check-input" name="kycMandatory" value="0"
                            {{ old('kycMandatory', basicControl()->isKycMandatory) == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="formInlineRadio2">No</label>
                    </div>
                </div>
            </div>
            <div class="card-body p-3">
                <p class="mb-0 text-muted">@lang('Toggle to set KYC mandatory status. This will impact the verification process for users.')</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title m-0">@lang('KYC Form')</h4>
                <div class="d-flex align-items-center justify-content-end">
                    <a href="{{ route('admin.kyc.sumsubConfigure') }}" class="btn btn-secondary me-2 btn-sm"><i class="fas fa-user-check"></i> @lang('Verify Using Sumsub')</a>
                    <a href="{{ route('admin.kyc.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> @lang('Add Form')</a>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-thead-bordered table-nowrap table-align-middle card-table">
                    <thead class="thead-light">
                    <tr>
                        <th>@lang('No.')</th>
                        <th>@lang('Form Type')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($kycList as $key => $kyc)
                        <tr>
                            <td>{{ $loop->index + 1  }}</td>
                            <td>
                                @lang($kyc->name)
                            </td>
                            <td>
                                @if($kyc->status ==  0)
                                    <span class="badge bg-soft-danger text-danger">
                                                <span class="legend-indicator bg-danger"></span>@lang('Inactive')
                                            </span>
                                @elseif($kyc->status ==  1)
                                    <span class="badge bg-soft-success text-success">
                                                <span class="legend-indicator bg-success"></span>@lang('Active')
                                                </span>
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-white btn-sm" href="{{ route('admin.kyc.edit', $kyc->id) }}">
                                    <i class="bi-pencil-square me-1"></i> @lang("Edit")
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="odd"><td valign="top" colspan="8" class="dataTables_empty"><div class="text-center p-4">
                                    <img class="mb-3 dataTables-image" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                                    <img class="mb-3 dataTables-image" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                                    <p class="mb-0">@lang("No data to show")</p>
                                </div></td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@push('css-lib')
    <link rel="stylesheet" href="{{ asset('assets/admin/vendor/flatpickr/dist/flatpickr.min.css') }}">
@endpush

@push('js-lib')
    <script src="{{ asset('assets/admin/vendor/flatpickr/dist/flatpickr.min.js') }}"></script>
@endpush

@push('script')
    <script>
        'use strict';
        (function () {
            HSCore.components.HSFlatpickr.init('.js-flatpickr')
        })();
        $('input[name="kycMandatory"]').on('change', function() {
            let isChecked = $(this).val();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.kycIsMandatory') }}",
                type: "POST",
                data: { kycMandatory: isChecked },
                dataType: 'json',
                success: function(data) {
                    if (data.success) {
                        Notiflix.Notify.success('KYC Mandatory setting updated successfully.');
                    } else {
                        Notiflix.Notify.failure('Failed to update KYC Mandatory setting.');
                    }
                },
                error: function(xhr, status, error) {
                    Notiflix.Notify.failure('An error occurred: ' + xhr.responseText);
                }
            });
        });
    </script>
@endpush



