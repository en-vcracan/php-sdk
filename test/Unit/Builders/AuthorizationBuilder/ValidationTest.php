<?php

namespace GlobalPayments\Api\Tests\Unit\Builders\AuthorizationBuilder;

use GlobalPayments\Api\Entities\Exceptions\BuilderException;
use GlobalPayments\Api\PaymentMethods\CreditCardData;
use GlobalPayments\Api\ServicesConfig;
use GlobalPayments\Api\ServicesContainer;
use PHPUnit\Framework\TestCase;

class ValidationTest extends TestCase
{
    protected $card;
    private $enableCryptoUrl = true;

    public function setUp(): void
    {
        $card = new CreditCardData();
        $card->number = '4111111111111111';
        $card->expMonth = 12;
        $card->expYear = 2025;
        $card->cvn = '123';
        $card->cardHolderName = 'Joe Smith';
        $this->card = $card;

        ServicesContainer::configure($this->getConfig());
    }

    public function testCreditAuthNoAmount()
    {
        $this->expectException(BuilderException::class);
        $this->expectExceptionMessage('amount cannot be null for this transaction type.');

        $this->card->authorize()
            ->execute();
    }

    public function testCreditAuthNoCurrency()
    {
        $this->expectException(BuilderException::class);
        $this->expectExceptionMessage("currency cannot be null");

        $this->card->authorize(14)
            ->execute();
    }

    public function testCreditAuthNoPaymentMethod()
    {
        $this->expectException(BuilderException::class);
        $this->expectExceptionMessage("paymentMethod cannot be null");

        $this->card->authorize(14)
            ->withCurrency('USD')
            ->withPaymentMethod(null)
            ->execute();
    }

    public function testCreditSaleNoAmount()
    {
        $this->expectException(BuilderException::class);
        $this->expectExceptionMessage("amount cannot be null");

        $this->card->charge()
            ->execute();
    }

    public function testCreditSaleNoCurrency()
    {
        $this->expectException(BuilderException::class);
        $this->expectExceptionMessage("currency cannot be null");

        $this->card->charge(14)
            ->execute();
    }

    public function testCreditSaleNoPaymentMethod()
    {
        $this->expectException(BuilderException::class);
        $this->expectExceptionMessage("paymentMethod cannot be null");

        $this->card->charge(14)
            ->withCurrency('USD')
            ->withPaymentMethod(null)
            ->execute();
    }

    protected function getConfig()
    {
        $config = new ServicesConfig();
        $config->secretApiKey = 'skapi_cert_MTeSAQAfG1UA9qQDrzl-kz4toXvARyieptFwSKP24w';
        $config->serviceUrl = ($this->enableCryptoUrl) ?
                              'https://cert.api2-c.heartlandportico.com/':
                              'https://cert.api2.heartlandportico.com';
        return $config;
    }
}
