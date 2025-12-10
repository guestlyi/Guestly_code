<div id="firebase-app">
    <div class="p-3 mb-5 alert alert-soft-dark mb-4 mb-lg-7" role="alert"
         v-if="notificationPermission == 'default' && !is_notification_skipped" v-cloak>
        <div class="alert-box d-flex flex-wrap align-items-center">
            <div class="flex-shrink-0">
                <img class="avatar avatar-xl"
                     src="{{ asset('assets/admin/img/oc-megaphone.svg') }}"
                     alt="Image Description" data-hs-theme-appearance="default">
                <img class="avatar avatar-xl"
                     src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                     alt="Image Description" data-hs-theme-appearance="dark">
            </div>

            <div class="flex-grow-1 ms-3">
                <h3 class="mb-1">@lang("Attention!")</h3>
                <div class="d-flex align-items-center">
                    <p class="mb-0 text-body"> @lang('Please allow your browser to get instant push notification. Allow it from
                                notification setting.')</p>
                    <button id="allow-notification" class="btn btn-sm btn-primary mx-2"><i
                            class="fa fa-check-circle"></i> @lang('Allow me')</button>
                </div>
            </div>
            <button type="button" class="btn-close"
                    @click.prevent="skipNotification" data-bs-dismiss="alert"
                    aria-label="Close">
            </button>
        </div>
    </div>
    <div class="alert alert-soft-dark mb-4 mb-lg-7" role="alert"
         v-if="notificationPermission == 'denied' && !is_notification_skipped" v-cloak>
        <div class="d-flex align-items-center mt-4">
            <div class="flex-shrink-0">
                <img class="avatar avatar-xl"
                     src="{{ asset('assets/admin/img/oc-megaphone.svg') }}"
                     alt="Image Description" data-hs-theme-appearance="default">
                <img class="avatar avatar-xl"
                     src="{{ asset('assets/admin/img/oc-megaphone-light.svg') }}"
                     alt="Image Description" data-hs-theme-appearance="dark">
            </div>

            <div class="flex-grow-1 ms-3">
                <h3 class=" mb-1">@lang("Attention!")</h3>
                <div class="d-flex align-items-center">
                    <p class="mb-0 text-body"> @lang("Please allow your browser to get instant push notification. Allow it from
                                notification setting.")</p>
                </div>
            </div>
            <button type="button" class="btn-close" @click.prevent="skipNotification" data-bs-dismiss="alert"
                    aria-label="Close"></button>
        </div>
    </div>
</div>
