<?php

namespace GateSoftware\GatePay\Gateway\Request;

use Magento\Framework\Locale\Resolver;

class AuthorizationRequestBuyer extends AbstractAuthorizationRequest
{
    private Resolver $shop;

    public function __construct(Resolver $shop)
    {
        $this->shop = $shop;
    }

    public function build(array $buildSubject): array
    {
        parent::build($buildSubject);

        $billingAddress = $this->order->getBillingAddress();
        $shippingAddress = $this->order->getShippingAddress();

        return [
            'customerIp' => $this->order->getRemoteIp(),
            'buyer' => [
                'extCustomerId' => $this->order->getCustomerId(),
                'email' => $billingAddress->getEmail(),
                'phone' => $billingAddress->getTelephone(),
                'firstName' => $billingAddress->getFirstname(),
                'lastName' => $billingAddress->getLastname(),
                'language' => $this->shop->getLocale(),
                'delivery' => [
                    'street' => $shippingAddress->getStreetLine1() .' '. $shippingAddress->getStreetLine2(),
                    'postalCode' => $shippingAddress->getPostcode(),
                    'city' => $shippingAddress->getCity(),
                    'state' => $shippingAddress->getRegionCode(),
                    'countryCode' => $shippingAddress->getCountryId(),
                    'recipientName' => $shippingAddress->getFirstname() .' '. $shippingAddress->getLastname(),
                    'recipientEmail' => $shippingAddress->getEmail(),
                    'recipientPhone' => $shippingAddress->getTelephone()
                ]
            ]
        ];
    }
}
