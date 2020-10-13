<?php

namespace App\Enum;

class UserGroupType extends Enumeration {

    const ADMIN = 1;
    const CARDATA_ADMIN = 2;
    const CARDATA_MEMBER = 3;
    const HEAD_ADMIN = 10;
    const HEAD_ACCOUNTING = 11;
    const HEAD_MEMBER = 12;
    const ZONE_ADMIN = 20;
    const ZONE_ACCOUNTING = 21;
    const ZONE_MEMBER = 22;
    const MEMBER_ADMIN = 30;
    const MEMBER_ACCOUTING = 31;
    const MEMBER_MEMBER = 32;
    const GEST = 40;
    
    protected static $descriptions
        = [
            self::ADMIN => 'Administrator',
            self::CARDATA_ADMIN    => 'Cardata Administrator',
            self::CARDATA_MEMBER    => 'Cardata member',
            self::HEAD_ADMIN    => 'Head Administrator',
            self::HEAD_ACCOUNTING    => 'Head Accounting',
            self::HEAD_MEMBER    => 'Head Member',
            self::ZONE_ADMIN    => 'Zone Administrator',
            self::ZONE_ACCOUNTING    => 'Zone Accounting',
            self::ZONE_MEMBER    => 'Zone Member',
            self::MEMBER_ADMIN    => 'Member Administrator',
            self::MEMBER_ACCOUTING    => 'Member Accounting',
            self::MEMBER_MEMBER    => 'Member Member',
            self::GEST    => 'guest Guest',
        ];
}
