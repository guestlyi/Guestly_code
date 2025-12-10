@if(basicControl()->in_app_notification)
    <li class="nav-item d-sm-inline-block" id="pushNotificationArea">
        <div class="header-notifications">
            <div class="dropdown">
                <button class="notif cmn-dropdown-toggle {{ $class2 }}" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-light fa-bell"></i>
                    <span class="notification-counter {{ $class }}" v-if="items.length > 0" v-cloak>
                        @{{ items.length }}
                    </span>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <div class="notification-container">
                        <div class="notification-header d-flex justify-content-between align-items-center">
                            <h4>@lang('Notifications')</h4>
                            <a href="javascript:void(0)" v-if="items.length > 0" @click.prevent="readAll" class="text-sm text-danger">
                                @lang("Clear All")
                            </a>
                        </div>

                        <div class="notification-list">
                            <ul class="notification-list-inner" v-if="items.length > 0">
                                <li v-for="(item, index) in items" :key="index" v-if="item.description">
                                    <a href="javascript:void(0)" @click.prevent="readAt(item.id, item.description.link)">
                                        <div class="notification-icon">
                                            <i class="fa-light fa-bell"></i>
                                        </div>
                                        <div class="notification-details">
                                            <h6>@{{ item.description.name }} @{{ item.description.text }}</h6>
                                            <span>@{{ item.formatted_date }}</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>

                            <div class="text-center p-4" v-else>
                                <img class="dataTables-image mb-3"
                                     src="{{ asset('assets/admin/img/oc-error.svg') }}"
                                     alt="No Notification">
                                <p class="mb-0">@lang("No Notifications Found")</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </li>
@endif


