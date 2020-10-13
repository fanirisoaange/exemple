<?php

namespace App\Enum;

class ProductTypes extends Enumeration
{
    const EMAILING = 1;
    const SMS_MARKETING = 2;
    const TELEMARKETING = 3;
    const POSTAL_MAILING = 4;
    const EMAILING_REMINDER = 5;
    const SMS_MARKETING_REMINDER = 6;

    protected static $descriptions
        = [
            self::EMAILING               => 'Emailing',
            self::SMS_MARKETING          => 'SMS Marketing',
            self::TELEMARKETING          => 'Telemarketing',
            self::POSTAL_MAILING         => 'Postal Mailing',
            self::EMAILING_REMINDER      => 'Emailing Reminder',
            self::SMS_MARKETING_REMINDER => 'SMS Marketing Reminder',
        ];

    public static function getDescriptions(): array
    {
        return self::$descriptions;
    }
}
