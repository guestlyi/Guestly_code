<div id="ai-feature" class="card">
    <div class="card-header">
        <h2 class="card-title h4">@lang('AI Feature')</h2>
    </div>
    <div class="card-body">
        <label class="row form-check form-switch mb-4" for="aiSwitch">
            <span class="col-8 col-sm-9 ms-0">
              <span class="d-block text-dark">@lang('AI Feature For User')</span>
              <span class="d-block fs-5">@lang('If You Disable Ai Feature for this user, User cannot use artificial intelligence for generate image or text ')</span>
            </span>
            <span class="col-4 col-sm-3 text-end">
              <input type="hidden" name="ai_feature" value="0">
              <input type="checkbox" class="form-check-input" name="ai_feature" id="aiSwitch" value="1" {{ $user->ai_feature == 1 ? 'checked' : '' }}>
            </span>
        </label>
    </div>
</div>

@push('script')
    <script>
        $(document).ready(function () {
            $('#aiSwitch').on('change', function () {
                let isChecked = $(this).is(':checked') ? 1 : 0;
                let userId = '{{ $user->id }}';

                $.ajax({
                    url: '{{ route('admin.user.set.ai.feature') }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        payment_collection: isChecked,
                        user_id: userId
                    },
                    success: function (response) {
                        Notiflix.Notify.success(response.message);
                    },
                    error: function (xhr) {
                        Notiflix.Notify.failure('Failed to update artificial intelligence. Please try again.');
                    }
                });
            });
        });
    </script>
@endpush
