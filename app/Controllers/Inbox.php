<?php

namespace App\Controllers;

use App\Libraries\Layout;
use App\Libraries\Utils;
use App\Libraries\Traductions;

class Inbox extends BaseController
{
    protected $companyModel;

    const PAGELENGTH = 15;

    public function __construct()
    {
        $this->inboxModel = model('InboxModel', true, $this->db);
        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
    }
    public function index()
    {
        $page = $this->request->getGet("page") ? $this->request->getGet("page") : 1;

        $totalCount = $this->inboxModel->getTotalMessageCount();
        $countUnread = $this->inboxModel->getUnreadMessagesCount();

        if ($totalCount <  ($page-1) * self::PAGELENGTH) {
            $page = 1;
        }

        if (!$this->ionAuth->user()) {
            return redirect()->to('/login');
        }

        $m = '';
        if (isset($_SESSION['message'])) {
            $m = $_SESSION['message'];
            unset($_SESSION['message']);
        }

        $db  = \Config\Database::connect();
        $builder = $db->table('messages');

        $messages = $this->inboxModel->getMessages($page);

         
        $currentCount = $page * self::PAGELENGTH > $totalCount ? $totalCount : $page * self::PAGELENGTH;

        $options = array(
            'title' => 'test',
            'metadescription' => 'Description trop bien',
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/inbox/index',
            'page_title' => 'Inbox',
            'message' => $m,
            'messages' => $messages,
            'page' => $page,
            'totalCount' => $totalCount,
            'currentCount' => $currentCount,
            'countUnread' => $countUnread,
            'type' => 'inbox'
        );
        $test = new Layout();
        $test->load_assets('default');
        $test->add_js([ASSETS . "js/inbox"]);
       

        echo $test->view('test_view', $options);
    }

    public function draft()
    {
        $page = $this->request->getGet("page") ? $this->request->getGet("page") : 1;

        $totalCount = $this->inboxModel->getTotalDraftMessageCount();
        $countUnread = $this->inboxModel->getUnreadMessagesCount();

        if ($totalCount <  ($page-1) * self::PAGELENGTH) {
            $page = 1;
        }

        if (!$this->ionAuth->user()) {
            return redirect()->to('/login');
        }

        $m = '';
        if (isset($_SESSION['message'])) {
            $m = $_SESSION['message'];
            unset($_SESSION['message']);
        }

        $db  = \Config\Database::connect();
        $builder = $db->table('messages');

        $messages = $this->inboxModel->getDraftMessages($page);

         
        $currentCount = $page * self::PAGELENGTH > $totalCount ? $totalCount : $page * self::PAGELENGTH;

        $options = array(
            'title' => 'test',
            'metadescription' => 'Draft page',
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/inbox/index',
            'page_title' => 'Draft',
            'message' => $m,
            'messages' => $messages,
            'page' => $page,
            'totalCount' => $totalCount,
            'currentCount' => $currentCount,
            'countUnread' => $countUnread,
            'type' => "draft"
        );
        $test = new Layout();
        $test->load_assets('default');
        $test->add_js([ASSETS . "js/inbox"]);
       

        echo $test->view('test_view', $options);
    }

    public function read($id)
    {
        if(!($uid = $this->inboxModel->getUserId()))
            return redirect()->to('/login');
      
        $mail = $this->inboxModel->getMail($id);
       
        if (!$mail->seen) {
            $this->inboxModel->mailSeen($mail->id);
        }

        $countUnread = $this->inboxModel->getUnreadMessagesCount();
        
        $mail->attachments = json_decode($mail->attachments);

        $options = array(
            'title' => 'test',
            'metadescription' => 'Description trop bien',
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/inbox/read',
            'page_title' => 'Inbox',
            'message' => $mail,
            'countUnread' => $countUnread,
            'type' => 'read'
        
        );
        $test = new Layout();
        $test->load_assets('default');
        $test->add_js([ASSETS . "js/inbox"]);
       
       

        echo $test->view('test_view', $options);
    }

    public function delete()
    {
        $ids = explode(";", $this->request->getPost("id"));
        foreach ($ids as $i) {
            if ($i) {
                $this->inboxModel->deleteOne($i);
            }
        }

        echo json_encode(array("status" => 200));
    }
    public function deleteOne($id)
    {
        $mail = $this->inboxModel->getMail($id);
        if (($mail->sender_id == $this->ionAuth->user()->row()->id && $mail->type == "sent") || ($mail->recipient_id == $this->ionAuth->user()->row()->id && $mail->type == "recipient")) {
            $this->inboxModel->deleteOne($id);
        }

        $this->session->set('message', "Message deleted.");
        return redirect()->to("/inbox");
    }
    public function putTrash()
    {
        $ids = explode(";", $this->request->getPost("id"));
        foreach ($ids as $i) {
            if ($i) {
                $this->inboxModel->putTrash($i);
            }
        }

        echo json_encode(array("status" => 200));
    }

