<?php

namespace GlobalPayments\Api\PaymentMethods;

use GlobalPayments\Api\PaymentMethods\Interfaces\ITrackData;

class DebitTrackData extends Debit implements ITrackData
{
    public $entryMethod;
    public $value;
    public $track;
    public $tag;
    public $funding = 'DEBIT';
    public $chipCondition;
    public $pinBlock;
    public $brandReference;

    /**
     * Card holder name
     *
     * @var string
     */
    public $cardHolderName;
}
