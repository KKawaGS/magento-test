<?php

namespace GateSoftware\GatePay\Gateway\Http;

use GateSoftware\GatePay\PayU\PayUAuthorization;
use GateSoftware\GatePay\PayU\PayUConfiguration;
use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;

class TransferFactory implements TransferFactoryInterface
{

    private TransferBuilder $transferBuilder;
    private PayUAuthorization $payUAuth;
    private PayUConfiguration $payUConf;

    public function __construct(
        TransferBuilder $transferBuilder,
        PayUAuthorization $payUAuth,
        PayUConfiguration $payUConf
    ) {
        $this->transferBuilder = $transferBuilder;
        $this->payUAuth = $payUAuth;
        $this->payUConf = $payUConf;
    }

    public function create(array $request)
    {
        return $this->transferBuilder
            ->setMethod('POST')
            ->setHeaders(
                [
                    'Content-Type' => 'application/json',
                    'Authorization' => $this->payUAuth->getAuthorizationString(),
                    'Cache-Control' => 'no-cache'
                ]
            )
            ->setBody(json_encode($request, JSON_UNESCAPED_SLASHES))
            ->setUri($this->payUConf->getOrderUrl())
            ->setClientConfig(
                ['maxredirects' => 0]
            )
            ->build();
    }
}
