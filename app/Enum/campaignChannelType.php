<?php

namespace App\Enum;

class campaignChannelType extends Enumeration
{
    const EMAIL = 1;
    const SMS = 2;
    const TELEMARKETING = 3;
    const MAILING = 4;

    protected static $descriptions
        = [
            self::EMAIL => 'email',
            self::SMS => 'sms',
            self::TELEMARKETING => 'telemarketing',
            self::MAILING => 'mailing',
        ];
}
