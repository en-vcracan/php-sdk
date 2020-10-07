<?php


namespace Gateways\GpApiConnector;


use GlobalPayments\Api\Entities\Exceptions\ApiException;
use GlobalPayments\Api\PaymentMethods\DebitTrackData;
use GlobalPayments\Api\ServicesConfig;
use GlobalPayments\Api\ServicesContainer;
use GlobalPayments\Api\Utils\AccountNameManager;
use PHPUnit\Framework\TestCase;

class DebitCardTest extends TestCase
{
    public function setup(): void
    {
        ServicesContainer::configure($this->setUpConfig());
    }

    public function testChargeCardPresentDebitCard()
    {
        $debitCard = new DebitTrackData();
        $debitCard->track = '%B4012002000060016^VI TEST CREDIT^251210118039000000000396?;4012002000060016=25121011803939600000?';
        $debitCard->tag = '9F4005F000F0A0019F02060000000025009F03060000000000009F2608D90A06501B48564E82027C005F3401019F360200029F0702FF009F0802008C9F0902008C9F34030403029F2701809F0D05F0400088009F0E0508000000009F0F05F0400098005F280208409F390105FFC605DC4000A800FFC7050010000000FFC805DC4004F8009F3303E0B8C89F1A0208409F350122950500000080005F2A0208409A031409109B02E8009F21030811539C01009F37045EED3A8E4F07A00000000310109F0607A00000000310108407A00000000310109F100706010A03A400029F410400000001';
        $debitCard->cardHolderName = 'James Mason';

        try {
            $response = $debitCard->charge(669)
                ->withCurrency("EUR")
                ->withChannel(\GlobalPayments\Api\Entities\Enums\GpApi\Channels::CP)
                ->withCountry("US")
                ->withOrderId("124214-214221")
                ->withEntryMode(\GlobalPayments\Api\Entities\Enums\GpApi\EntryMode::SWIPE)
                ->execute();
        } catch (ApiException $e) {
            $this->fail('Debit card charge failed');
        }

        $this->assertEquals('00', $response->payment_method->result);

    }


    public function setUpConfig()
    {
        $config = new ServicesConfig();
        $accessTokenManager = new \GlobalPayments\Api\Utils\AccessTokenManager();
        $accountNameManager = new AccountNameManager();
        //this is gpapistuff stuff
        $config->appId = 'VuKlC2n1cr5LZ8fzLUQhA7UObVks6tFF';
        $config->appKey = 'NmGM0kg92z2gA7Og';
        $config->apiVersion = '2020-01-20';
        $config->serviceUrl = 'https://apis.sandbox.globalpay.com/ucp';
        $config->accessTokenManager = $accessTokenManager;
        $config->accountNameManager = $accountNameManager;

        return $config;
    }
}