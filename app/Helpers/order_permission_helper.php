<?php

use App\Enum\OrderStatus;

function canEditOrder(int $status)
{
    return (isAdmin() || isCardata())
        && ( ! in_array(
            $status,
            [
                OrderStatus::ACCEPTED,
                OrderStatus::PAID,
                OrderStatus::CANCELLED,
            ]
        ));
}

function canDraftOrder(int $status)
{
    return (isAdmin() || isCardata())
        && ($status == OrderStatus::PENDING);
}

function canCancelOrder(int $status)
{
    return isMemberAccounting() && $status == OrderStatus::ACCEPTED;
}

function canSendOrder(int $status)
{
    return (isAdmin() || isCardata())
        && ($status == OrderStatus::DRAFT);
}

function canValidateOrder(int $status)
{
    return isMemberAccounting() && $status == OrderStatus::PENDING;
}

function canViewOrder(int $status)
{
    return (isMemberAccounting())
        && ((isAdmin()) || (isCardata())
            || (isCustomer() && $status != OrderStatus::DRAFT));
}

function canDeleteOrder(int $status)
{
    return (isAdmin() || isCardata())
        && ($status == OrderStatus::DRAFT);
}

function canCreateOrder()
{
    return isAdmin() || isCardata();
}
