<?php

namespace App\Enum;

class CampaignCanalType extends Enumeration {
    const EMAIL = 1;
    const MOBILE = 2;

    protected static $descriptions
        = [
            self::EMAIL => 'email',
            self::MOBILE  => 'mobile',
        ];
}