    public function trash()
    {
        $page = $this->request->getGet("page") ? $this->request->getGet("page") : 1;

        $totalCount = $this->inboxModel->getTotalMessageCount();
        $countUnread = $this->inboxModel->getUnreadMessagesCount();

        if ($totalCount <  ($page-1) * self::PAGELENGTH) {
            $page = 1;
        }

        if (!$this->ionAuth->user()) {
            return redirect()->to('/login');
        }

        $m = '';
        if (isset($_SESSION['message'])) {
            $m = $_SESSION['message'];
            unset($_SESSION['message']);
        }

        $db  = \Config\Database::connect();
        $builder = $db->table('messages');

        $messages = $this->inboxModel->getTrashedMessages($page);

         
        $currentCount = $page * self::PAGELENGTH > $totalCount ? $totalCount : $page * self::PAGELENGTH;

        $options = array(
            'title' => 'test',
            'metadescription' => 'Description trop bien',
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/inbox/index',
            'page_title' => 'Trash',
            'message' => $m,
            'messages' => $messages,
            'page' => $page,
            'totalCount' => $totalCount,
            'currentCount' => $currentCount,
            'countUnread' => $countUnread,
            'type' => "trash"
        );
        $test = new Layout();
        $test->load_assets('default');
        $test->add_js([ASSETS . "js/inbox"]);
       

        echo $test->view('test_view', $options);
    }

    public function sent()
    {
        $page = $this->request->getGet("page") ? $this->request->getGet("page") : 1;

        $totalCount = $this->inboxModel->getSentMessagesCount();
        $countUnread = $this->inboxModel->getUnreadMessagesCount();

        if ($totalCount <  ($page-1) * self::PAGELENGTH) {
            $page = 1;
        }

        $m = '';
        if (isset($_SESSION['message'])) {
            $m = $_SESSION['message'];
            unset($_SESSION['message']);
        }

      
        $messages = $this->inboxModel->getSentMessages($page);

        $currentCount = $page * self::PAGELENGTH > $totalCount ? $totalCount : $page * self::PAGELENGTH;

        $options = array(
            'title' => 'test',
            'metadescription' => 'Description trop bien',
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/inbox/sent',
            'page_title' => 'Sent',
            'message' => $m,
            'page' => $page,
            'messages' => $messages,
            'totalCount' => $totalCount,
            'currentCount' => $currentCount,
            'countUnread' => $countUnread,
            'type' => 'sent'
        );
        $test = new Layout();
        $test->load_assets('default');
        $test->add_js([LIBRARY . "toastr/toastr.min.js",
                         ASSETS . "js/inbox"]);
       
       

        echo $test->view('test_view', $options);
    }

    public function refresh() {
        switch($this->request->getGet("type")) {
            case "inbox":
                return json_encode($this->inboxModel->getMessages(1));
            break;
            case "sent":
                return json_encode($this->inboxModel->getSentMessages(1));
            break;
            case "trash":
                return json_encode($this->inboxModel->getTrashedMessages(1));
            break;
            case "draft":
                return json_encode($this->inboxModel->getDraftMessages(1));
            break;
        }
    }

    public function search() {
        return json_encode($this->inboxModel->searchMessages($this->request->getGet("q"), 1, 'inbox'));
    }
    public function compose($id = 0)
    {
        $countUnread = $this->inboxModel->getUnreadMessagesCount();

        $mail = $id ? $this->inboxModel->getMail($id) : null;
        $to = $mail ? $mail->recipient_id : null;

        if($mail && $mail->attachments) {
            $mail->att_string = $mail->attachments;
            $mail->attachments = json_decode($mail->attachments);
        }
        $options = array(
            'title' => 'test',
            'metadescription' => 'Compose message',
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/inbox/compose',
            'page_title' => 'Compose',
            'contacts' =>  $this->inboxModel->getContacts(),
            'countUnread' => $countUnread,
            'mail' => $mail,
            'to' => $to,
            'type' => 'compose'
        );
        $test = new Layout();
        $test->load_assets('default');
        $test->add_css([
            LIBRARY . 'summernote/summernote-bs4'
          
        ]);
        $test->add_js([
            LIBRARY . 'summernote/summernote-bs4.min',
            LIBRARY . 'select2/js/select2.full.js',
            ASSETS . 'js/compose'
        ]);

        echo $test->view('test_view', $options);
    }

    public function replyTo($id)
    {
        $countUnread = $this->inboxModel->getUnreadMessagesCount();

        $mail = $this->inboxModel->getMail($id);
        $mail->content = "<br><br><hr>" . $mail->content;
        $mail->subject = "RE: " . $mail->subject;
        $mail->attachments = [];
        
        $options = array(
            'title' => 'test',
            'metadescription' => 'Compose message',
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/inbox/compose',
            'page_title' => 'Compose',
            'contacts' =>  $this->inboxModel->getContacts(),
            'mail' => $mail,
            'countUnread' => $countUnread,
            'to' => $mail->sender_id,
            'type' => 'compose'
        );
        $test = new Layout();
        $test->load_assets('default');
        $test->add_css([
            LIBRARY . 'summernote/summernote-bs4'
          
        ]);
        $test->add_js([
            LIBRARY . 'summernote/summernote-bs4.min',
            LIBRARY . 'select2/js/select2.full.js',
            ASSETS . 'js/compose'
        ]);

        echo $test->view('test_view', $options);
    }

