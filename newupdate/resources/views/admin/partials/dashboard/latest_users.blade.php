<div class="row">
    <div class="col-md-6">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header card-header-content-between">
                <h4 class="card-header-title">@lang("Latest Users")</h4>

                <a class="btn btn-white btn-sm" href="{{ route("admin.users") }}">@lang("View All")</a>
            </div>
            <div class="table-responsive" style="height: 23.5rem;">
                <table class="table table-borderless table-thead-bordered table-align-middle card-table" >
                    <thead class="thead-light">
                    <tr>
                        <th>@lang('Full Name')</th>
                        <th>@lang('Country')</th>
                        <th>@lang('Status')</th>
                        <th>@lang('Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($latestUser as $user)
                        <tr>
                            <td>
                                <a class="d-flex align-items-center me-2"
                                   href="{{ route("admin.user.view.profile", $user->id) }}">
                                    <div class="flex-shrink-0">
                                        {!! $user->profilePicture() !!}
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h5 class="text-hover-primary mb-0">{{ $user->firstname.' '.$user->firstname }}</h5>
                                        <span class="fs-6 text-body">{{'@'. $user->username }}</span>
                                    </div>
                                </a>
                            </td>
                            <td>
                                {{ $user->country ?? 'N/A' }}
                            </td>
                            <td>
                                @if($user->status == 1)
                                    <span class="badge bg-soft-success text-success">
                                <span class="legend-indicator bg-success"></span>@lang("Active")
                            </span>
                                @else
                                    <span class="badge bg-soft-danger text-danger">
                                <span class="legend-indicator bg-danger"></span>@lang("Inactive")
                            </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a class="btn btn-white btn-sm" href="{{ route('admin.user.edit', $user->id) }}">
                                        <i class="bi-pencil-square me-1"></i> @lang("Edit")
                                    </a>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-white btn-icon btn-sm dropdown-toggle dropdown-toggle-empty" id="userEditDropdown" data-bs-toggle="dropdown" aria-expanded="false"></button>
                                        <div class="dropdown-menu dropdown-menu-end mt-1" aria-labelledby="userEditDropdown" >
                                            <a class="dropdown-item" href="{{ route('admin.user.view.profile', $user->id) }}">
                                                <i class="bi-eye-fill dropdown-item-icon"></i> @lang("View Profile")
                                            </a>
                                            <a class="dropdown-item" href="{{ route('admin.send.email', $user->id) }}"> <i
                                                    class="bi-envelope dropdown-item-icon"></i> @lang("Send Mail") </a>
                                            <a class="dropdown-item loginAccount" href="javascript:void(0)"
                                               data-route="{{ route('admin.login.as.user', $user->id) }}"
                                               data-bs-toggle="modal" data-bs-target="#loginAsUserModal">
                                                <i class="bi bi-box-arrow-in-right dropdown-item-icon"></i>
                                                @lang("Login As User")
                                            </a>
                                            <a class="dropdown-item addBalance" href="javascript:void(0)"
                                               data-route="{{ route('admin.user.update.balance', $user->id) }}"
                                               data-balance="{{ currencyPosition($user->balance) }}"
                                               data-bs-toggle="modal" data-bs-target="#addBalanceModal">
                                                <i class="bi bi-cash-coin dropdown-item-icon"></i>
                                                @lang("Manage Balance")
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <div class="text-center p-4">
                                <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error.svg') }}" alt="Image Description" data-hs-theme-appearance="default">
                                <img class="dataTables-image mb-3" src="{{ asset('assets/admin/img/oc-error-light.svg') }}" alt="Image Description" data-hs-theme-appearance="dark">
                                <p class="mb-0">@lang("No data to show")</p>
                            </div>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card mb-3 mb-lg-5">
            <div class="card-header card-header-content-between">
                <h4 class="card-header-title">@lang("Users Overview")</h4>
            </div>
            <div class="card-body">
                <div style="height: 22rem;" class="rounded-bottom">
                    <div id="userOverviewMap" class="jsvectormap-custom"
                         data-hs-js-vector-map-options='{
                            "focusOn": {"coords": [25,12], "scale":1.5, "animate":true},
                            "regionStyle": {"initial":{"fill":"rgba(55,125,255,.3)"},"hover":{"fill":"#377dff"}},
                            "backgroundColor":"#132144",
                            "markerStyle": {
                                "initial": {
                                    "stroke-width": 2,
                                    "fill": "#6e71ff",
                                    "stroke": "rgba(255,255,255,.5)",
                                    "r": 6
                                },
                                "hover": {
                                    "fill": "#e23e3e",
                                    "stroke": "#fff"
                                }
                            }
                         }'>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('script')
    <script>
        fetch("{{ route('admin.user.overview') }}")
            .then(response => response.json())
            .then(data => {

                const markers = data.map(user => ({
                    coords: user.coords,
                    name: user.name,
                    active: user.active,
                    new: user.new,
                    flag: user.flag,
                    code: user.code,
                    color: user.new ? '#6e71ff' : '#e23e3e'
                }));

                const tooltipTemplate = (marker) => `
                    <span class="d-flex align-items-center mb-2">
                        <img class="avatar avatar-xss avatar-circle" src="${marker.flag}" alt="Flag">
                        <span class="h5 ms-2 mb-0">${marker.name}</span>
                    </span>
                    <div class="d-flex justify-content-between" style="max-width: 10rem;">
                        <strong>{{ trans('Total User') }}:</strong>
                        <span class="ms-2">${marker.active}</span>
                    </div>
                `;

                HSCore.components.HSJsVectorMap.init('#userOverviewMap', {
                    markers,
                    zoomOnScroll: true,
                    zoomButtons: true,
                    minZoom: 0.5,
                    maxZoom: 5,
                    focusOn: { coords: [25, 12], scale: 1.5, animate: true },

                    regionStyle: {
                        initial: { fill: 'rgba(55,125,255,.3)' },
                        hover: { fill: '#377dff' }
                    },

                    markerStyle: {
                        initial: {
                            "stroke-width": 2,
                            "fill": "#6e71ff",
                            "stroke": "rgba(255,255,255,.5)",
                            "r": 6
                        },
                        hover: {
                            "fill": "#e23e3e",
                            "stroke": "#fff"
                        },
                        selected: {
                            "fill": "#e23e3e",
                            "stroke": "#fff"
                        }
                    },

                    onMarkerReady(map, index) {
                        const marker = markers[index];
                        const el = map.markers[index].element;
                        el.setStyle('fill', marker.color);
                        el.setStyle('stroke', marker.color);
                    },

                    onMarkerTooltipShow(map, tooltip, code) {
                        tooltip._tooltip.style.display = null;
                        tooltip._tooltip.innerHTML = tooltipTemplate(markers[code]);
                    },

                    backgroundColor: HSThemeAppearance.getAppearance() === 'dark'
                        ? '#25282a'
                        : '#132144'
                });

            })
            .catch(error => console.error('User Overview Map Error:', error));
    </script>
@endpush
