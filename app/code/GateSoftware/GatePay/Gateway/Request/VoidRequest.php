<?php

namespace GateSoftware\GatePay\Gateway\Request;

use GateSoftware\GatePay\Gateway\Http\Client\ClientMock;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use function PHPUnit\Framework\throwException;

class VoidRequest implements \Magento\Payment\Gateway\Request\BuilderInterface
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
            'TXN_TYPE' => 'V',
            'TXN_ID' =>$payment->getLastTransId(),
        ];
    }
}
