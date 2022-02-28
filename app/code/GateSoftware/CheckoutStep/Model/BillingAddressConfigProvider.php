<?php

namespace GateSoftware\CheckoutStep\Model;

use GateSoftware\CheckoutStep\Helper\CheckoutConfigData;
use Magento\Checkout\Model\ConfigProviderInterface;

class BillingAddressConfigProvider implements ConfigProviderInterface
{
    private CheckoutConfigData $checkoutConfigData;

    public function __construct(CheckoutConfigData $checkoutConfigData)
    {
        $this->checkoutConfigData = $checkoutConfigData;
    }

    public function getConfig() : array
    {
        $config = [];
        $config["isBillingAddressDisabled"] = $this->checkoutConfigData->isBillingAddressDisabled();
        return $config;
    }
}
