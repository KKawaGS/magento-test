<?php

namespace GateSoftware\GatePay\Gateway\Response;

use InvalidArgumentException;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Response\HandlerInterface;

class TxnIdHandler implements HandlerInterface
{
    public function handle(array $handlingSubject, array $response)
    {
        if (!isset($handlingSubject['payment']) || !$handlingSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new InvalidArgumentException('Payment data object should be provided');
        }

        $payment = $handlingSubject['payment']->getPayment();
        $payment->setTransactionId($response['orderId']);
        $payment->setIsTransactionClosed(false);

        $payment->setAdditionalInformation(
            'payu_redirect_url',
            $response['redirectUri']
        );

    }
}
