<div id="affiliate_able" class="card">
    <div class="card-header">
        <h2 class="card-title h4">@lang('Affiliate')</h2>
    </div>
    <div class="card-body">
        <label class="row form-check form-switch mb-4" for="affiliateSwitch">
            <span class="col-8 col-sm-9 ms-0">
              <span class="d-block text-dark">@lang('Affiliate Feature For User')</span>
              <span class="d-block fs-5">@lang('If you disable affiliate feature for this user, User cannot affiliate more income. ')</span>
            </span>
            <span class="col-4 col-sm-3 text-end">
              <input type="hidden" name="is_affiliatable" value="0">
              <input type="checkbox" class="form-check-input" name="is_affiliatable" id="affiliateSwitch" value="1" {{ $user->is_affiliatable == 1 ? 'checked' : '' }}>
            </span>
        </label>
    </div>
</div>

@push('script')
    <script>
        $(document).ready(function () {
            $('#affiliateSwitch').on('change', function () {
                let isChecked = $(this).is(':checked') ? 1 : 0;
                let userId = '{{ $user->id }}';

                $.ajax({
                    url: '{{ route('admin.user.set.affiliate') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_affiliatable: isChecked,
                        user_id: userId
                    },
                    success: function (response) {
                        Notiflix.Notify.success(response.message);
                    },
                    error: function (xhr) {
                        Notiflix.Notify.failure('Failed to update affiliate control. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush
