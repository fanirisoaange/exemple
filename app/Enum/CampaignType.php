<?php

namespace App\Enum;

class CampaignType extends Enumeration {
    const PERFORMANCE = 1;
    const PREMIUM = 2;

    protected static $descriptions
        = [
            self::PERFORMANCE => 'performance',
            self::PREMIUM  => 'premium',
        ];
}
