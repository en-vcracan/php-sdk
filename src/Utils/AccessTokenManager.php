<?php


namespace GlobalPayments\Api\Utils;


use GlobalPayments\Api\Entities\GpApi\AccessToken;
use GlobalPayments\Api\Entities\GpApi\AccessTokenRequest;
use GlobalPayments\Api\Gateways\GpApiConnector;
use GlobalPayments\Api\Gateways\RestGatewayWithCompression;
use GlobalPayments\Api\ServicesConfig;

class AccessTokenManager extends RestGatewayWithCompression
{
    public $appId;
    public $appKey;
    public $apiVersion;
    public $servicePoint;
    /**
     * @var $accessToken AccessToken
     */
    private $accessToken;

    public function generateAccessToken()
    {
        $requestHeader = ['X-GP-VERSION' => $this->apiVersion];
        $endPoint = $this->servicePoint . GpApiConnector::ACCESS_TOKEN_ENDPOINT;
        $requestBody = new AccessTokenRequest(
            $this->appId,
            $this->generateNonce(),
            $this->generateSecret(),
            'client_credentials'
        );

        $request = $this->doTransaction("POST", $endPoint, $requestBody, null, $requestHeader);
//        $this->accessToken = $request;
        $this->accessToken = new AccessToken(
            $request->token,
            $request->type,
            $request->time_created,
            $request->seconds_to_expire
        );
        return $this->accessToken;
    }

    public function getAccessToken()
    {
        if (empty($this->accessToken) || ($this->accessToken->seconds_to_expire < 100)) {
            $this->generateAccessToken();
        }
        return $this->accessToken;
    }

    public function setAccessToken(AccessToken $accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function initialize(ServicesConfig $servicesConfig)
    {
        $this->appId = $servicesConfig->appId;
        $this->appKey = $servicesConfig->appKey;
        $this->apiVersion = $servicesConfig->apiVersion;
        $this->servicePoint = $servicesConfig->serviceUrl;
    }

    private function generateSecret()
    {
        return hash('SHA512', $this->generateNonce() . $this->appKey);
    }

    private function generateNonce()
    {
        $base = new \DateTime();
        return $base->format(\DateTime::RFC3339);
    }
}