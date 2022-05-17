<?php

namespace GateSoftware\GatePay\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class CaptureRequest implements \Magento\Payment\Gateway\Request\BuilderInterface
{

    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment']) || !$buildSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        $paymentDO = $buildSubject['payment'];
        $payment = $paymentDO->getPayment();

        if (!$payment instanceof OrderPaymentInterface) {
            throw new \LogicException('Order payment should be provided');
        }

        return [
            'TXN_TYPE' => 'S',
            'TXN_ID' =>$payment->getLastTransId(),
        ];
    }
}
