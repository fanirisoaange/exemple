<?php

namespace App\Enum;

class civilityType extends Enumeration
{
    const HOMME = 'm';
    const FEMME = 'f';

    protected static $descriptions
        = [
            self::HOMME => 'man',
            self::FEMME => 'woman',
        ];
}
