<?php

namespace App\Enum;

class BlacklistFileStatus extends Enumeration {
    const ACTIF = 1;
    const SENT = 2;
    
    protected static $descriptions
        = [
            self::ACTIF => 'Actif',
            self::SENT  => 'Sent',
        ];
}
