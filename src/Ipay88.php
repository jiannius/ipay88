<?php

namespace Jiannius\Ipay88;

class Ipay88
{
    public $credentials;

    public function __construct()
    {
        $this->setCredentials();
    }

    public function setCredentials($credentials = null) : void
    {
        $this->credentials = collect($credentials ?? [
            'merchant_code' => env('IPAY88_MERCHANT_CODE'),
            'merchant_key' => env('IPAY88_MERCHANT_KEY'),
            'url' => env('IPAY88_URL'),
            'query_url' => env('IPAY88_QUERY_URL'),
        ]);
    }

    public function getSignature($body) : string
    {
        $data = [
            $this->credentials->get('merchant_key'),
            data_get($body, 'MerchantCode'),
            data_get($body, 'RefNo'),
            str(data_get($body, 'Amount'))->replace('.', '')->replace(',', '')->toString(),
            data_get($body, 'Currency'),
        ];

        $str = implode('', $data);

        return hash('sha256', $str);
    }

    public function checkout($params) : mixed
    {
        $amount = app()->environment('production') ? data_get($params, 'Amount') : 1;

        $data = [
            ...$params,
            'Amount' => number_format($amount, 2),
            'MerchantCode' => $this->credentials->get('merchant_code'),
            'SignatureType' => 'SHA256',
            'ResponseURL' => route('__ipay88.redirect'),
            'BackendURL' => route('__ipay88.webhook'),
        ];

        $body = [
            ...$data,
            'signature' => $this->getSignature($data),
        ];

        return to_route('__ipay88.checkout', [
            'body' => $body,
            'url' => $this->credentials->get('url'),
        ]);
    }
}
