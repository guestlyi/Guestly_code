@extends(template().'layouts.user')
@section('title',__('KYC Verification'))
@section('content')
    <section class="listing">
        <div class="container">
            <div class="personal-info-title listing-top">
                <div class="text-area">
                    <ul>
                        <li><a href="{{ route('user.profile') }}">@lang('Account')</a></li>
                        <li><i class="fa-light fa-chevron-right"></i></li>
                        <li>@lang('KYC Verification')</li>
                    </ul>
                    <h4>@lang('KYC Verification')</h4>
                </div>
            </div>
        </div>
    </section>

    <div id="sumsub-websdk-container"></div>
@endsection
@push('style')
    <style>
        .kyc-wrapper {
            display: flex;
            justify-content: center;
            padding: 40px 0;
            background: #f9f9f9;
        }
        #sumsub-websdk-container{
            margin-bottom: 100px;
        }

        #sumsub-websdk-container iframe {
            width: 80% !important;
            border-radius: 12px !important;
            margin: auto;
            display: block;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        section.listing {
            padding: 0 !important;
        }
    </style>
@endpush
@push('script')
    <script src="https://static.sumsub.com/idensic/static/sns-websdk-builder.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", async function () {
            try {
                const newAccessToken = await getNewAccessToken();
                launchWebSdk(newAccessToken);
            } catch (error) {
                console.error("Error loading Sumsub token:", error);
            }
        });

        function getNewAccessToken() {
            return axios.get("{{ route('user.get.sumsub.token') }}")
                .then(response => response.data)
                .catch(error => {
                    console.error("Failed to get new Sumsub token:", error);
                    throw error;
                });
        }

        function launchWebSdk(accessToken) {
            let snsWebSdkInstance = snsWebSdk
                .init(accessToken, () => getNewAccessToken())
                .withConf({ lang: "en", theme: "light" })
                .withOptions({ addViewportTag: false, adaptIframeHeight: true })
                .on("idCheck.onStepCompleted", (payload) => {
                })
                .on("idCheck.onError", (error) => {
                    console.error("Error:", error);
                })
                .build();

            snsWebSdkInstance.launch("#sumsub-websdk-container");

            setTimeout(() => {
                const iframe = document.querySelector('#sumsub-websdk-container iframe');
                if (iframe) {
                    iframe.style.width = '78%';
                    iframe.style.borderRadius = '10px';
                    iframe.style.margin = 'auto';
                    iframe.style.display = 'block';
                }
            }, 1000);
        }
    </script>
@endpush

