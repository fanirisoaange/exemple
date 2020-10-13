<?php

use App\Enum\InvoiceStatus;
use App\Enum\OrderStatus;
use App\Enum\ProductPriceType;
use App\Enum\ProductServices;
use App\Enum\ProductTypes;
use App\Models\CompanyModel;
use App\Models\OrderModel;

if ( ! function_exists('order_status')) {

    /**
     * Return status text for order
     *
     * @param int $orderStatus The database value of the status
     *
     * @return void
     */
    function order_status(int $orderStatus)
    {
        $status = trad(
            OrderStatus::getDescriptionById(
                $orderStatus
            )
        );
        switch ($orderStatus) {
            case 1:
            case 5:
                echo '<span class="badge badge-success">'.$status.'</span>';
                break;
            case 2:
                echo '<span class="badge badge-warning">'.$status.'</span>';
                break;
            case 3:
                echo '<span class="badge badge-danger">'.$status.'</span>';
                break;
            case 4:
                echo '<span class="badge badge-draft">'.$status.'</span>';
                break;
            case 5:
                echo '<span class="badge badge-success">'.$status.'</span>';
                break;
            default:
                throw new Exception('invalid order status');
        }
    }

    function invoice_status(int $invoiceStatus)
    {
        $status = trad(
            InvoiceStatus::getDescriptionById(
                $invoiceStatus
            )
        );
        switch ($invoiceStatus) {
            case 1:
            case 2:
                echo '<span class="badge badge-success">'.$status.'</span>';
                break;
            case 3:
            case 4:
                echo '<span class="badge badge-danger">'.$status.'</span>';
                break;
            default:
                throw new Exception('invalid order status');
        }

    }   

    /**
     * Return the product list
     *
     * @return array
     */
    function get_product_list(): array
    {
        $productList = [];
        foreach (ProductTypes::getDescriptions() as $keyType=>$valueType) {
            foreach (ProductServices::getDescriptions() as $keyService=>$valueService) {
                $productList[$keyType.'-'.$keyService] = $valueType.' '.$valueService;
            }
        }

        return $productList;
    }

    /**
     * Return printing product data
     *
     * @return array
     */
    function get_product_print_data(array $productList, string $name): array
    {
        $out = [
            'is_custom' => true,
            'name'      => $name,
        ];
        foreach ($productList as $product) {
            $name = trim(strtolower($name));
            $normProduct = trim(strtolower($product));
            if ($normProduct == $name) {
                $out['is_custom'] = false;
                $out['name'] = $product;
                break;
            }
        }

        return $out;
    }

    function product_price_type(int $id)
    {
        return trad(ProductPriceType::getDescriptionById($id));
    }

    function getListCompanyToOrder(): array
    {
        $orderModel = new OrderModel();
        $id = $_SESSION['current_main_company'];
        if ( ! $id) {
            throw new Exception(trad('parameter main company is required'));
        }

        return $orderModel->getListCompanyToOrder($id);
    }
}
