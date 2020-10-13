<?php

namespace App\Controllers;

use App\Libraries\Layout;
use Exception;
use \App\Enum\OrderStatus;
use \App\Enum\Notifications;
use Spipu\Html2Pdf\Html2Pdf;

class Order extends BaseController {

	private $orderModel;

	public function __construct()
	{
		$this->orderModel = model('OrderModel', TRUE, $this->db);
        $this->notificationModel = model('NotificationModel', TRUE, $this->db);
		helper(['order']);
	}

	public function list()
	{
        if ( ! isMemberAccounting())
		{
			return accessDenied();
		}

		$id = isset($_SESSION['current_main_company'])
			? $_SESSION['current_main_company'] : NULL;
		$orders = [];
		if (isset($_SESSION['current_sub_company'])) {
			$orders = $this->orderModel->getOrdersByCompany(
				$_SESSION['current_sub_company'],
				'order.id, order.order_at, order.progress_status, order.company_to'
			);
		}
		$options = [
			'title'           => trad('Order List'),
			'metadescription' => trad('List of orders', 'user'),
			'content_only'    => FALSE,
			'no_js'           => FALSE,
			'nofollow'        => TRUE,
			'top_content'     => [
				'layout/default/header',
				'layout/default/sidebar',
			],
			'bottom_content'  => 'layout/default/footer',
			'content'         => 'layout/default/content',
			'page_title'      => '<i class="fas fa-chart-pie"></i>'.trad(
					' Order List'
				),
			'user_modal'      => view('users/modal'),
			'orders'          => $orders,
			'companyId'       => $id,
		];

		$layout = new Layout();
		$layout->load_assets('default');
		$layout->add_css(
			[
				LIBRARY.'datatables-bs4/css/dataTables.bootstrap4.min',
				LIBRARY.'datatables-responsive/css/responsive.bootstrap4.min',
			]
		);
		$layout->add_js(
			[
				LIBRARY.'datatables/jquery.dataTables.min',
				LIBRARY.'datatables-bs4/js/dataTables.bootstrap4.min',
				LIBRARY.'datatables-responsive/js/dataTables.responsive.min',
				LIBRARY.'datatables-responsive/js/responsive.bootstrap4.min',
				ASSETS.'js/orders',
                ASSETS.'js/helper',
			]
		);

		return $layout->view('order/list', $options);
	}

	public function detail(int $id)
	{
        $order = $this->orderModel->selectOrder($id, 'order.progress_status');
        if (!canViewOrder((int)$order['progress_status']))
		{
			return accessDenied();
		}

		$options = [
			'title'           => trad('Order detail'),
			'metadescription' => trad('Order detail'),
			'content_only'    => FALSE,
			'no_js'           => FALSE,
			'nofollow'        => TRUE,
			'top_content'     => [
				'layout/default/header',
				'layout/default/sidebar',
			],
			'bottom_content'  => 'layout/default/footer',
			'content'         => 'layout/default/content',
			'page_title'      => '<i class="fas fa-chart-pie"></i>'.trad(
					'Order detail'
				),
			'order'           => $this->orderModel->getOrderDetail(
			    $id,
                'order.id, order.order_at, order.accepted_at, order.progress_status, order.company_to, '.
                'c.fiscal_name, c.commercial_name, c.address_1, c.address_2, c.address_1, c.city, c.zip_code, c.city_display'
            ),
		];

		$layout = new Layout();
		$layout->load_assets('default');
		$layout->add_js(
			[
				ASSETS.'js/orders',
			]
		);

		return $layout->view('order/detail', $options);
	}

	public function validateOrder(int $id)
    {
        $order = $this->orderModel->selectOrder($id, 'order.progress_status');
        if (!canValidateOrder((int)$order['progress_status'])) {
            return accessDenied();
        }

        $this->orderModel->validateOrder($id);
        return json_encode(['message' => trad('Order updated successfully')]);
    }

