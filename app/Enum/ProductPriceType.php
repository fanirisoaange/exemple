<?php

namespace App\Enum;

class ProductPriceType extends Enumeration {
    const CPM = 1;
    const CPC = 2;
    const CPL = 3;
    const TJM = 4;
    const FORFAIT = 5;
    
    protected static $descriptions
        = [
            self::CPM     => 'CPM',
            self::CPC     => 'CPC',
            self::CPL     => 'CPL',
            self::TJM     => 'TJM',
            self::FORFAIT => 'FORFAIT'
        ];

    public static function getDescriptions()
    {
        return [
            self::CPM     => 'CPM',
            self::CPC     => 'CPC',
            self::CPL     => 'CPL',
            self::TJM     => 'TJM',
            self::FORFAIT => 'FORFAIT'
        ];
    }
}
