<?php


namespace GlobalPayments\Api\Entities\GpApi\DTO;


class PaymentMethod
{
    public $first_name;
    public $last_name;
    public $entry_mode;
    /** @var $authentication Authentication */
    public $authentication;
    /** @var $card CardNotPresent|CardPresent */
    public $card;
}