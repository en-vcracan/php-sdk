<?php


namespace GlobalPayments\Api\Entities\GpApi;


use GlobalPayments\Api\Builders\AuthorizationBuilder;
use GlobalPayments\Api\Entities\Enums\GpApi\AccountNames;
use GlobalPayments\Api\Entities\Enums\GpApi\Channels;
use GlobalPayments\Api\Entities\Enums\GpApi\EntryMode;
use GlobalPayments\Api\Entities\GpApi\DTO\Card;
use GlobalPayments\Api\Entities\GpApi\DTO\PaymentMethod;
use GlobalPayments\Api\PaymentMethods\CreditCardData;

class CreatePaymentRequest
{
    public $account_name;
    public $channel;
    public $amount;
    public $currency;
    public $reference;
    public $country;

    public $capture_mode;
    public $type;
    public $description;
    public $order_reference;
    public $ip_address;
    /** @var $payment_method PaymentMethod */
    public $payment_method;

    public static function createFromAutorizationBuilder(AuthorizationBuilder $builder)
    {
        $paymentRequest = new CreatePaymentRequest();
        $paymentRequest->account_name = AccountNames::TRANSACTION_PROCESSING;
        $paymentRequest->channel = $builder->channel;
//        $paymentRequest->payment_method->first_name = $builder->customerData->firstName;
//        $paymentRequest->payment_method->last_name = $builder->customerData->lastName;
        $paymentRequest->reference = $builder->orderId;
        $paymentRequest->amount = (string)$builder->amount;
        $paymentRequest->currency = $builder->currency;
        $paymentRequest->country = $builder->country;
        /** @var CreditCardData $paymentMethod */
        $paymentMethod = $builder->paymentMethod;
        $card = new Card();
        $pm = new PaymentMethod();
        $name = explode(" ", $paymentMethod->cardHolderName);
        $pm->first_name = $name[0];
        $pm->last_name = $name[1];
        $pm->entry_mode = EntryMode::ECOM;
        $card->number = $paymentMethod->number;
        $card->expiry_month = $paymentMethod->expMonth;
        $card->expiry_year = substr(str_pad($paymentMethod->expYear, 4, '0', STR_PAD_LEFT), 2, 2);
        $card->cvv = $paymentMethod->cvn;
        $card->cvv_indicator = "PRESENT";
        $pm->card = $card;
        $paymentRequest->payment_method = $pm;
//        $paymentRequest->payment_method->card->number = $paymentMethod->number;
//        $paymentRequest->payment_method->card->expiry_month = $paymentMethod->expMonth;
//        $paymentRequest->payment_method->card->expiry_year = $paymentMethod->expYear;
        return $paymentRequest;
    }
}

/**
 * {
"account_name": "Transaction_Processing",
"type": "SALE",
"channel": "CNP",
"capture_mode": "AUTO",
"amount": "1999",
"currency": "USD",
"reference": "93459c78-f3f9-427c-84df-ca0584bb55bf",
"description": "SKU#BLK-MED-G123-GUC",
"order_reference": "INV#88547",
"country": "US",
"ip_address": "123.123.123.123",
"payment_method": {
"first_name": "James",
"last_name": "Mason",
"entry_mode": "ECOM",
"authentication": {
"xid": "vJ9NXpFueXsAqeb4iAbJJbe+66s=",
"cavv": "AAACBUGDZYYYIgGFGYNlAAAAAAA=",
"eci": "5"
},
"card": {
"number": "4263970000005262",
"expiry_month": "05",
"expiry_year": "25",
"cvv": "852",
"cvv_indicator": "PRESENT",
"avs_address": "Flat 123",
"avs_postal_code": "50001"
}
}
}
 */