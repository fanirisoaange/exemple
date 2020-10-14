<?php

namespace App\Enum;

class VisualCategories extends Enumeration
{
    const EMAIL = 1;
    const SMS = 2;

    protected static $descriptions
        = [
            self::EMAIL => 'Email',
            self::SMS => 'SMS',
        ];

    public static function getDescriptions(): array
    {
        return self::$descriptions;
    }
}
