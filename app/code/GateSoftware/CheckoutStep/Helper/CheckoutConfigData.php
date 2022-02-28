<?php

namespace GateSoftware\CheckoutStep\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class CheckoutConfigData extends AbstractHelper
{
    const XML_PATH_DISABLE_BILLING_ADDRESS = 'checkout/options/disable_billing_address';

    /**
     * Check if disable billing option is active.
     * If true, billing address is always the same as shipping.
     */
    public function isBillingAddressDisabled() : bool
    {
        return $this->scopeConfig->isSetFlag(
            self::XML_PATH_DISABLE_BILLING_ADDRESS,
            ScopeInterface::SCOPE_STORE
        );
    }
}


