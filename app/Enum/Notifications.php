<?php

namespace App\Enum;

class Notifications extends Enumeration
{
    const ORDER_STATUS_CHANGED_TO_PENDING = 1;
    const ORDER_STATUS_CHANGED_TO_ACCEPTED = 2;
    const ORDER_STATUS_CHANGED_TO_PAID = 3;
    const ORDER_STATUS_CHANGED_TO_CANCELED = 4;
    const INVOICE_STATUS_CHANGED_TO_ACCEPTED = 10;
    const INVOICE_STATUS_CHANGED_TO_CANCELED = 11;
    const INVOICE_STATUS_CHANGED_TO_PAID = 12;

    protected static $descriptions
        = [
            self::ORDER_STATUS_CHANGED_TO_PENDING    => 'new pending order',
            self::ORDER_STATUS_CHANGED_TO_ACCEPTED   => 'new accepted order',
            self::ORDER_STATUS_CHANGED_TO_PAID       => 'new paid order',
            self::ORDER_STATUS_CHANGED_TO_CANCELED   => 'new canceled order',
            self::INVOICE_STATUS_CHANGED_TO_ACCEPTED => 'new accepeted invoice',
            self::INVOICE_STATUS_CHANGED_TO_CANCELED => 'new canceled invoice',
            self::INVOICE_STATUS_CHANGED_TO_PAID     => 'new paid invoice',
        ];
}
