<?php

namespace GlobalPayments\Api\Terminals\Enums;

use GlobalPayments\Api\Entities\Enum;

class SafUpload extends Enum
{

    const NEWLY_STORED_TRANSACTION = "0";

    const FAILED_TRANSACTION = "1";

    const ALL_TRANSACTION = "2";
}
