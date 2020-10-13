<?php

namespace App\Enum;

class InvoiceStatus extends Enumeration {
    const PAID = 1;
    const ACCEPTED = 2;
    const CANCELLED = 3;
    const INVALID_PAYMENT_METHOD = 4;

    protected static $descriptions
        = [
            self::PAID  => 'Paid',
            self::ACCEPTED   => 'Accepted',
            self::CANCELLED => 'Cancelled',
            self::INVALID_PAYMENT_METHOD => 'Invalid payment method'
        ];
}
