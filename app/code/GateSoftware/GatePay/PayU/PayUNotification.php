<?php

namespace GateSoftware\GatePay\PayU;

use Exception;
use Magento\Framework\App\RequestInterface;

class PayUNotification
{
    const PAYU_HEADER = 'OpenPayu-Signature';
    const PAYU_STATUS_PENDING = 'PENDING';
    const PAYU_STATUS_CANCELED = 'CANCELED';
    const PAYU_STATUS_SUCCESS = 'SUCCESS';

    private PayUConfiguration $payUConfig;
    private RequestInterface $request;

    public function __construct(PayUConfiguration $conf, RequestInterface $request)
    {
        $this->payUConfig = $conf;
        $this->request = $request;
    }

    /**
     * @throws Exception
     */
    public function validateNotification()
    {
        if ($this->isPayUHeaderSet()) {
            throw new Exception("Bad request" . empty($this->getPayUHeader()), 400);
        }

        $header = $this->getPayUHeader();
        $jsonNotification = $this->getJSONNotification();

        if (!$this->validatePayUSignature($header, $jsonNotification)) {
            throw new Exception('Signature validation error', 400);
        }
    }

    private function isPayUHeaderSet(): bool
    {
        return empty($this->getPayUHeader());
    }

    public function getPayUHeader(): ?string
    {
        return $this->request->getHeader(self::PAYU_HEADER);
    }

    private function getJSONNotification(): string
    {
        return $this->request->getContent();
    }

    private function validatePayUSignature(string $signatureHeader, string $jsonNotification): bool
    {
        $signatureData = $this->parsePayUSignatureHeader($signatureHeader);
        $signature = $signatureData['signature'];
        $algorithm = $signatureData['algorithm'];
        $secondKey = $this->payUConfig->getSecondKey();

        if (isset($signature)) {
            if ($algorithm === 'MD5') {
                $hash = md5($jsonNotification . $secondKey);
            } else if (in_array($algorithm, array('SHA', 'SHA1', 'SHA-1'))) {
                $hash = sha1($jsonNotification . $secondKey);
            } else {
                $hash = hash('sha256', $jsonNotification . $secondKey);
            }

            if (strcmp($signature, $hash) == 0) {
                return true;
            }
        }

        return false;
    }

    private function parsePayUSignatureHeader(string $signature): array
    {
        $signatureList = explode(';', rtrim($signature, ';'));
        $signatureData = [];

        foreach ($signatureList as $data) {
            $part = explode('=', $data);
            $signatureData[$part[0]] = $part[1];
        }

        return $signatureData;
    }

    public function getStatus(): string
    {
        $body = $this->getNotificationBody();
        return $body->order->status;
    }

    public function getNotificationBody()
    {
        return \Safe\json_decode($this->getJSONNotification());
    }
}
