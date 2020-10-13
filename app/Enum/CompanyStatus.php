<?php

namespace App\Enum;

class CompanyStatus extends Enumeration {
    const ACTIF = 1;
    const INACTIF = 2;
    
    protected static $descriptions
        = [
            self::ACTIF   => 'actif',
            self::INACTIF => 'inactif',
        ];
}
