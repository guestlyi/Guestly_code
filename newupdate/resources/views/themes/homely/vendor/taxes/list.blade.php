@extends(template().'layouts.user')
@section('title',trans('Taxes'))
@section('content')
    <section class="listing">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('Taxes')</li>
                    </ul>
                    <h4>@lang('Taxes')</h4>
                </div>
                <a href="#" class="listing-plus-btn" data-bs-toggle="modal" data-bs-target="#createTaxModal"><i class="fa-light fa-plus"></i></a>
            </div>
            <div class="listing-container">
                <div class="shop-view-content">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="list-view-wrapper">
                            <div class="table-responsive d-flex flex-column-reverse">
                                <table class="table table-striped align-middle">
                                    <thead>
                                        <tr>
                                            <th scope="col">@lang('Title')</th>
                                            <th scope="col">@lang('Amount')</th>
                                            <th scope="col">@lang('Status')</th>
                                            <th scope="col">@lang('Action')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($taxes as $tax)
                                            <tr>
                                                <td data-label="title">
                                                    <div class="listing-image-container">
                                                        <h6>{{ $tax->title }}</h6>
                                                    </div>
                                                </td>
                                                <td data-label="Amount">{{ $tax->amount.($tax->type == 'percentage' ? '%' : basicControl()->currency_symbol) }}</td>
                                                <td data-label="Status">
                                                    @if($tax->status == 1)
                                                        <span class="badge bg-soft-success text-success">@lang('Active')</span>
                                                    @elseif($tax->status == 0)
                                                        <span class="badge bg-soft-danger text-danger">@lang('Inactive')</span>
                                                    @else
                                                        <span class="badge bg-soft-secondary text-secondary">@lang('Unknown')</span>
                                                    @endif
                                                </td>
                                                <td data-label="Edit">
                                                    <div class="dropdown">
                                                        <button class="action-btn-secondary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fa-regular fa-ellipsis-stroke-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <a class="dropdown-item edit-tax-btn" href="#"
                                                               data-id="{{ $tax->id }}"
                                                               data-title="{{ $tax->title }}"
                                                               data-amount="{{ $tax->amount }}"
                                                               data-tax-type="{{ $tax->type }}"
                                                               data-tax-status="{{ $tax->status }}"
                                                               data-update-url="{{ route('user.tax.update') }}"
                                                               data-bs-toggle="modal" data-bs-target="#editTaxModal">
                                                                @lang('Edit')
                                                            </a>
                                                            <li>
                                                                <a class="dropdown-item"
                                                                   href="#"
                                                                   data-bs-target="#deleteTaxModal"
                                                                   data-id="{{ $tax->id }}"
                                                                   data-bs-toggle="modal"
                                                                >
                                                                    @lang('Remove')
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            @include('empty')
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
    <div class="modal fade" id="createTaxModal" tabindex="-1" aria-labelledby="createTaxModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="taxForm" method="post" action="{{ route('user.tax.store') }}">
                @csrf

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createTaxModalLabel">@lang('Create Tax')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="taxTitle" class="form-label">@lang('Title')</label>
                            <input type="text" class="form-control" id="taxTitle" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="taxAmount" class="form-label">@lang('Amount')</label>
                            <input type="number" class="form-control" id="taxAmount" name="amount" step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label for="taxType" class="form-label">@lang('Tax Type')</label>
                            <select class="form-select cmn-select2" id="taxType" name="tax_type" required>
                                <option value="">@lang('Select Tax Type')</option>
                                <option value="percentage">@lang('Percentage') (%)</option>
                                <option value="fixed">@lang('Fixed Amount')</option>
                            </select>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-primary">@lang('Create')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="editTaxModal" tabindex="-1" aria-labelledby="editTaxModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="editTaxForm" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="tax_id" id="tax_id" value="" />
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTaxModalLabel">@lang('Edit Tax')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="editTaxTitle" class="form-label">@lang('Title')</label>
                            <input type="text" class="form-control" id="editTaxTitle" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="editTaxAmount" class="form-label">@lang('Amount')</label>
                            <input type="number" class="form-control" id="editTaxAmount" name="amount" step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label for="editTaxType" class="form-label">@lang('Tax Type')</label>
                            <select class="form-select cmn-select2" id="editTaxType" name="tax_type" required>
                                <option value="">@lang('Select Tax Type')</option>
                                <option value="percentage">@lang('Percentage') (%)</option>
                                <option value="fixed">@lang('Fixed Amount')</option>
                            </select>
                        </div>
                        <input type="hidden" name="is_enabled" value="0">
                        <div class="form-check form-switch mt-3">
                            <input class="form-check-input" type="checkbox" id="editTaxToggle" name="is_enabled" value="1">
                            <label class="form-check-label" for="editTaxToggle">@lang('Enable this tax')</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-primary">@lang('Update')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="deleteTaxModal" tabindex="-1" aria-labelledby="deleteTaxModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteTaxForm" method="POST" action="{{ route('user.tax.delete') }}">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteTaxModalLabel">@lang('Confirm Deletion')</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('Close')"></button>
                    </div>
                    <div class="modal-body">
                        @lang('Are you sure you want to remove this tax?')
                        <input type="hidden" name="tax_id" id="deleteTaxId" value="">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                        <button type="submit" class="btn btn-danger">@lang('Delete')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .select2-container .select2-selection--single {
            height: 43px;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #444;
            line-height: 41px;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
        }
        .personal-info-title {
             margin-bottom: 0 !important;
        }
    </style>
@endpush
@push('script')
    <script>
        document.querySelectorAll('.dropdown-item[data-bs-target="#deleteTaxModal"]').forEach(item => {
            item.addEventListener('click', function () {
                const taxId = this.getAttribute('data-id');
                document.getElementById('deleteTaxId').value = taxId;
            });
        });
        $(document).ready(function() {
            if ($.fn.select2 && $('#editTaxType').hasClass("select2-hidden-accessible")) {
                $('#editTaxType').select2('destroy');
            }

            $('#editTaxType').select2({
                placeholder: "Select Tax Type",
                width: '100%'
            });

            $('.edit-tax-btn').on('click', function () {
                const tax_id = $(this).data('id');
                const title = $(this).data('title');
                const amount = $(this).data('amount');
                const taxType = $(this).data('tax-type');
                const taxStatus = $(this).data('tax-status');
                const updateUrl = $(this).data('update-url');

                $('#tax_id').val(tax_id);
                $('#editTaxTitle').val(title);
                $('#editTaxAmount').val(amount);

                $('#editTaxType').val(taxType).trigger('change');

                $('#editTaxForm').attr('action', updateUrl);
                $('#editTaxToggle').prop('checked', taxStatus == 1);
            });

        });
    </script>
@endpush
