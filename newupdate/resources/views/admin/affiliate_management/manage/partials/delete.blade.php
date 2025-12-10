<form id="deleteAccountForm"
      action="{{ route('admin.affiliate.profile.delete', $affiliate->id) }}"
      method="POST"
      onsubmit="return confirmDelete();">
    @csrf
    @method('DELETE')

    <div id="deleteAccountSection" class="card">
        <div class="card-header">
            <h4 class="card-title">@lang('Delete Account')</h4>
        </div>

        <div class="card-body">
            <p class="card-text">@lang('When you delete account, you lose access...')</p>

            <div class="mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="deleteAccountCheckbox">
                    <label class="form-check-label" for="deleteAccountCheckbox">
                        @lang('Confirm that I want to delete this account.')
                    </label>
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3">
                <button type="submit" class="btn btn-danger deleteButton" disabled>@lang('Delete')</button>
            </div>
        </div>
    </div>
</form>

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const checkbox = document.getElementById('deleteAccountCheckbox');
            const deleteButton = document.querySelector('.deleteButton');

            checkbox.addEventListener('change', function () {
                deleteButton.disabled = !this.checked;
            });

            window.confirmDelete = function () {
                if (!checkbox.checked) {
                    Notiflix.Notify.failure("You must confirm deletion.");
                    return false;
                }

                Notiflix.Confirm.show(
                    'Confirm Deletion',
                    'Are you sure you want to delete this account?',
                    'Yes, Delete',
                    'Cancel',
                    function okCb() {
                        document.getElementById('deleteAccountForm').submit();
                    },
                    function cancelCb() {
                        Notiflix.Notify.info('Deletion canceled.');
                    }
                );

                return false;
            };
        });
    </script>
@endpush

