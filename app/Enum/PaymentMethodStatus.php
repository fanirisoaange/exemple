<?php

namespace App\Enum;

class PaymentMethodStatus extends Enumeration {
    const VERIFIED = 1;
    const REFUSED = 2;
    
    protected static $descriptions
        = [
            self::VERIFIED => 'Verified',
            self::REFUSED   => 'Refused'
        ];
}
