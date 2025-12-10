<section class="error">
    <div class="container">
        <div class="error-container text-center">
            <div class="error-image">
                <img src="{{ asset(template(true).'img/resource/error.gif') }}" alt="error">
            </div>
            <div class="error-content">
                <h3 class="errorHeader">
                    @lang('No Data Found!')
                </h3>
            </div>
        </div>
    </div>
</section>

@push('style')
    <style>
        .error .error-container{
            padding: 20px 0 0 0;
        }

        .error .error-container .error-image img{
            max-width: 200px;
            padding: 15px;
        }
        .errorHeader{
            font-size: 24px;
            font-weight: 600;
            line-height: 56px;
            margin-bottom: 16px;
        }
    </style>
@endpush
