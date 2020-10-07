<?php


namespace GlobalPayments\Api\Entities\GpApi\DTO;


class CardNotPresent
{
    public $number;
    public $expiry_month;
    public $expiry_year;
    public $cvv;
    public $cvv_indicator;
    public $avs_address;
    public $avs_postal_code;
}