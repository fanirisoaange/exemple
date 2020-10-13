<?php

namespace App\Enum;

class AutoOwned extends Enumeration {
    const YES = 'yes';
    const NO = 'no';
    
    protected static $descriptions
        = [
            self::YES => 'YES',
            self::NO  => 'NO',
        ];
}
