<?php

namespace App\Enum;

class CompanyType extends Enumeration {
    const NATIONAL = 1;
    const LOCAL = 2;
    
    protected static $descriptions
        = [
            self::NATIONAL => 'national',
            self::LOCAL    => 'local',
        ];
}