    public function setFavorite() {
        //check permissions 

        $this->inboxModel->setFavorite($this->request->getPost("id"), $this->request->getPost("fav"));
        echo json_encode(["success" => 200]);

    }
    public function forward($id)
    {
        $countUnread = $this->inboxModel->getUnreadMessagesCount();

        $mail = $this->inboxModel->getMail($id);
        $mail->sender_id = 0;
        $mail->content = "From: " . $mail->sender . "<br><br><hr><br>" . $mail->content;
        $mail->subject = "FW: " . $mail->subject;
        if($mail->attachments) {
            $mail->att_string = $mail->attachments;
            $mail->attachments = json_decode($mail->attachments);
        }


        $options = array(
            'title' => 'test',
            'metadescription' => 'Compose message',
            'content_only' => false,
            'no_js' => false,
            'nofollow' => true,
            'top_content' => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content' => 'layout/inbox/compose',
            'page_title' => 'Compose',
            'contacts' =>  $this->inboxModel->getContacts(),
            'mail' => $mail,
            'countUnread' => $countUnread,
            'to' => $mail->sender_id,
            'type' => 'compose'
        );
        $test = new Layout();
        $test->load_assets('default');
        $test->add_css([
            LIBRARY . 'summernote/summernote-bs4'
          
        ]);
        $test->add_js([
            LIBRARY . 'summernote/summernote-bs4.min',
            LIBRARY . 'select2/js/select2.full.js',
            ASSETS . 'js/compose'
        ]);

        echo $test->view('test_view', $options);
    }

    public function send()
    {
        $id = $this->ionAuth->user()->row()->id;
       

        $db  = \Config\Database::connect();
      

        $fields = $this->request->getPost();

        $builder = $db->table('messages');

        $to = implode(";", $fields["to"]);

   

        $attachments = isset($fields['attachment']) ? json_decode($fields['attachment']) : array();

        
        $ids = array();

        switch ($fields["method"]) {
                case "send":

                    foreach ($fields["to"] as $t) {
                        $data = [
                            'sender_id' => $id,
                            'recipient_id' => $t,
                            'subject' => $fields["subject"],
                            'content'  => $fields['content'],
                            'send_at'  => date("Y-m-d H:i:s"),
                            'type' => 'recipient'
                            ];
                        if(isset($fields['attachment']))
                            $data['attachments'] = $fields['attachment'];

                        $builder->insert($data);
                        array_push($ids, $this->db->insertId());
                    }

                    if(!isset($fields['mid'])) {

                        $data = [
                                'sender_id' => $id,
                                'recipient_id' => $to,
                                'subject' => $fields["subject"],
                                'content'  => $fields['content'],
                                'send_at'  => date("Y-m-d H:i:s"),                 
                                'type' => 'sent'
                        ];
                        if(isset($fields['attachment']))
                            $data['attachments'] = $fields['attachment'];

                        $builder->insert($data);

                        $rid = $this->db->insertId();
                        array_push($ids, $rid);
                    }
                    else {
                        $this->inboxModel->sendDraft($fields['mid'], $to, $fields['subject'], $fields['content']);
                        $rid = $fields['mid'];
                        array_push($ids, $fields['mid']);
                    }
                    $this->session->set('message', "Message sent successfully!");
                break;
               ;
                case "draft":
                      
                    $data = [
                            'sender_id' => $id,
                            'recipient_id' => $to,
                            'subject' => $fields["subject"],
                            'content'  => $fields['content'],
                            'send_at'  => date("Y-m-d H:i:s"),
                            'type' => 'draft',
                            'seen' => 1
                        ];

                    $builder->insert($data);
                    $rid = $this->db->insertId();
                    array_push($ids, $rid);
                    $this->session->set('message', "Message saved to draft.");
                break;
        }

        if($files = $this->request->getFiles())
            {
                if(count($files['attachments']) > 1) {

                  

                    if(!file_exists('uploads/mailbox'))
                        mkdir('uploads/mailbox');

                     if(!file_exists('uploads/mailbox/' . $rid))
                        mkdir('uploads/mailbox/' . $rid);

                   foreach($files['attachments'] as $img)
                   {
                      if ($img->isValid() && ! $img->hasMoved())
                      {
                           $newName = $img->getRandomName();
                           
                           $img->move('uploads/mailbox/' . $rid, $newName);
                           array_push($attachments, array("name" => $img->getClientName(), "url"=> base_url().'/uploads/mailbox/' . $rid . "/" .$newName, "type" => $img->getExtension(), "size" => $this->inboxModel->human_filesize(filesize('uploads/mailbox/' . $rid . "/" . $newName))));
                      }
                   }

                   foreach($ids as $i) {
                            $this->inboxModel->setAttachments($i, json_encode($attachments));
                    }
                }
        }

        return redirect()->to("/inbox");
    }    
    
}
