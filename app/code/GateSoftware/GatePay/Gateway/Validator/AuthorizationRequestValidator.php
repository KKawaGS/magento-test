<?php

namespace GateSoftware\GatePay\Gateway\Validator;

use Magento\Payment\Gateway\Validator\AbstractValidator;
use Magento\Payment\Gateway\Validator\ResultInterface;

class AuthorizationRequestValidator extends AbstractValidator
{
    const STATUS = 'status';
    const STATUS_CODE = 'statusCode';
    const STATUS_SUCCESS = 'SUCCESS';

    public function validate(array $validationSubject): ResultInterface
    {
        if (!isset($validationSubject['response']) || !is_array($validationSubject['response'])) {
            throw new \InvalidArgumentException('Response does not exist');
        }

        $response = $validationSubject['response'];
        $isValid = true;
        $fails = [];
        $errorCodes = [];

        if (!$this->isSuccessful($response)) {
            $isValid = false;
            $fails[] = $response[self::STATUS]['statusDesc'];
            $errorCodes[] = $response[self::STATUS]['code'];
        }

        return $this->createResult($isValid, $fails, $errorCodes);
    }

    private function isSuccessful($response) : bool
    {
        return isset($response[self::STATUS][self::STATUS_CODE]) && $response[self::STATUS][self::STATUS_CODE] === self::STATUS_SUCCESS;
    }
}
