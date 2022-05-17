<?php

namespace GateSoftware\GatePay\Gateway\Request;

use GateSoftware\GatePay\PayU\PayUConfiguration;

class AuthorizationRequestTransaction extends AbstractAuthorizationRequest
{
    private PayUConfiguration $payuconf;

    public function __construct(PayUConfiguration $payuconf)
    {
        $this->payuconf = $payuconf;
    }

    public function build(array $buildSubject): array
    {
        parent::build($buildSubject);

        return [
            'TXN_TYPE' => 'A',
            'continueUrl' => $this->payuconf->getContinueUrl(),
            'description' => 'Luma Shop',
            'merchantPosId' => $this->payuconf->getPosId(),
            'currencyCode' => $this->order->getCurrencyCode(),
            'notifyUrl' => $this->payuconf->getNotifyUrl(),
            'extOrderId' => $this->order->getOrderIncrementId(),
            'orderId' => $this->order->getOrderIncrementId(),
        ];
    }
}
