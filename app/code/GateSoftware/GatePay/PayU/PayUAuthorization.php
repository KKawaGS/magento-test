<?php

namespace GateSoftware\GatePay\PayU;

use Exception;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Phrase;

class PayUAuthorization
{
    private Curl $curl;
    private PayUConfiguration $payUConf;
    private string $access_token;
    private string $token_type;
    private string $expires_in;


    public function __construct(Curl $curl, PayUConfiguration $conf)
    {
        $this->curl = $curl;
        $this->payUConf = $conf;
    }

    /**
     * @throws LocalizedException
     */
    public function getExpiresIn(): string
    {
        if (!isset($this->expires_in)) {
            $this->makeAuthRequest();
        }

        return $this->expires_in;
    }

    /**
     * @throws LocalizedException
     */
    private function makeAuthRequest() : void
    {
        $response = [];
        $url = $this->payUConf->getAuthUrl();
        $params = $this->getAuthParams();

        $this->curl->post($url, $params);
        $response = \Safe\json_decode($this->curl->getBody());

        if ($this->curl->getStatus() === 200) {
            $this->access_token = $response->access_token;
            $this->token_type = $response->token_type;
            $this->expires_in = $response->expires_in;
        } else {
            $phrase = new Phrase("Problem authenticating with payU");
            throw new LocalizedException($phrase);
        }
    }

    private function getAuthParams(): array
    {
        return [
            'grant_type' => $this->payUConf->getGrantType(),
            'client_id' => $this->payUConf->getClientId(),
            'client_secret' => $this->payUConf->getClientSecret()
        ];
    }

    /**
     * @throws LocalizedException
     */
    public function getAuthorizationString(): string
    {
        return $this->getTokenType() . " " . $this->getAuthToken();
    }

    /**
     * @throws LocalizedException
     */
    public function getTokenType(): string
    {
        if (!isset($this->token_type)) {
            $this->makeAuthRequest();
        }

        return $this->token_type;
    }

    /**
     * @throws LocalizedException
     */
    public function getAuthToken(): string
    {
        if (!isset($this->access_token)) {
            $this->makeAuthRequest();
        }

        return $this->access_token;
    }

}
