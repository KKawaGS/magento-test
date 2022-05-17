<?php

namespace GateSoftware\GatePay\Block;

class Info extends \Magento\Payment\Block\ConfigurableInfo
{
    protected function getLabel($field)
    {
        return __($field);
    }

    protected function getValueView($field, $value)
    {
        return parent::getValueView($field, $value);
    }
}
