<?php


namespace Gateways\GpApiConnector;


use GlobalPayments\Api\Gateways\GpApiConnector;
use GlobalPayments\Api\ServicesConfig;
use GlobalPayments\Api\ServicesContainer;
use GlobalPayments\Api\Utils\AccessTokenManager;
use PHPUnit\Framework\TestCase;
use Gateways\GpApiConnector\GpApiTestData;

class AccessTokenTest extends TestCase
{
    /** @var $gatewayInstance GpApiConnector */
    private $gatewayInstance;

    /** @var $accessTokenManager AccessTokenManager */
    private $accessTokenManager;

    public function setup(): void
    {
        ServicesContainer::configure($this->setUpConfig());
        $this->gatewayInstance = ServicesContainer::instance();
    }

    public function testAccessTokenExistence()
    {
        $accessToken = $this->accessTokenManager->generateAccessToken();
        $this->assertIsString($accessToken->token);
    }

    public function setUpConfig()
    {
        $config = new ServicesConfig();
        $accessTokenManager = new \GlobalPayments\Api\Utils\AccessTokenManager();
        //this is gpapistuff stuff
        $config->appId = 'VuKlC2n1cr5LZ8fzLUQhA7UObVks6tFF';
        $config->appKey = 'NmGM0kg92z2gA7Og';
        $config->apiVersion = '2020-01-20';
        $config->serviceUrl = 'https://apis.sandbox.globalpay.com/ucp';
        $config->accessTokenManager = $accessTokenManager;
        $this->accessTokenManager = $accessTokenManager;

        return $config;
    }
}