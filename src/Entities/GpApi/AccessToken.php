<?php


namespace GlobalPayments\Api\Entities\GpApi;


class AccessToken
{
    public $token;
    public $type;
    public $time_created;
    public $seconds_to_expire;
}