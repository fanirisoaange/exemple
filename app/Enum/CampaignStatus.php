<?php

namespace App\Enum;

class CampaignStatus extends Enumeration
{
    const CREATE = 1;
    const CHANNEL = 2;
    const SEGMENTATION = 3;
    const CONTENT = 4;
    const PLANNING = 5;
    const VALIDATION = 6;
    const VALIDATED = 7;
    const CAMPAIGN_SUBMITTED = -1;

    protected static $descriptions
        = [
            self::CREATE              => 'create',
            self::CHANNEL             => 'channel',
            self::SEGMENTATION        => 'segmentation',
            self::CONTENT             => 'content',
            self::PLANNING            => 'planning',
            self::VALIDATION          => 'validation',
            self::VALIDATED           => 'validated',
            self::CAMPAIGN_SUBMITTED  => 'campaign_submitted',
        ];
}
