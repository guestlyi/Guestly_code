<?php

namespace App\Services\Gateway\square;

use App\Models\Deposit;
use Facades\App\Services\BasicService;
use Illuminate\Support\Facades\Http;

class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        if ($gateway->environment == 'test') {
            $url = "https://connect.squareupsandbox.com/v2/online-checkout/payment-links";
        } else {
            $url = "https://connect.squareup.com/v2/online-checkout/payment-links";
        }

        $payload = [
            "idempotency_key" => $deposit->trx_id,
            "quick_pay" => [
                "name" => "Payment",
                "price_money" => [
                    "amount" => (int)$deposit->payable_amount,
                    "currency" => $deposit->payment_method_currency
                ],
                "location_id" => $gateway->parameters->location_id
            ],
            "checkout_options" => [
                "redirect_url" => route('success')
            ],
        ];

        $response = Http::withHeaders([
            'Square-Version' => '2025-09-24',
            'Authorization' => 'Bearer ' . $gateway->parameters->access_token,
            'Content-Type' => 'application/json',
        ])->post($url, $payload);


        $res = json_decode($response);

        if (isset($res) && isset($res->payment_link) && isset($res->payment_link->url)) {
            $deposit->note = $res->payment_link->order_id;
            $deposit->save();

            $send['redirect'] = true;
            $send['redirect_url'] = $res->payment_link->url;
        } else {
            $send['error'] = true;
            $send['message'] = 'Payment not initiate. contact with provider';
        }

        return json_encode($send);
    }

    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        $orderId = $request->data->id ?? null;
        $eventType = $request->type;
        $orderState = $request->data->object->order_updated->state;
        if ($orderId && $eventType == 'order.updated' && $orderState == 'OPEN') {
            $deposit = Deposit::where('status', 0)->where('note', $orderId)->latest()->first();
            if ($deposit) {
                BasicService::preparePaymentUpgradation($deposit);

                $data['status'] = 'success';
                $data['msg'] = 'Transaction was successful.';
                $data['redirect'] = route('success');
                return $data;
            }
        }

        $data['status'] = 'error';
        $data['msg'] = 'unable to Process.';
        $data['redirect'] = route('failed');
        return $data;
    }
}
