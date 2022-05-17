<?php

namespace GateSoftware\GatePay\Model\Ui;

    class ConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
    {

        const CODE = 'gatePaymentGateway';

        public function getConfig()
        {
            return [
                'payment' => [
                    self::CODE => [

                    ]
                ]
            ];
        }
    }
