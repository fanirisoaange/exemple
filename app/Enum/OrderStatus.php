<?php

namespace App\Enum;

class OrderStatus extends Enumeration {
    const ACCEPTED = 1;
    const PENDING = 2;
    const CANCELLED = 3;
    const DRAFT = 4;
    const PAID = 5;
    
    protected static $descriptions
        = [
            self::ACCEPTED  => 'Accepted',
            self::PENDING   => 'Pending',
            self::CANCELLED => 'Cancelled',
            self::DRAFT     => 'Draft',
            self::PAID      => 'Paid'
        ];
}
