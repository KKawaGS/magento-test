<?php

namespace GateSoftware\GatePay\Gateway\Request;

use GateSoftware\GatePay\Gateway\Http\Client\ClientMock;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use function PHPUnit\Framework\throwException;

class VoidRequest extends AbstractRequest
{
    public function build(array $buildSubject)
    {
        parent::build($buildSubject);

        return [
            'TXN_TYPE' => 'V',
            'TXN_ID' =>$this->payment->getLastTransId()
        ];
    }
}