    public function cancel(int $id)
    {
        $order = $this->orderModel->selectOrder($id, 'order.progress_status');
        if (!canCancelOrder((int)$order['progress_status'])) {
            return accessDenied();
        }
        try {
            $this->orderModel->candelOrder($id);
            return json_encode(['message' => trad('Order canceled successfully')]);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function draft(int $id)
    {
        $order = $this->orderModel->selectOrder($id, 'order.progress_status');
        if (!canDraftOrder((int)$order['progress_status'])) {
            return accessDenied();
        }

        $this->orderModel->draftOrder($id);

        return json_encode(['message' => trad('Order draft successfully')]);
    }

    public function send(int $id)
    {
        $order = $this->orderModel->selectOrder($id, 'order.progress_status, order.company_to');
        if (!canSendOrder((int)$order['progress_status'])) {
            return accessDenied();
        }

        $recipients = model('UserModel')->getAccountantCompany($order['company_to'], []);
        $this->orderModel->sendOrder($id);
        $this->notificationModel->sendNotifToRecipient($id, $recipients, Notifications::ORDER_STATUS_CHANGED_TO_PENDING);
        echo json_encode(['message' => trad('Order sent successfully')]); 
    }

    public function delete(int $id)
    {
        $order = $this->orderModel->selectOrder($id, 'order.progress_status');
        if (!canDeleteOrder((int)$order['progress_status'])) {
            return accessDenied();
        }
        try {
            $this->orderModel->deleteOrder($id);
            return json_encode(['message' => trad('Order deleted successfully')]);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function create()
    {
        if (!canCreateOrder()) {
            return accessDenied();
        }
        if ($this->request->isAJAX()) {
            $data = json_decode($this->request->getPost('data'));
            if (count($data->products) > 0) {
                $order = $this->orderModel->createOrder($data);
                return json_encode(
                    [
                        'orderId' => $order['id'],
                        'message' => trad('Order created successfully'),
                    ]
                );
            } else {
                throw new Exception(trad('invalid data parameter'));
            }
        }
        $data = [
            'top_content' => [
                'layout/default/header',
                'layout/default/sidebar'
            ],
            'bottom_content' => 'layout/default/footer',
            'content'        => 'layout/default/content',
        ];
        $this->layout = new Layout();
        $this->layout->load_assets('default');
        $this->layout->add_js(ASSETS . 'js/visualsLib');
        $this->layout->add_js(
            [
                ASSETS.'js/orders',
                ASSETS.'js/control-fields',
                ASSETS.'js/helper',
            ]
        );
        $data += [
            'page_title' => '<i class="nav-icon fas fa-file-invoice"></i> Create order',
            'breadcrumb' => [
                '/order/list' => trad('Order List'),
                '' => trad('Create order')
            ]
        ];

        return $this->layout->view('order/create_order', $data);
    }

    public function update($id)
    {
        $order = $this->orderModel->selectOrder($id, 'order.progress_status');
        if (!canEditOrder((int)$order['progress_status'])) {
            return accessDenied();
        }
        if ($this->request->isAJAX()) {
            $data = json_decode($this->request->getPost('data'));
            $order = $this->orderModel->updateOrder($id, $data);

            return json_encode(
                [
                    'orderId' => $order['id'],
                    'message' => trad('Order updated successfully'),
                ]
            );
        }

        throw new Exception('invalid request');
    }

    public function edit($id)
    {
        $order = $this->orderModel->selectOrder($id, 'order.progress_status');
        if (!canEditOrder((int)$order['progress_status'])) {
            return accessDenied();
        }

        $data = [
            'top_content' => [
                'layout/default/header',
                'layout/default/sidebar'
            ],
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/default/content',
            'order'           => $this->orderModel->getOrderDetail(
                $id,
                'order.id, order.order_at, order.progress_status,'.
                'c.id as companyTo, c.fiscal_name, c.commercial_name, c.address_1, phone_number, email, c.address_2, c.address_1, c.city, c.zip_code, c.city_display'
            )
        ];
        $this->layout = new Layout();
        $this->layout->load_assets('default');
        $this->layout->add_js(ASSETS . 'js/visualsLib');
        $this->layout->add_js(
            [
                ASSETS.'js/orders',
                ASSETS.'js/control-fields',
            ]
        );
        $data += [
            'page_title' => '<i class="nav-icon fas fa-file-invoice"></i> Edit order',
            'breadcrumb' => [
                '/order/list' => trad('Order List'),
                '' => trad('Edit order')
            ]
        ];

        return $this->layout->view('order/edit_order', $data);
    }

    public function getCompanyByAjax(int $id)
    {
        return json_encode(model('CompanyModel')->selectCompany($id));
    }

    public function pdf(int $id = 0)
    {
        $order = $this->orderModel->selectOrder($id, 'order.progress_status');
        if(!canViewOrder((int)$order['progress_status'])) {
            return accessDenied();
        }

        if(!$id)
            return redirect()->to(base_url() . '/order');

        $data = ['order' => $this->orderModel->get($id)];
        $content = view('order/pdf', $data);
        $pdf = new Html2Pdf("p","A4","fr");
        $pdf->pdf->SetAuthor('Cardata');
        $pdf->pdf->SetTitle('Order ' . $id);
        $pdf->writeHTML($content);
        $pdf->Output('Order' . $id . '.pdf', 'D');
        die;
    }
}
