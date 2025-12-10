@extends(template().'layouts.user')
@section('title',trans('Login Security'))
@section('content')
    @php
        $contents = getProfileContent();
    @endphp
    <section class="personal-info login-security">
        <div class="container">
            <div class="personal-info-title">
                <ul>
                    <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                    <li><i class="fa-light fa-chevron-right"></i></li>
                    <li>@lang('Login & security')</li>
                </ul>
                <h4>@lang('Login & security')</h4>
            </div>
            <div class="row">
                <div class="col-lg-10">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">

                        </div>
                    </nav>
                </div>
            </div>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="personal-info-left">
                                <div class="personal-info-list">
                                    <ul>
                                        <h3>@lang('Security')</h3>
                                        <li>
                                            <div class="personal-info-list-content">
                                                <h6>@lang('Password')</h6>
                                                <p>
                                                    @lang('Last updated : ')
                                                    @if(auth()->user()->password_updated)
                                                        {{ \Carbon\Carbon::parse(auth()->user()->password_updated)->diffForHumans() }}
                                                    @else
                                                        @lang('never updated')
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="personal-info-list-link">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#updatePasswordModal">@lang('Update')</a>
                                            </div>
                                        </li>

                                        <h3 class="mt_40">@lang('Latest logins')</h3>

                                        @foreach ($loginHistory as $item)
                                            <li>
                                                <div class="personal-info-list-content">
                                                    <h6>{{ $item->browser }}</h6>
                                                    <p>{{ $item->os }}</p>
                                                    <p>{{ $item->get_device }}</p>
                                                </div>
                                                <div class="personal-info-list-link">
                                                    <p>{{ dateTime($item->created_at) }}</p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 offset-lg-1">
                            <div class="personal-info-right">
                                <div class="personal-info-sidebar">
                                    <div class="personal-info-sidebar-content">
                                        <div class="icon">
                                            <img src="{{ getFile($contents['single']['content']->media->image->driver, $contents['single']['content']->media->image->path) }}" />
                                        </div>
                                        <h5>{{ $contents['single']->description->login_security_text ?? '' }}</h5>
                                        <p>{{ $contents['single']->description->login_security_description ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(auth()->user()->role == 1)
                            <div class="col-lg-10">
                                <div class="personal-info-list mt_40">
                                    <ul>
                                        <li>
                                            <div class="personal-info-list-content">
                                                <h6>@lang('Account')</h6>
                                                <p>@lang('Deactivate your account')</p>
                                            </div>
                                            <div class="personal-info-list-link">
                                                <a href="#0" data-bs-target="#statusModal" data-bs-toggle="modal">{{ auth()->user()->status == 2 ? 'Activate' : 'Deactivate' }}</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="updatePasswordModal" tabindex="-1" aria-labelledby="updatePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="updatePasswordModalLabel">@lang('Update Password')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="passwordUpdateForm" method="post" action="{{ route('user.updatePassword') }}">
                    @csrf

                    <div class="modal-body">
                        <div class="mb-3 position-relative">
                            <label class="form-label">@lang('Current Password')</label>
                            <input type="password" class="form-control" name="current_password" id="currentPassword">
                            <i class="toggle-password fa-regular fa-eye" data-target="currentPassword"></i>
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 position-relative">
                            <label class="form-label">@lang('New Password')</label>
                            <input type="password" class="form-control" name="password" id="newPassword">
                            <i class="toggle-password fa-regular fa-eye" data-target="newPassword"></i>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 position-relative">
                            <label class="form-label">@lang('Retype New Password')</label>
                            <input type="password" class="form-control" name="password_confirmation" id="confirmPassword" required>
                            <i class="toggle-password fa-regular fa-eye" data-target="confirmPassword"></i>
                            <div id="passwordMatchMessage" class="form-text mt-1"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">@lang('Save Changes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @if(auth()->user()->role == 1)
        <div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('user.account.toggleStatus') }}">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="statusModalLabel">{{ auth()->user()->status == 0 ? 'Activate Account' : 'Deactivate Account' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @lang('Are you sure you want to '){{ auth()->user()->status == 2 ? 'activate' : 'deactivate' }}@lang(' your account?')
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('Cancel')</button>
                            <button type="submit" class="btn btn-primary" id="statusSubmitBtn">
                                <span class="spinner-border spinner-border-sm me-1 d-none" role="status" aria-hidden="true" id="statusSpinner"></span>
                                @lang('Yes, Proceed')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
@push('script')
    <script>
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', () => {
                const input = document.getElementById(icon.getAttribute('data-target'));
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
        document.addEventListener("DOMContentLoaded", function() {
            const newPassword = document.getElementById("newPassword");
            const confirmPassword = document.getElementById("confirmPassword");
            const matchMessage = document.getElementById("passwordMatchMessage");

            function checkPasswordMatch() {
                if (confirmPassword.value === "") {
                    matchMessage.textContent = "";
                    return;
                }

                if (newPassword.value === confirmPassword.value) {
                    matchMessage.textContent = "Passwords match ✅";
                    matchMessage.classList.remove("text-danger");
                    matchMessage.classList.add("text-success");
                } else {
                    matchMessage.textContent = "Passwords do not match ❌";
                    matchMessage.classList.remove("text-success");
                    matchMessage.classList.add("text-danger");
                }
            }

            newPassword.addEventListener("input", checkPasswordMatch);
            confirmPassword.addEventListener("input", checkPasswordMatch);
        });

        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('#statusModal form');
            const spinner = document.getElementById('statusSpinner');
            const button = document.getElementById('statusSubmitBtn');

            form.addEventListener('submit', function () {
                spinner.classList.remove('d-none');
                button.setAttribute('disabled', true);
            });
        });
    </script>
@endpush
