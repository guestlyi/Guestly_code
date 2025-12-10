<div id="emailSection" class="card">
    <div class="card-header">
        <h4 class="card-title">@lang('Email')</h4>
    </div>

    <div class="card-body">
        <p>@lang('Your current email address is ') <span class="fw-semibold">{{ $affiliate->email }}</span></p>

        <form action="{{ route('admin.affiliate.profile.email.update', $affiliate->id) }}" method="POST">
            @csrf

            <div class="row mb-4">
                <label for="newEmailLabel" class="col-sm-3 col-form-label form-label">@lang('New email address')</label>

                <div class="col-sm-9">
                    <input type="email" class="form-control" name="email" id="newEmailLabel" placeholder="Enter new email address" aria-label="Enter new email address" value="{{ old('email', $affiliate->email) }}">
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-primary">@lang('Save changes')</button>
            </div>
        </form>
    </div>
</div>
