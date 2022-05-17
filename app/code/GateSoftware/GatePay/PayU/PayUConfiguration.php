<?php

namespace GateSoftware\GatePay\PayU;

use Magento\Framework\UrlInterface;
use Magento\Payment\Gateway\ConfigInterface;
use Magento\Store\Model\StoreManagerInterface;

class PayUConfiguration
{
    const SANDBOX = 'sandbox';
    const GATE_PAYMENT_GATEWAY = 'gatePaymentGateway';
    const GRANT_CLIENT_CREDENTIALS = 'client_credentials';

    //options
    const GRANT_TRUSTED_MERCHANT = 'trusted_merchant';
    const SANDBOX_URL = 'sandbox_pos_parameters/advanced_sandbox_pos_parameters/sandbox_url';
    const SANDBOX_AUTH_TOKEN_URI = 'sandbox_pos_parameters/advanced_sandbox_pos_parameters/sandbox_auth_token_uri';
    const SANDBOX_ORDER_URI = 'sandbox_pos_parameters/advanced_sandbox_pos_parameters/sandbox_order_uri';
    const SANDBOX_CLIENT_ID = 'sandbox_pos_parameters/sandbox_client_id';
    const SANDBOX_CLIENT_SECRET = 'sandbox_pos_parameters/sandbox_client_secret';
    const SANDBOX_POS_ID = 'sandbox_pos_parameters/sandbox_pos_id';
    const SANDBOX_SECOND_KEY = 'sandbox_pos_parameters/sandbox_second_key';
    const PRODUCTION_URL = 'production_pos_parameters/advanced_production_pos_parameters/production_url';
    const PRODUCTION_AUTH_TOKEN_URI = 'production_pos_parameters/advanced_production_pos_parameters/production_auth_token_uri';
    const PRODUCTION_ORDER_URI = 'production_pos_parameters/advanced_production_pos_parameters/production_order_uri';
    const PRODUCTION_CLIENT_ID = 'production_pos_parameters/production_client_id';
    const PRODUCTION_CLIENT_SECRET = 'production_pos_parameters/production_client_secret';
    const PRODUCTION_POS_ID = 'production_pos_parameters/production_pos_id';
    const PRODUCTION_SECOND_KEY = 'production_pos_parameters/production_second_key';

    private ConfigInterface $config;
    private string $grant_type;
    private StoreManagerInterface $store;

    public function __construct(ConfigInterface $config, StoreManagerInterface $store)
    {
        $this->config = $config;
        $this->store = $store;
        $this->grant_type = self::GRANT_CLIENT_CREDENTIALS;
    }

    public function getAuthUrl(): string
    {
        $url = '';
        $uri = '';

        if ($this->isSandboxEnabled()) {
            $url = $this->config->getValue(self::SANDBOX_URL);
            $uri = $this->config->getValue(self::SANDBOX_AUTH_TOKEN_URI);
        } else {
            $url = $this->config->getValue(self::PRODUCTION_URL);
            $uri = $this->config->getValue(self::PRODUCTION_AUTH_TOKEN_URI);
        }

        return $url . $uri;
    }

    public function isSandboxEnabled(): bool
    {
        return $this->config->getValue(self::SANDBOX);
    }

    public function getOrderUrl(): string
    {
        $url = '';
        $uri = '';

        if ($this->isSandboxEnabled()) {
            $url = $this->config->getValue(self::SANDBOX_URL);
            $uri = $this->config->getValue(self::SANDBOX_ORDER_URI);
        } else {
            $url = $this->config->getValue(self::PRODUCTION_URL);
            $uri = $this->config->getValue(self::PRODUCTION_ORDER_URI);
        }

        return $url . $uri;
    }

    public function getGrantType(): string
    {
        return $this->grant_type;
    }

    public function setGrantType(string $grant_type): void
    {
        $this->grant_type = $grant_type;
    }

    public function getClientId(): string
    {
        return $this->isSandboxEnabled()
            ? $this->config->getValue(self::SANDBOX_CLIENT_ID)
            : $this->config->getValue(self::PRODUCTION_CLIENT_ID);
    }

    public function getSecondKey(): string
    {
        return $this->isSandboxEnabled()
            ? $this->config->getValue(self::SANDBOX_SECOND_KEY)
            : $this->config->getValue(self::PRODUCTION_SECOND_KEY);
    }

    public function getPosId(): string
    {
        return $this->isSandboxEnabled()
            ? $this->config->getValue(self::SANDBOX_POS_ID)
            : $this->config->getValue(self::PRODUCTION_POS_ID);
    }

    public function getClientSecret(): string
    {
        return $this->isSandboxEnabled()
            ? $this->config->getValue(self::SANDBOX_CLIENT_SECRET)
            : $this->config->getValue(self::PRODUCTION_CLIENT_SECRET);
    }

    public function getContinueUrl(): string
    {
        return $this->store->getStore()->getBaseUrl(UrlInterface::URL_TYPE_WEB) . 'checkout/onepage/success';
    }

    public function getNotifyUrl(): string
    {
        return $this->store->getStore()->getBaseUrl(UrlInterface::URL_TYPE_WEB) . 'payu/notify';
    }

}
