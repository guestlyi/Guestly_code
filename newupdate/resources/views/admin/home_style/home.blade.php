@extends('admin.layouts.app')
@section('page_title', __('Home Styles'))
@section('content')
    <div class="content container-fluid" id="homeStyles">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-sm">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="javascript:void(0)">@lang('Dashboard')</a></li>
                            <li class="breadcrumb-item active"
                                aria-current="page">@lang("Home Style")</li>
                        </ol>
                    </nav>
                    <h1 class="page-header-title">@lang("Home Styles")</h1>
                </div>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            @foreach(config('themes')[$basicControl->theme]['home_version'] as $key => $homeVersion)
                <div class="col-xl-3 col-md-3 col-sm-12">
                    <div class="select-theme">
                        <label class="form-control" for="formControlRadio{{$key}}">
                            <span class="form-check">
                                <input type="radio" class="form-check-input" name="homeStyle" data-theme_name="{{$homeVersion['name']}}" value="{{$key}}"
                                       id="formControlRadio{{$key}}" @checked(basicControl()->home_style == $key)>
                                <img class="img-fluid w-100 homeStyleImage"
                                     src="{{ asset($homeVersion['preview_link']) }}"
                                     alt="Image Description">
                            </span>
                        </label>
                    </div>
                    <div class="text-center">
                        <h5 class="mb-0 bg-warning p-3">@lang($homeVersion['name'])</h5>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('.form-check-input').on('change', function() {
                const IS_DEMO = {{ config('demo.IS_DEMO') ? 'true' : 'false' }};
                if (IS_DEMO === true) {
                    Notiflix.Notify.failure('This action is disabled in demo mode.');
                    return false; // stop further execution
                }

                if ($(this).prop('checked')) {
                    let radioValue = $(this).val();
                    let title = $(this).data('name');
                    $.ajax({
                        url: '{{ route('admin.select.home.style') }}',
                        type: 'POST',
                        data: {
                            val: radioValue,
                            title: title
                        },
                        success: function (response) {
                            Notiflix.Notify.success(response.message);
                        },
                        error: function (xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            });



        });
    </script>
@endpush
