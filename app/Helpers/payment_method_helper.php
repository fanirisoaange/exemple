<?php

use App\Enum\PaymentMethodStatus;

if ( ! function_exists('order_status')) {

    /**
     * Return status text for order
     *
     * @param int $orderStatus The database value of the status
     *
     * @return void
     */

    function payment_method_status(int $s)
    {
        $status = trad(
            PaymentMethodStatus::getDescriptionById(
                $s
            )
        );
        switch ($s) {
            case 1:
                echo '<span class="badge badge-success">'.$status.'</span>';
                break;
            case 2:
                echo '<span class="badge badge-danger">'.$status.'</span>';
                break;
            default:
                throw new Exception('invalid payment method status');
        }
    }
}
