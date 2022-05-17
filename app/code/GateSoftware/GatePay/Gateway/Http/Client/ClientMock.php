<?php

namespace GateSoftware\GatePay\Gateway\Http\Client;

use GateSoftware\GatePay\PayU\PayUAuthorization;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Payment\Gateway\Http\Client\Zend;
use Magento\Payment\Gateway\Http\ClientInterface;
use Magento\Payment\Gateway\Http\TransferInterface;
use Magento\Payment\Model\Method\Logger;

class ClientMock implements ClientInterface
{

    private Curl $curl;
    private Logger $logger;

    public function __construct(Curl $curl, Logger $logger)
    {
        $this->curl = $curl;
        $this->logger = $logger;
    }

    /**
     * @throws \Magento\Payment\Gateway\Http\ConverterException
     * @throws \Magento\Payment\Gateway\Http\ClientException
     */
    public function placeRequest(TransferInterface $transferObject): array
    {

        $txnType = \Safe\json_decode($transferObject->getBody())->TXN_TYPE;

        /*
         * Capture and void are processed according to payu notification only.
         */
        if ($txnType === 'S' || $txnType === 'V') {
            return ['response' => 'success'];
        }

        $this->setHeaders($transferObject);

        $jsonData = $transferObject->getBody();
        $postUrl = $transferObject->getUri();

        $this->curl->post($postUrl, $jsonData);
        $response = $this->curl->getBody();

        return \Safe\json_decode($response, JSON_OBJECT_AS_ARRAY);
    }

    private function setHeaders(TransferInterface $transferObject)
    {
        $headers = $transferObject->getHeaders();

        foreach ($headers as $k => $v) {
            if (is_string($k)) {
                $this->curl->addHeader($k, $v);
            }
        }
    }
}

