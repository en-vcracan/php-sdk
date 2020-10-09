<?php


namespace GlobalPayments\Api\Entities\GpApi;


use GlobalPayments\Api\Builders\AuthorizationBuilder;
use GlobalPayments\Api\Entities\GpApi\DTO\CardNotPresent;
use GlobalPayments\Api\PaymentMethods\CreditCardData;
use GlobalPayments\Api\Utils\AccountNameManager;
use GlobalPayments\Api\Utils\GenerationUtils;

class CreatePaymentMethodRequest
{
    public $account_name;
    public $reference;
    public $name;
    /**
     * @var $card CardNotPresent
     */
    public $card;

    public static function createFromAuthorizationBuilder(
        AuthorizationBuilder $builder,
        AccountNameManager $accountNameManager
    ) {
        $createPaymentMethodRequest = new CreatePaymentMethodRequest();
        $associatedCard = new CardNotPresent();
//        $associatedCard = new \stdClass();
        /** @var CreditCardData $builderCard */
        $builderCard = $builder->paymentMethod;
        $associatedCard->number = $builderCard->number;
        $associatedCard->expiry_month = (string)$builderCard->expMonth;
        $associatedCard->expiry_year = substr(str_pad($builderCard->expYear, 4, '0', STR_PAD_LEFT), 2, 2);;

        $createPaymentMethodRequest->account_name = $accountNameManager->tokenizationAccount;
        $createPaymentMethodRequest->name = $builder->description ? $builder->description : null;
//        $createPaymentMethodRequest->name = null;
        $createPaymentMethodRequest->reference = $builder->clientTransactionId ?
            $builder->clientTransactionId : GenerationUtils::generateOrderId();
        $createPaymentMethodRequest->card = $associatedCard;


        return $createPaymentMethodRequest;
    }

}

/**
 * {
 * "account_name": "Tokenization",
 * "reference": "card_default_1",
 * "name": "string",
 * "card": {
 * "number": "4263970000005262",
 * "expiry_month": "12",
 * "expiry_year": "25"
 * }
 * }
 */