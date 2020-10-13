<?php

namespace App\Enum;

class campaignChannelStatus extends Enumeration
{
    const OPENED = 1;
    const NOT_OPENED = 2;
    const CLICKED = 3;
    const NOT_CLICKED = 4;

    protected static $descriptions
        = [
            self::OPENED       => 'opened',
            self::NOT_OPENED      => 'not opened',
            self::CLICKED => 'clicked',
            self::NOT_CLICKED      => 'not clicked',
        ];
}
