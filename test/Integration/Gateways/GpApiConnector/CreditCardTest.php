<?php


namespace Gateways\GpApiConnector;


use GlobalPayments\Api\Entities\Enums\GpApi\EntryMode;
use GlobalPayments\Api\Entities\Exceptions\ApiException;
use GlobalPayments\Api\PaymentMethods\CreditCardData;
use GlobalPayments\Api\Utils\AccountNameManager;
use PHPUnit\Framework\TestCase;
use GlobalPayments\Api\ServicesConfig;
use GlobalPayments\Api\ServicesContainer;

class CreditCardTest extends TestCase
{

    public function setup(): void
    {
        ServicesContainer::configure($this->setUpConfig());
    }

    public function testECOMwithNCPTransaction()
    {
        $card = new CreditCardData();
        $card->number = "4263970000005262";
        $card->expMonth = 12;
        $card->expYear = 2025;
        $card->cvn = "131";
        $card->cardHolderName = "James Mason";

        try {
            $response = $card->charge(669)
                ->withCurrency("EUR")
                ->withChannel(\GlobalPayments\Api\Entities\Enums\GpApi\Channels::CNP)
                ->withCountry("US")
                ->withOrderId("124214-214221")
                ->withEntryMode(EntryMode::ECOM)
                ->execute();
        } catch (ApiException $e) {
            $this->fail("Card not present with ECOM transaction failed");
        }

        $this->assertEquals('00', $response->payment_method->result);
    }

    public function testCardPresentWithChipTransaction()
    {
        $card = new \GlobalPayments\Api\PaymentMethods\CreditTrackData();
        $card->track = '%B4012002000060016^VI TEST CREDIT^251210118039000000000396?;4012002000060016=25121011803939600000?';
        $card->tag = '9F4005F000F0A0019F02060000000025009F03060000000000009F2608D90A06501B48564E82027C005F3401019F360200029F0702FF009F0802008C9F0902008C9F34030403029F2701809F0D05F0400088009F0E0508000000009F0F05F0400098005F280208409F390105FFC605DC4000A800FFC7050010000000FFC805DC4004F8009F3303E0B8C89F1A0208409F350122950500000080005F2A0208409A031409109B02E8009F21030811539C01009F37045EED3A8E4F07A00000000310109F0607A00000000310108407A00000000310109F100706010A03A400029F410400000001';
        $card->cardHolderName = 'James Mason';
        try {
            // process an auto-capture authorization
            $response = $card->charge(669)
                ->withCurrency("EUR")
                ->withChannel(\GlobalPayments\Api\Entities\Enums\GpApi\Channels::CP)
                ->withCountry("US")
                ->withOrderId("124214-214221")
                ->withEntryMode(\GlobalPayments\Api\Entities\Enums\GpApi\EntryMode::CHIP)
                ->execute();
        } catch (ApiException $e) {
            $this->fail('Chip Credit Card transaction failed');
        }

        $this->assertEquals('00', $response->payment_method->result);
    }

    public function testCardPresentWithSwipeTransaction()
    {
        $card = new \GlobalPayments\Api\PaymentMethods\CreditTrackData();
        $card->track = '%B4012002000060016^VI TEST CREDIT^251210118039000000000396?;4012002000060016=25121011803939600000?';
        $card->cardHolderName = 'James Mason';
        try {
            // process an auto-capture authorization
            $response = $card->charge(669)
                ->withCurrency("EUR")
                ->withChannel(\GlobalPayments\Api\Entities\Enums\GpApi\Channels::CP)
                ->withCountry("US")
                ->withOrderId("124214-214221")
                ->withEntryMode(\GlobalPayments\Api\Entities\Enums\GpApi\EntryMode::SWIPE)
                ->execute();
        } catch (ApiException $e) {
            $this->fail('Swipe Credit Card transaction failed');
        }

        $this->assertEquals('00', $response->payment_method->result);
    }

    public function testRefundOnCardPresentChipCard()
    {
        $card = new \GlobalPayments\Api\PaymentMethods\CreditTrackData();
        $card->track = '%B4012002000060016^VI TEST CREDIT^251210118039000000000396?;4012002000060016=25121011803939600000?';
        $card->tag = '9F4005F000F0A0019F02060000000025009F03060000000000009F2608D90A06501B48564E82027C005F3401019F360200029F0702FF009F0802008C9F0902008C9F34030403029F2701809F0D05F0400088009F0E0508000000009F0F05F0400098005F280208409F390105FFC605DC4000A800FFC7050010000000FFC805DC4004F8009F3303E0B8C89F1A0208409F350122950500000080005F2A0208409A031409109B02E8009F21030811539C01009F37045EED3A8E4F07A00000000310109F0607A00000000310108407A00000000310109F100706010A03A400029F410400000001';
        $card->cardHolderName = 'James Mason';

        try {
            // process an auto-capture authorization
            $response = $card->refund(669)
                ->withCurrency("EUR")
                ->withChannel(\GlobalPayments\Api\Entities\Enums\GpApi\Channels::CP)
                ->withCountry("US")
                ->withOrderId("124214-214221")
                ->withEntryMode(\GlobalPayments\Api\Entities\Enums\GpApi\EntryMode::CHIP)
                ->execute();
        } catch (ApiException $e) {
            $this->fail('Chip Credit Card refund transaction failed');
        }

        $this->assertEquals('00', $response->payment_method->result);
    }
    
