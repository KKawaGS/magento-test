<?php

namespace GateSoftware\GatePay\Gateway\Request;

use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Magento\Payment\Gateway\Request\BuilderInterface;
use Magento\Payment\Model\InfoInterface;

abstract class AbstractRequest implements BuilderInterface
{
    protected InfoInterface $payment;
    protected OrderAdapterInterface $order;
    protected PaymentDataObjectInterface $buildPayment;

    /**
     * @inheritDoc
     */
    public function build(array $buildSubject)
    {
        if (!isset($buildSubject['payment']) || !$buildSubject['payment'] instanceof PaymentDataObjectInterface) {
            throw new \InvalidArgumentException('Payment data object should be provided');
        }

        $this->buildPayment = $buildSubject['payment'];
        $this->payment = $this->buildPayment->getPayment();
        $this->order = $this->buildPayment->getOrder();
    }
}
