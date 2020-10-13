<?php

namespace App\Enum;

class campaignNatureType extends Enumeration
{
    const INDIVIDUAL = 'individual';
    const COMPANY = 'company';

    protected static $descriptions
        = [
            self::INDIVIDUAL => 'individual',
            self::COMPANY => 'company',
        ];
}
