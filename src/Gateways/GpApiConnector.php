<?php


namespace GlobalPayments\Api\Gateways;

use GlobalPayments\Api\Builders\AuthorizationBuilder;
use GlobalPayments\Api\Builders\ManagementBuilder;
use GlobalPayments\Api\Builders\ReportBuilder;
use GlobalPayments\Api\Entities\Exceptions\GatewayException;
use GlobalPayments\Api\Entities\Transaction;
use GlobalPayments\Api\Utils\AccessTokenManager;

class GpApiConnector extends RestGatewayWithCompression implements IPaymentGateway
{

    const PRODUCTION_ENV = 'https://apis.globalpay.com/ucp';
    const TEST_ENV = 'https://apis.sandbox.globalpay.com/ucp';
    const ACCESS_TOKEN_ENDPOINT = '/accesstoken';

    public $appId;
    public $appKey;

    public $apiVersion;
    /**
     * @var AccessTokenManager
     */
    public $accessTokenManager;


    private function getConstantHeaders()
    {
        $headers = array(
          'X-GP-VERSION' => $this->apiVersion
        );

        $headers = array_merge($headers, [
           'Authorization' => ''
        ]);

        return [
//            'Content-type' => 'application/json',
            'X-GP-VERSION' => self::API_VERSION
        ];
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
        // TODO: Implement processAuthorization() method.
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
        // TODO: Implement manageTransaction() method.
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
