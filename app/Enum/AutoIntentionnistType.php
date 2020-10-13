<?php

namespace App\Enum;

class AutoIntentionnistType extends Enumeration {
    const YES = 1;
    const NO = 2;
    
    protected static $descriptions
        = [
            self::YES => 'YES',
            self::NO  => 'NO',
        ];
}
