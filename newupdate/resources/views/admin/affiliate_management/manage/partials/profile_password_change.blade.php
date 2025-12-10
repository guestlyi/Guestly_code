<div id="passwordSection" class="card">
    <div class="card-header">
        <h4 class="card-title">@lang('Change your password')</h4>
    </div>

    <div class="card-body">
        <form id="changePasswordForm" action="{{ route('admin.affiliate.profile.password.update', $affiliate->id) }}" method="POST">
            @csrf

            <div class="row mb-4">
                <label for="newPassword" class="col-sm-3 col-form-label form-label">@lang('New password')</label>
                <div class="col-sm-9">
                    <div class="input-group">
                        <input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="Enter new password">
                        <button type="button" class="btn btn-outline-secondary toggle-password" data-target="newPassword">
                            <i class="fa fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <label for="confirmNewPasswordLabel" class="col-sm-3 col-form-label form-label">@lang('Confirm new password')</label>

                <div class="col-sm-9">
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="password" class="form-control" name="confirmNewPassword" id="confirmNewPasswordLabel" placeholder="Confirm your new password" aria-label="Confirm your new password" value="{{ old('confirmNewPassword') }}">
                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="confirmNewPasswordLabel">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    @if(basicControl()->strong_password)
                        <h5>@lang('Password requirements'):</h5>

                        <p class="fs-6 mb-2">@lang('Ensure that these requirements are met'):</p>

                        <ul class="fs-6">
                            <li>@lang('Minimum 8 characters long - the more, the better')</li>
                            <li>@lang('At least one lowercase character')</li>
                            <li>@lang('At least one uppercase character')</li>
                            <li>@lang('At least one number, symbol, or whitespace character')</li>
                        </ul>
                    @endif
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">@lang('Save Changes')</button>
            </div>
        </form>
    </div>
</div>
@push('script')
    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {
            button.addEventListener('click', function () {
                const targetId = this.getAttribute('data-target');
                const input = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    </script>
@endpush
