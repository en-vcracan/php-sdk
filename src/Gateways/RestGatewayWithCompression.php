<?php


namespace GlobalPayments\Api\Gateways;


use GlobalPayments\Api\Entities\Exceptions\GatewayException;

abstract class RestGatewayWithCompression extends Gateway
{

    public function __construct()
    {
        parent::__construct('application/json');
    }

    /**
     * @param $verb
     * @param $endPoint
     * @param null $requestBody
     * @param null $queryStringParams
     * @param array $requestHeader
     * @return mixed
     * @throws GatewayException
     */
    protected function doTransaction(
        $verb,
        $endPoint,
        $requestBody = null,
        $queryStringParams = null,
        $requestHeader = array()
    ) {
        $requestBody = $requestBody ? json_encode($requestBody) : null;
        $response = $this->sendRequest($verb, $endPoint, $requestBody, $queryStringParams, $requestHeader);
        $parsedResponse = json_decode(gzdecode($response->rawResponse));

        if (!in_array($response->statusCode, [200, 204])) {
            $error = isset($parsedResponse->error) ? $parsedResponse->error : $parsedResponse;
            throw new GatewayException(
                sprintf(
                    'Status Code: %s - %s',
                    $error->error_code,
                    isset($error->detailed_error_description) ?
                        $error->detailed_error_description :
                        (isset($error->detailed_error_code) ? $error->detailed_error_code : (string)$error)
                )
            );
        }

        return $parsedResponse;
    }
}