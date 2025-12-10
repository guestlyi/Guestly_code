@extends(template().'layouts.user')
@section('title',trans('Dashboard'))
@section('content')
    <section class="account">
        <div class="container">
            <div class="common-title d-flex justify-content-between align-items-center">
                <div class="leftPart">
                    <h3>@lang('Account')</h3>
                    <p>
                        <a href="{{ route('user.profile.details') }}" title="@lang('Go to profile')">
                            <i class="far fa-user-circle"></i>
                        </a>
                        <b>{{ auth()->user()->username }}</b>{{ auth()->user()->gmail ? ', ' . auth()->user()->gmail : '' }}
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-memo-circle-check"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Personal info')</h5>
                            <p>@lang('Tell Us About Yourself — We’d Love to Hear More About You and How We Can Stay Connected!')</p>
                            <a href="{{ route('user.personalInfo') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-shield"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Login & security')</h5>
                            <p>@lang('Update your password regularly to keep your account safe and protect your personal information.')</p>
                            <a href="{{ route('user.loginSecurity') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-unlock"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Bookings Information')</h5>
                            <p>@lang('Check, update, and manage all your booking information easily from one convenient location.')</p>
                            <a href="{{ route('user.reservations') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-memo-circle-check"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Payment History')</h5>
                            <p>@lang('View the full payment history of all guest bookings on your property for easy transaction tracking.')</p>
                            <a href="{{ route('user.payment.history') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-file-invoice"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Transactions')</h5>
                            <p>@lang('See detailed transaction information for all your bookings, including dates, amounts, and status updates.')</p>
                            <a href="{{ route('user.transaction') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="account-single">
                        <div class="account-icon">
                            <i class="fa-light fa-bell"></i>
                        </div>
                        <div class="account-content">
                            <h5>@lang('Notifications')</h5>
                            <p>@lang('Select your notification preferences and choose the best ways for us to contact you anytime, anywhere.')</p>
                            <a href="{{ route('user.notification.permission.list') }}" class="btn-1">@lang('View Details')</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@if($firebaseNotify)
    @push('script')
        <script type="module">

            import {initializeApp} from "https://www.gstatic.com/firebasejs/9.17.1/firebase-app.js";
            import {
                getMessaging,
                getToken,
                onMessage
            } from "https://www.gstatic.com/firebasejs/9.17.1/firebase-messaging.js";

            const firebaseConfig = {
                apiKey: "{{$firebaseNotify['apiKey']}}",
                authDomain: "{{$firebaseNotify['authDomain']}}",
                projectId: "{{$firebaseNotify['projectId']}}",
                storageBucket: "{{$firebaseNotify['storageBucket']}}",
                messagingSenderId: "{{$firebaseNotify['messagingSenderId']}}",
                appId: "{{$firebaseNotify['appId']}}",
                measurementId: "{{$firebaseNotify['measurementId']}}"
            };

            const app = initializeApp(firebaseConfig);
            const messaging = getMessaging(app);
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('{{ getProjectDirectory() }}' + `/firebase-messaging-sw.js`, {scope: './'}).then(function (registration) {
                        requestPermissionAndGenerateToken(registration);
                    }
                ).catch(function (error) {
                });
            } else {
            }

            onMessage(messaging, (payload) => {
                if (payload.data.foreground || parseInt(payload.data.foreground) == 1) {
                    const title = payload.notification.title;
                    const options = {
                        body: payload.notification.body,
                        icon: payload.notification.icon,
                    };
                    new Notification(title, options);
                }
            });

            function requestPermissionAndGenerateToken(registration) {
                document.addEventListener("click", function (event) {
                    if (event.target.id == 'allow-notification') {
                        Notification.requestPermission().then((permission) => {
                            if (permission === 'granted') {
                                getToken(messaging, {
                                    serviceWorkerRegistration: registration,
                                    vapidKey: "{{$firebaseNotify['vapidKey']}}"
                                })
                                    .then((token) => {
                                        $.ajax({
                                            url: "{{ route('user.save.token') }}",
                                            method: "post",
                                            data: {
                                                token: token,
                                            },
                                            success: function (res) {
                                            }
                                        });
                                        window.newApp.notificationPermission = 'granted';
                                    });
                            } else {
                                window.newApp.notificationPermission = 'denied';
                            }
                        });
                    }
                });
            }
        </script>
        <script>
            window.newApp = new Vue({
                el: "#firebase-app",
                data: {
                    user_foreground: '',
                    user_background: '',
                    notificationPermission: Notification.permission,
                    is_notification_skipped: sessionStorage.getItem('is_notification_skipped') == '1'
                },
                mounted() {
                    sessionStorage.clear();
                    this.user_foreground = "{{$firebaseNotify['user_foreground']}}";
                    this.user_background = "{{$firebaseNotify['user_background']}}";
                },
                methods: {
                    skipNotification() {
                        sessionStorage.setItem('is_notification_skipped', '1')
                        this.is_notification_skipped = true;
                    }
                }
            });
        </script>
    @endpush
@endif

