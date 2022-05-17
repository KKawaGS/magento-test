<?php

namespace GateSoftware\GatePay\Gateway\Request;

use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;

class CaptureRequest extends AbstractRequest
{

    public function build(array $buildSubject)
    {
        parent::build($buildSubject);

        return [
            'TXN_TYPE' => 'S',
            'TXN_ID' =>$this->payment->getLastTransId()
        ];
    }
}
