<?php


namespace GlobalPayments\Api\Entities\GpApi;


class AccessTokenRequest
{
    public $app_id;
    public $nonce;
    public $secret;
    public $grant_type;

    /**
     * AccessTokenRequest constructor.
     * @param $app_id
     * @param $nonce
     * @param $secret
     * @param $grant_type
     */
    public function __construct($app_id, $nonce, $secret, $grant_type)
    {
        $this->app_id = $app_id;
        $this->nonce = $nonce;
        $this->secret = $secret;
        $this->grant_type = $grant_type;
    }


}