    public function testCardTokenization()
    {
        $card = new CreditCardData();
        $card->number = "4263970000005262";
        $card->expMonth = 12;
        $card->expYear = 2025;
        $card->cvn = "131";
        $card->cardHolderName = "James Mason";

        try {
            // process an auto-capture authorization
            $response = $card->tokenize()
                ->execute();

        } catch (ApiException $e) {
            $this->fail('Credit Card Tokenization failed ' . $e->getMessage());
        }

        $this->assertEquals('00', $response->result);
    }

    public function testCardTokenizationThenPayingWithToken()
    {
        $card = new CreditCardData();
        $card->number = "4263970000005262";
        $card->expMonth = 12;
        $card->expYear = 2025;
        $card->cvn = "131";
        $card->cardHolderName = "James Mason";

        try {
            // process an auto-capture authorization
            $response = $card->tokenize()
                ->execute();

        } catch (ApiException $e) {
            $this->fail('Credit Card Tokenization failed ' . $e->getMessage());
        }

        $tokenId = $response->id;

        $tokenizedCard = new CreditCardData();
        $tokenizedCard->token = $tokenId;
        $tokenizedCard->cardHolderName = "James Mason";

        try {
            $response = $card->charge(669)
                ->withCurrency("EUR")
                ->withChannel(\GlobalPayments\Api\Entities\Enums\GpApi\Channels::CNP)
                ->withCountry("US")
                ->withOrderId("124214-214221")
                ->withEntryMode(EntryMode::ECOM)
                ->execute();
        } catch (ApiException $e) {
            $this->fail("Tokenized card transaction with ECOM transaction failed");
        }

        $this->assertEquals('00', $response->payment_method->result);
    }

    public function testCardTokenizationThenTokenRetrievalBasedOnId()
    {
        $card = new CreditCardData();
        $card->number = "4263970000005262";
        $card->expMonth = 12;
        $card->expYear = 2025;
        $card->cvn = "131";
        $card->cardHolderName = "James Mason";

        try {
            // process an auto-capture authorization
            $response = $card->tokenize()
                ->execute();

        } catch (ApiException $e) {
            $this->fail('Credit Card Tokenization failed ' . $e->getMessage());
        }

        $tokenId = $response->id;

        $tokenizedCard = new CreditCardData();
        $tokenizedCard->token = $tokenId;

        try {
            $response = $tokenizedCard->verify()->execute();
        } catch (ApiException $e) {
            $this->fail('Credit Card token retrieval failed ' . $e->getMessage());
        }

        $this->assertEquals('00', $response->action->result_code);
    }

    public function testCardTokenizationThenCardDetokenization()
    {
        $card = new CreditCardData();
        $card->number = "4263970000005262";
        $card->expMonth = 12;
        $card->expYear = 2025;
        $card->cvn = "131";
        $card->cardHolderName = "James Mason";

        try {
            // process an auto-capture authorization
            $response = $card->tokenize()
                ->execute();

        } catch (ApiException $e) {
            $this->fail('Credit Card Tokenization failed ' . $e->getMessage());
        }

        $tokenId = $response->id;

        $tokenizedCard = new CreditCardData();
        $tokenizedCard->token = $tokenId;

        try {
            $response = $tokenizedCard->detokenize();
        } catch (ApiException $e) {
            $this->fail('Credit Card detokenization failed ' . $e->getMessage());
        }

        $this->assertEquals('00', $response->result);
    }

    public function testCardTokenizationThenDeletion()
    {
        $card = new CreditCardData();
        $card->number = "4263970000005262";
        $card->expMonth = 12;
        $card->expYear = 2025;
        $card->cvn = "131";
        $card->cardHolderName = "James Mason";

        try {
            // process an auto-capture authorization
            $response = $card->tokenize()
                ->execute();

        } catch (ApiException $e) {
            $this->fail('Credit Card Tokenization failed ' . $e->getMessage());
        }

        $tokenId = $response->id;

        $tokenizedCard = new CreditCardData();
        $tokenizedCard->token = $tokenId;

        try {
            $response = $tokenizedCard->deleteToken();
        } catch (ApiException $e) {
            $this->fail('Credit Card token deletion failed ' . $e->getMessage());
        }

        $this->assertEquals(true, $response);
    }

    public function testCardTokenizationThenUpdate()
    {
        $card = new CreditCardData();
        $card->number = "4263970000005262";
        $card->expMonth = 12;
        $card->expYear = 2025;
        $card->cvn = "131";
        $card->cardHolderName = "James Mason";

        try {
            // process an auto-capture authorization
            $response = $card->tokenize()
                ->execute();

        } catch (ApiException $e) {
            $this->fail('Credit Card Tokenization failed ' . $e->getMessage());
        }

        $tokenId = $response->id;

        $tokenizedCard = new CreditCardData();
        $tokenizedCard->token = $tokenId;
        $tokenizedCard->expYear = '26';
        $tokenizedCard->expMonth = '10';

        try {
            $response = $tokenizedCard->updateTokenExpiry();
        } catch (ApiException $e) {
            $this->fail('Credit Card token update failed ' . $e->getMessage());
        }

        $this->assertEquals(true, $response);
    }

    public function setUpConfig()
    {
        $config = new ServicesConfig();
        $accessTokenManager = new \GlobalPayments\Api\Utils\AccessTokenManager();
        $accountNameManager = new AccountNameManager();
        //this is gpapistuff stuff
        $config->appId = 'VuKlC2n1cr5LZ8fzLUQhA7UObVks6tFF';
        $config->appKey = 'NmGM0kg92z2gA7Og';
        $config->apiVersion = '2020-04-10';
        $config->serviceUrl = 'https://apis.sandbox.globalpay.com/ucp';
        $config->accessTokenManager = $accessTokenManager;
        $config->accountNameManager = $accountNameManager;

        return $config;
    }

}