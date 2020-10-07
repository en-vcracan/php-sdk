<?php


namespace GlobalPayments\Api\Gateways;

use GlobalPayments\Api\Builders\AuthorizationBuilder;
use GlobalPayments\Api\Builders\ManagementBuilder;
use GlobalPayments\Api\Builders\ReportBuilder;
use GlobalPayments\Api\Entities\Enums\TransactionType;
use GlobalPayments\Api\Entities\Exceptions\GatewayException;
use GlobalPayments\Api\Entities\GpApi\CreatePaymentMethodRequest;
use GlobalPayments\Api\Entities\GpApi\CreatePaymentRequest;
use GlobalPayments\Api\Entities\Transaction;
use GlobalPayments\Api\Utils\AccessTokenManager;
use GlobalPayments\Api\Utils\AccountNameManager;

class GpApiConnector extends RestGatewayWithCompression implements IPaymentGateway
{

    const PRODUCTION_ENV = 'https://apis.globalpay.com/ucp';
    const TEST_ENV = 'https://apis.sandbox.globalpay.com/ucp';
    const ACCESS_TOKEN_ENDPOINT = '/accesstoken';
    const TRANSACTION_ENDPOINT = '/transactions';
    const PAYMENT_METHODS_ENDPOINT = '/payment-methods';

    public $appId;
    public $appKey;

    public $apiVersion;
    /**
     * @var AccessTokenManager
     */
    public $accessTokenManager;

    /**
     * @var $accountNameManager AccountNameManager
     */
    public $accountNameManager;


    private function getConstantHeaders()
    {
        $accessToken = $this->accessTokenManager->getAccessToken();
        $headers = array(
            'X-GP-VERSION' => $this->apiVersion,
            'Authorization' => $accessToken->composeAuthorizationHeader()
        );

        return $headers;
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

    /**
     * Serializes and executes authorization transactions
     *
     * @param AuthorizationBuilder $builder The transaction's builder
     *
     * @return Transaction
     */
    public function processAuthorization(AuthorizationBuilder $builder)
    {
        if (
            $builder->transactionType == TransactionType::SALE ||
            $builder->transactionType == TransactionType::REFUND) {
            $transaction = CreatePaymentRequest::createFromAutorizationBuilder($builder, $this->accountNameManager);
            $response = $this->doTransaction(
                "POST",
                self::TRANSACTION_ENDPOINT,
                $transaction,
                null,
                $this->getConstantHeaders()
            );
        } elseif ($builder->transactionType == TransactionType::VERIFY) {
            $transaction = CreatePaymentMethodRequest::createFromAuthorizationBuilder(
                $builder,
                $this->accountNameManager
            );
            $response = $this->doTransaction(
                "POST",
                self::PAYMENT_METHODS_ENDPOINT,
                $transaction,
                null,
                $this->getConstantHeaders()
            );
        }

        return $response;
    }

    /**
     * Serializes and executes follow up transactions
     *
     * @param ManagementBuilder $builder The transaction's builder
     *
     * @return Transaction
     */
    public function manageTransaction(ManagementBuilder $builder)
    {
//        if ($builder->transactionType == TransactionType::VERIFY) {
//
//        }
    }

    public function processReport(ReportBuilder $builder)
    {
        // TODO: Implement processReport() method.
    }

    public function serializeRequest(AuthorizationBuilder $builder)
    {
        // TODO: Implement serializeRequest() method.
    }
}
