<?php

namespace App\Controllers;

use App\Libraries\Layout;
use App\Libraries\Utils;
use App\Libraries\Traductions;
use App\Enum\Notifications;

class Notification extends BaseController
{
	public function __construct()
    {
        $this->inboxModel = model('InboxModel', true, $this->db);
        $this->NotificationModel = model('NotificationModel', true, $this->db);
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
    }

    public function NotificationUnreadCount() {
        return json_encode(
            array(
                "count" => $this->inboxModel->getUnreadMessagesCount(),
                "lastMessage" => $this->inboxModel->getLastMessage(),
                "lastNotif" => $this->NotificationModel->getLastNotificationDate(),
                "lastOrder" => $this->NotificationModel->getLastNotification(),
            )
        );
    }

    public function all(){
        $notification = $this->NotificationModel->getAllNotification();
       
        $options = [
            'title'           => trad('All notification'),
            'metadescription' => trad('All notification'),
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
                    'Notification'
                ),
            'notification'           => $notification,
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

        return $layout->view('notification/all', $options);
    }
}
