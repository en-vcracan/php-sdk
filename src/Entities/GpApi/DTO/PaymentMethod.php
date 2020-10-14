<?php


namespace GlobalPayments\Api\Entities\GpApi\DTO;


class PaymentMethod
{
    public $id;
    public $first_name;
    public $last_name;
    public $entry_mode;
    /** @var $authentication Authentication */
    public $authentication;
    /** @var $card CardNotPresent|CardPresent */
    public $card;
}