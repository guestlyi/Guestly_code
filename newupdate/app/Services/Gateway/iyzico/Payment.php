<?php

namespace App\Services\Gateway\iyzico;

use App\Services\BasicService;
use Iyzipay\Model\Buyer;
use Iyzipay\Model\Address;
use Iyzipay\Model\BasketItem;
use Iyzipay\Model\CheckoutFormInitialize;
use Iyzipay\Request\CreateCheckoutFormInitializeRequest;
use Iyzipay\Request\RetrieveCheckoutFormRequest;
use Iyzipay\Options;

class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        try {
            $basic = basicControl();

            $baseUrl = $gateway->environment === 'live'
                ? 'https://api.iyzipay.com'
                : 'https://sandbox-api.iyzipay.com';

            $parameters = json_decode(json_encode($gateway->parameters), true);

            $options = new Options();
            $options->setApiKey($parameters['api_key'] ?? '');
            $options->setSecretKey($parameters['secret'] ?? '');
            $options->setBaseUrl($baseUrl);

            $request = new CreateCheckoutFormInitializeRequest();
            $request->setLocale('en');
            $request->setConversationId($deposit->trx_id);
            $request->setPrice(round($deposit->payable_amount, 2));
            $request->setPaidPrice(round($deposit->payable_amount, 2));
            $request->setCurrency($deposit->payment_method_currency);
            $request->setBasketId($deposit->trx_id);
            $request->setPaymentGroup('PRODUCT');
            $request->setCallbackUrl(route('ipn', [$gateway->code, $deposit->trx_id]));

            $buyer = new Buyer();
            $buyer->setId((string)$deposit->user_id);
            $buyer->setName($deposit->user->firstname ?? $deposit->user->name);
            $buyer->setSurname($deposit->user->lastname ?? "");
            $buyer->setEmail($deposit->user->email);
            $buyer->setIdentityNumber("11111111111");
            $buyer->setGsmNumber($deposit->user->phone ?? "");
            $buyer->setIp(request()->ip());
            $buyer->setCity($deposit->user->city ?? "");
            $buyer->setCountry($deposit->user->country ?? "");
            $buyer->setRegistrationAddress($deposit->user->address_one ?? "");
            $request->setBuyer($buyer);

            $address = new Address();
            $address->setContactName($deposit->user->firstname . ' ' . ($deposit->user->lastname ?? ''));
            $address->setCity($deposit->user->city ?? '');
            $address->setCountry($deposit->user->country ?? '');
            $address->setAddress($deposit->user->address_one ?? '');
            $address->setZipCode($deposit->user->zip_code ?? '');
            $request->setBillingAddress($address);
            $request->setShippingAddress($address);

            $basketItem = new BasketItem();
            $basketItem->setId("ITEM_" . $deposit->trx_id);
            $basketItem->setName("Payment to " . $basic->site_title);
            $basketItem->setCategory1("Payment");
            $basketItem->setItemType("VIRTUAL");
            $basketItem->setPrice(round($deposit->payable_amount, 2));
            $request->setBasketItems([$basketItem]);

            $checkoutFormInitialize = CheckoutFormInitialize::create($request, $options);

            if ($checkoutFormInitialize->getStatus() === 'success') {
                return json_encode([
                    'redirect' => true,
                    'redirect_url' => $checkoutFormInitialize->getPaymentPageUrl(),
                ]);
            }

            return json_encode([
                'error' => true,
                'message' => $checkoutFormInitialize->getErrorMessage()
            ]);

        } catch (\Exception $e) {
            return json_encode([
                'error' => true,
                'message' => $e->getMessage()
            ]);
        }
    }

    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        try {
            $token = $request->input('token');
            if (!$token) {
                return redirect()->route('home')->with('error', 'Invalid payment request');
            }

            $parameters = json_decode(json_encode($gateway->parameters), true);
            $baseUrl = $gateway->environment === 'live'
                ? 'https://api.iyzipay.com'
                : 'https://sandbox-api.iyzipay.com';

            $options = new Options();
            $options->setApiKey($parameters['api_key'] ?? '');
            $options->setSecretKey($parameters['secret'] ?? '');
            $options->setBaseUrl($baseUrl);

            $retrieveRequest = new RetrieveCheckoutFormRequest();
            $retrieveRequest->setLocale('en');
            $retrieveRequest->setConversationId($deposit->trx_id);
            $retrieveRequest->setToken($token);

            $checkoutForm = \Iyzipay\Model\CheckoutForm::retrieve($retrieveRequest, $options);

            if ($checkoutForm->getPaymentStatus() === 'SUCCESS') {
                app(BasicService::class)->preparePaymentUpgradation($deposit);

                return [
                    'status' => 'success',
                    'redirect' => route('success'),
                    'msg' => 'Payment successful'
                ];
            }

            return [
                'status' => 'error',
                'redirect' => route('failed'),
                'msg' => 'Payment failed'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'redirect' => route('failed'),
                'msg' => 'Payment failed'
            ];
        }
    }
}
