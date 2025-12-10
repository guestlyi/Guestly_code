@if(basicControl()->cookie_status == 1)
    <div id="cookieAlert" class="cookie-content" style="display: none">
        <h4>
            <i class="fad fa-cookie-bite"></i>
            @lang(basicControl()->cookie_heading)
        </h4>
        <p>
            @lang(\Illuminate\Support\Str::limit(basicControl()->cookie_description, 180))
            @lang('By continuing to use our website, you agree to our')
            <a class="cookieSeemoreButton" href="{{ url('/cookie-policy') }}">@lang(basicControl()->cookie_button)</a>
        </p>
        <div class="cookie-btns d-flex justify-content-center align-items-center gap-2">
            <a class="btn-1 text-light" href="javascript:void(0);" type="button" onclick="acceptCookiePolicy()">
                <div class="btn-wrapper">
                    <div class="main-text btn-single">
                        @lang('Accept')
                    </div>
                    <div class="hover-text btn-single">
                        @lang('Accept')
                    </div>
                </div>
            </a>
            <a class="btn-3" href="javascript:void(0);" type="button" onclick="closeCookieBanner()">
                <div class="btn-wrapper">
                    <div class="main-text btn-single">
                        @lang('Close')
                    </div>
                    <div class="hover-text btn-single">
                        @lang('Close')
                    </div>
                </div>
            </a>
        </div>
    </div>

    <script>
        function acceptCookiePolicy() {
            sessionStorage.setItem("cookie_policy_accepted", "true");
            hideCookieBanner();
        }

        function closeCookieBanner() {
            hideCookieBanner();
        }

        function hideCookieBanner() {
            const banner = document.getElementById("cookieAlert");
            if (banner) {
                banner.style.display = "none";
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const hasAccepted = sessionStorage.getItem("cookie_policy_accepted");
            const banner = document.getElementById("cookieAlert");

            if (!hasAccepted && banner) {
                banner.style.display = "block";
            }
        });
    </script>
@endif
