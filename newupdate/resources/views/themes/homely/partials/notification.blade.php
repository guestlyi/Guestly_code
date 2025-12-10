@if(basicControl()->in_app_notification)
    <div id="pushNotificationArea" class="notification-panel">
        <!-- Toggle Button -->
        <button class="dropdown-toggle btn-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="btn-wrapper">
                <div class="main-text btn-single">
                    <i class="fa-light fa-bell"></i>
                </div>
                <div class="hover-text btn-single">
                    <i class="fa-light fa-bell"></i>
                </div>
            </div>
            <span class="count" v-if="items.length > 0" v-cloak>@{{ items.length }}</span>
        </button>

        <!-- Dropdown -->
        <ul class="notification-dropdown">
            <div class="dropdown-box" v-if="items.length > 0">
                <li v-for="(item, index) in items" :key="index">
                    <a class="dropdown-item" href="javascript:void(0)" @click.prevent="readAt(item.id, item.description.link)">
                        <i class="fal fa-bell" aria-hidden="true"></i>
                        <div class="text">
                            <p>@{{ item.description.name }} @{{ item.description.text }}</p>
                            <span class="time">@{{ item.formatted_date }}</span>
                        </div>
                    </a>
                </li>
            </div>

            <div class="notification-empty text-center p-4" v-else>
                <img class="no-notification-img mb-3"
                     src="{{ asset('assets/admin/img/oc-error.svg') }}"
                     alt="No Notification" />
                <h5 class="mb-1 text-secondary">@lang("No Notifications Yet!")</h5>
                <p class="small text-muted mb-0">@lang("You will see new notifications here once you receive them.")</p>
            </div>

            <div class="clear-all fixed-bottom" v-if="items.length > 0">
                <a href="javascript:void(0)" @click.prevent="readAll">@lang("Clear all")</a>
            </div>
        </ul>
    </div>
@endif


