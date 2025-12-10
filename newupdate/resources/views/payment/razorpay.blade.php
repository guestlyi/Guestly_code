@extends($extends)
@section('page_title')
	{{ __('Pay with ').__(optional($deposit->gateway)->name) }}
@endsection
@section('content')

    <div class="container paymentContainer">
        <div class="payment-process-container">
            <div class="payment-process-container-inner">
                <div class="payment-process-image">
                    <img src="{{getFile(optional($deposit->gateway)->driver,optional($deposit->gateway)->image)}}"
                         class="card-img-top gateway-img">
                </div>
                <div class="payment-process-content">
                    <h5 class="my-3">@lang('Please Pay') {{getAmount($deposit->payable_amount)}} {{$deposit->payment_method_currency}}</h5>
                    <form action="{{$data->url}}" method="{{$data->method}}">
                        <script src="{{$data->checkout_js}}"
                                @foreach($data->val as $key=>$value)
                                    data-{{$key}}="{{$value}}"
                            @endforeach >
                        </script>
                        <input type="hidden" custom="{{$data->custom}}" name="hidden">
                    </form>
                </div>
            </div>
        </div>
    </div>



@endsection
@push('script')
	<script>
		$(document).ready(function () {
			$('input[type="submit"]').addClass("btn-sm btn-primary border-0");
		})
	</script>
@endpush
