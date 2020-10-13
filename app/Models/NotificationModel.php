<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Enum\Notifications;

class NotificationModel extends Model {
    private $orderModel;
    private $userId;
    protected $db;

	public function __construct()
	{
		parent::__construct();

	    $this->ionAuth = new \IonAuth\Libraries\IonAuth();
	    $this->builder = $this->db->table("notifications");
        $this->db = \Config\Database::connect();
        $this->orderModel = model('OrderModel', TRUE, $this->db);
	}

    public function getLastNotificationDate()
    {
        $id = $this->ionAuth->getUserId();
        $last = $this->db->table('notifications')->select("done_at")->where(
                [
                    'id_user' => $id,
                    'is_seen' => 0,
                ]
            )->orderBy('id',"desc")->limit(1)->get()->getResultArray();
            
        return (is_null($last) || empty($last))?null:$last[0]['done_at'];
    }

	public function getLastNotification(): int
	{
		$id = $this->ionAuth->getUserId();

		return $this->builder->where(
			[
				'id_user' => $id,
				'is_seen' => 0,
			]
		)->countAllResults();
	}

	public function sendNotif(string $orderId, string $status): void
    {
        $companyTo = model("OrderModel")->selectOrder((int)$orderId, 'company_to')['company_to'];
        $users = model("UserModel")->getAccountantCompany((int)$companyTo, []);

        $date = new \DateTime();
        $date = $date->format('Y-m-d H:i:s');
        foreach($users as $user){
            $data = [
                "notification_type" => $status,
                "done_at"           => $date,
                "id_user"           => $user["id"],
                "target_id"         => $orderId,
                "is_seen"           => 0,
                "target_type"       => 1,
            ];
            
            $this->db->table('notifications')->insert($data);
        }
    }
    
    public function sendNotifToRecipient(string $orderId,  array $recipient, string $status): void
    {
        $date = new \DateTime();
        $date = $date->format('Y-m-d H:i:s');
        foreach($recipient as $user){
            $data = [
                "notification_type" => $status,
                "done_at"           => $date,
                "id_user"           => $user['id'],
                "target_id"         => $orderId,
                "is_seen"           => 0,
                "target_type"       => 1,
            ];
            
            $this->db->table('notifications')->insert($data);
        }
    }

    public function getAllNotification()
    {

        $id = $this->ionAuth->getUserId();
        $query1 =$this->db->table('notifications n')
            ->select("n.id, n.notification_type , n.done_at as date_notif, n.id_user as user, n.target_id as order_id, n.is_seen,  'sender' as sender, '' as subject, '' as content, '' as attachments, '' as trashed, '' as type, '' as favorite, 'notification' as notif_or_message, n.target_type")
        
        ->where('n.id_user', $id)
         ->getCompiledSelect(false);
        
        $query2 = $this->db->table('messages m')->select("m.id, 'message' as notification_type, m.send_at as date_notif, m.recipient_id as user, '0' as order_id, m.seen as is_seen, m.sender_id as sender, m.subject, m.content, m.attachments, m.trashed, m.type, m.favorite, 'message' as notif_or_message, '' as target_type ")->where('m.recipient_id', $id) 
         ->getCompiledSelect();        
        
        return $query = $this->db->query($query1 . ' UNION ' . $query2. ' ORDER BY date_notif DESC')->getResult();

    }

    public function sendEnvoiceNotif(int $invoiceId, int $companyto)
    {
        $users = model("UserModel")->getAccountantCompany($companyto, []);
        $date = new \DateTime();
        $date = $date->format('Y-m-d H:i:s');
        foreach($users as $user){
            $data = [
                "notification_type" => Notifications::INVOICE_STATUS_CHANGED_TO_ACCEPTED,
                "done_at"           => $date,
                "id_user"           => $user["id"],
                "target_id"         => $invoiceId,
                "is_seen"           => 0,
                "target_type"       => 2,
            ];
            
            $this->db->table('notifications')->insert($data);
        }
    }
}
