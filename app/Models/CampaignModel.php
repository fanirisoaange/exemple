<?php

namespace App\Models;

use App\Enum\CampaignTag;
use App\Enum\ModelEconomic;
use CodeIgniter\Model;
use App\Enum\CampaignStatus;
use App\Enum\campaignChannelType;
use App\Enum\campaignChannelStatus;
use App\Enum\CampaignType;
use App\Enum\AutoIntentionnistType;
use App\Enum\AutoOwned;
use App\Enum\CampaignCanalType;
use DateTime;
use Exception;

class CampaignModel extends Model
{
    private $segmentationModel;

    public function __construct()
    {
        parent::__construct();
        $this->segmentationModel = model('SegmentationModel', TRUE, $this->db);
    }

    public function selectCampaign($campaignId, $columns = '*')
    {
        $builder = $this->db->table('campaigns');

        return $builder->select($columns)
                        ->getWhere(['id' => $campaignId])
                        ->getRowArray();
    }

    public function selectChannel($channelId, $columns = '*')
    {
        $builder = $this->db->table('campaign_to_channel');

        return $builder->select($columns)
                        ->getWhere(['id' => $channelId])
                        ->getRowArray();
    }

    public function selectChannelsByCampaign($campaignId, $columns = '*')
    {
        $builder = $this->db->table('campaign_to_channel');
        return $builder->select($columns)
                        ->where('campaign_id', $campaignId)
                        ->orderBy('level ASC, channel_id ASC')
                        ->get()
                        ->getResultArray();
    }

    public function selectVisual($visualId, $columns = 'campaign_to_visual.*, campaign_to_channel.channel_id AS channel_type')
    {
        $builder = $this->db->table('campaign_to_visual');

        return $builder->select($columns)
                        ->join('campaign_to_channel', 'campaign_to_channel.id=campaign_to_visual.channel_id')
                        ->getWhere(['campaign_to_visual.id' => $visualId])
                        ->getRowArray();
    }

    public function selectVisualsByCampaign($campaignId, $columns = 'campaign_to_visual.*, campaign_to_channel.channel_id AS channel_type')
    {
        $builder = $this->db->table('campaign_to_visual');
        return $builder->select($columns)
                        ->join('campaign_to_channel', 'campaign_to_channel.id=campaign_to_visual.channel_id')
                        ->where('campaign_to_visual.campaign_id', $campaignId)
                        ->orderBy('campaign_to_visual.channel_id ASC, sender ASC')
                        ->get()
                        ->getResultArray();
    }

    public function selectPlanning($campaignId, $columns = '*')
    {
        $builder = $this->db->table('campaign_planning');

        return $builder->select($columns)
                        ->getWhere(['id' => $campaignId])
                        ->getRowArray();
    }

    public function getCampaign(int $campaignId)
    {
        $campaign = $this->findCampaignByID($campaignId);

        if (!$campaign) {
            return false;
        }
        
        $data = [];
        if (!empty($campaignId)) {
            $data['campaign'] = $campaign;
            $data['companies'] = $this->getCompanyCampaign($campaignId);
            $data['channels'] = $this->findChannelByCampaignId($campaignId);
            $data['planning'] = $this->findPlanningByCampaignId($campaignId);
            $data['segmentation'] = $this->segmentationModel->getSegmentations([
                'campaign_id' => $campaignId],
                '*'
            );
            $data['api'] = [];
            $data['content'] = [];

            if (!is_null($this->getContentEmail($campaignId))) {
                array_push($data['content'], $this->getContentEmail($campaignId));
            }
            if (!is_null($this->getContentSms($campaignId))) {
                array_push($data['content'], $this->getContentSms($campaignId));
            }
        }

        return $data;
    }

    public function getContentSms(int $campaignId)
    {
        $rows = $this->db->table('campaign_content_sms')
                    ->where('campaign_id', $campaignId)
                    ->get()
                    ->getResultArray();
        return isset($rows[0]) ? $rows[0] : null;
    }

    public function getContentEmail(int $campaignId)
    {
        $rows = $this->db->table('campaign_content_email')
            ->where('campaign_id', $campaignId)
            ->get()
            ->getResultArray();

        return isset($rows[0]) ? $rows[0] : null;
    }

    public function getCampaigns($company)
    {
        return $this->db->table('campaigns as ca')
                   ->select('ca.*, co.id as company_id, co.fiscal_name') 
                   ->join('campaign_company as cc', 'ca.id = cc.campaign_id', 'left')
                   ->join('companies as co', 'cc.company_id = co.id','left')  
                   ->where('cc.company_id', $company)
                   ->get()
                   ->getResultArray();
    }

    public function getVolumeSegmentation(int $campaignId)
    {
        $volume = [];
        $rows = $this->db->table('campaign_api')
            ->where('campaign_id',$campaignId)
            ->get()->getResultArray();

        if ($rows) {
            foreach ($rows as $row) {
                $volume[$row['channel_id']] = $row['segmentation'];
            }
        }

        return $volume;
    }

    public function getCampaignVisual(int $visualId)
    {
        return $this->selectVisual($visualId);
    }

    public function getChannelType(int $channelId)
    {
        $channel = $this->selectChannel($channelId, 'channel_id');
        if (!empty($channel)) {
            return $channel['channel_id'];
        }

        return 0;
    }

    public function saveCampaign($post, $campaignId = null)
    {
        $data = [
            'name'             => $post['name'],
            'type'             => 2,
            'annonceur'        => '',
            'model_economique' => 'cpm',
            'status'           => 1,
        ];

        $builder = $this->db->table('campaigns');
        $builder->set('created', 'NOW()', FALSE);
        $builder->set('id_client', isset($_SESSION['user_id']) ? $_SESSION['user_id']: null);
        
        if ($campaignId !== null) {
            if (isset($post['message'])) {
                $table = $this->db->table('contact_us');
                $rows = $table->where('campaign_id', $campaignId)->get()->getResult();
                if ($rows) {
                    $table->update(['message' => $post['message']], ['campaign_id' => $campaignId]);
                } else {
                    $table->set(['message' => $post['message'], 'campaign_id' => $campaignId])
                        ->insert();
                }
            }

            $campaignCompany = [];
            $this->db->table('campaign_company')->delete(['campaign_id' => $campaignId]);

            foreach (explode(',', $post['company']) as $company) {
                $this->db->table('campaign_company')->insert([
                    'campaign_id' => $campaignId,
                    'company_id'  => $company
                ]);
            }

            unset($post['message'],$post['action'], $post['error'], $post['company'], $post['email']);
            $builder->update($post, ['id' => $campaignId]);
            return (int) $campaignId;
        } else {
            $post['status'] = CampaignStatus::CREATE;
            $builder->insert($data);
            $lastId = $this->db->insertID();

            foreach (explode(',',$post['company']) as $company) {
                $this->db->table('campaign_company')
                     ->insert(['campaign_id' => $lastId, 'company_id' => $company]);
            }

            return $lastId;
        }
    }

    public function updateCampaign($post, $companyId, $campaignId)
    {
        unset($post['action']);
        $post['company_id'] = $post['company'];
        
        $this->db->table('campaigns')->update($post, ['id' => $campaignId]);
        return (int) $campaignId;
    }

    public function saveChannel($post, $channel = null)
    {
        $campaignId = (int) $channel ? $channel['campaign_id'] : getSegment(3);
        $channelData = [
            'campaign_id' => $campaignId,
            'channel_id' => implode(',',$post['channel']),
            'level' => 1,
            'trigger_status' => campaignChannelStatus::OPENED,
            'exclude_converted' => !empty($post['exclude']) ? 1 : 0,
            'created' => time(),
            'updated' => time(),
        ];

        $builder = $this->db->table('campaign_to_channel');

        if ($channel !== null) {
            foreach ($post['channel'] as $ch) {
                switch ($ch) {
                    case campaignChannelType::EMAIL:
                        $this->db->table('campaign_content_sms')->delete(['campaign_id' => $campaignId]);
                        break;
                    case campaignChannelType::SMS:
                        $this->db->table('campaign_content_email')->delete(['campaign_id' => $campaignId]);
                        break;
                    default:
                        break;
                }
                $this->db->table('campaign_planning')->delete(['campaign_id' => $campaignId, 'channel_id' => $ch]);
            }

            $builder->update($channelData, ['id' => $channel['id']]);
            return $channel['campaign_id'];

        } else {
            $builder->insert($channelData);
            $campaign = $this->updateCampaignStatus(getSegment(3), CampaignStatus::CHANNEL);
            return (int) $campaign['id'];
        }
    }

    public function saveContent($post, $content = [])
    {
        $channels = explode(',', $post['channels']);
        $campaignId = $content ? getSegment(4) : getSegment(3);

        if (in_array(campaignChannelType::EMAIL, $channels)) {

            $data = [
                'campaign_id' => $campaignId,
                'sender'      => $post['sender'],
                'object'      => $post['object'],
                'html'        => $post['html'],
                'html_text'   => $post['htmlText']
            ];

            $table = $this->db->table('campaign_content_email');
            array_key_exists('sender', $content) ? $table->update($data, ['campaign_id' => $campaignId]) : $table->insert($data);
        }

        if (in_array(campaignChannelType::SMS, $channels)) {
            $data = [
                'campaign_id'             => $campaignId,
                'sms_oneclick'            => $post['sms_oneclick'],
                'mobile_expediteur'       => $post['mobile_expediteur'],
                'mobile_message'          => $post['mobile_message'],
                'mobile_url_redirection'  => $post['mobile_url_redirect'],
                'text'                    => $post['text']
            ];

            $table = $this->db->table('campaign_content_sms');
            array_key_exists('sms_oneclick', $content) ? $table->update($data, ['campaign_id' => $campaignId]) : $table->insert($data);
        }

        $campaign = $this->updateCampaignStatus($campaignId, CampaignStatus::PLANNING);
        return (int) $campaign['id'];
    }

    public function savePlanning($post, $campaignId)
    {
        $builder = $this->db->table('campaign_planning');
        unset($post['action']);

        $builder->delete(['campaign_id' => $campaignId]);

        foreach ($post['channel'] as $key => $data) {
            $data = [
                'campaign_id' => (int) $campaignId,
                'channel_id'  => (int) $data,
                'date_send'   => strtotime($post['dateSend'][$key]),
                'volume'      => (int) $post['volume'][$key],
                'created'     => time(),
                'updated'     => time(),
            ];

            $builder->insert($data);
        }

        $this->updateCampaignStatus($campaignId, CampaignStatus::PLANNING);
        return (int) $campaignId;
    }

    public function formCampaign($data = null)
    {
        if (isset($data)) {
            $data = trim_data($data);
        }

        $form = [
            'action' => ['field' => 'action', null, 'post' => 'saveCamapign', 'rules' => 'required'],
            'name' => ['field' => 'name', 'label' => trad('Name', 'campaign'), 'post' => isset($data['name']) ? $data['name'] : '', 'rules' => 'required'],
            // 'type' => ['field' => 'type', 'label' => trad('Type', 'campaign'), 'post' => isset($data['type']) ? $data['type'] : '', 'options' => $this->listCampaignTypes(), 'rules' => 'required'],
            'annonceur' => ['field' => 'annonceur', 'label' => trad('Annonceur', 'campaign'), 'post' => isset($data['annonceur']) ? $data['annonceur'] : '', 'rules' => 'required'],
            // 'model_economique' => ['field' => 'model_economique', 'label' => trad('Model économique', 'campaign'), 'post' => isset($data['model_economique']) ? $data['model_economique'] : '', 'options' => $this->listEconomicModel(), 'rules' => 'required'],
            'tarif_unitaire' => ['field' => 'tarif_unitaire', 'label' => trad('Tarif unitaire', 'campaign'), 'post' => isset($data['tarif_unitaire']) ? $data['tarif_unitaire'] : '', 'rules' => 'required'],
            'tag' => ['field' => 'tag', 'label' => trad('Tag', 'campaign'), 'post' => isset($data['tag']) ? $data['tag'] : '', 'options' => $this->listTags(), 'rules' => 'required', 'multiple' => true],
            'message' => ['field' => 'message', 'label' => trad('Message', 'campaign'), 'post' => isset($data['message']) ? $data['message'] : '', 'rules' => 'required'],
        ];

        return $form;
    }

    public function formVisual($data = null, $campaignId = null)
    {
        if (isset($data)) {
            $data = trim_data($data);
        }
        $form = [
            'action' => ['field' => 'action', null, 'post' => 'saveVisual', 'rules' => 'required'],
            'channel_id' => ['field' => 'channel_id', 'label' => trad('Channel', 'campaign'), 'post' => isset($data['channel_id']) ? $data['channel_id'] : '', 'options' => $this->listCampaignChannels($campaignId), 'rules' => 'required'],
            'campaign_sender' => ['field' => 'campaign_sender', 'label' => trad('Sender', 'campaign'), 'post' => isset($data['campaign_sender']) ? $data['campaign_sender'] : '', 'rules' => 'required'],
            'campaign_subject' => ['field' => 'campaign_subject', 'label' => trad('Subject', 'campaign'), 'post' => isset($data['campaign_subject']) ? $data['campaign_subject'] : '', 'rules' => 'required'],
            'campaign_visual' => ['field' => 'campaign_visual', 'label' => trad('Campaign content', 'campaign'), 'post' => isset($data['campaign_visual']) ? $data['campaign_visual'] : '', 'rules' => 'required'],
        ];

        return $form;
    }

    public function formChannel($data = null)
    {
        if (isset($data)) {
            $data = trim_data($data);
        }

        $form = [
            'action' => ['field' => 'action', null, 'post' => 'saveChannel', 'rules' => 'required'],
            'channel_id' => ['field' => 'channel_id', 'label' => trad('Channel', 'campaign'), 'post' => isset($data['channel']) ? $data['channel'] : '', 'options' => $this->listChannelTypes(), 'rules' => 'required'],
            'level' => ['field' => 'level', 'label' => trad('Level', 'campaign'), 'post' => isset($data['level']) ? $data['level'] : '', 'options' => $this->listLevels(), 'rules' => 'required'],
        ];

        return $form;
    }

    public function formPlanning($data = null)
    {
        if (isset($data)) {
            $data = trim_data($data);
        }

        $form = [
            'action' => ['field' => 'action', null, 'post' => 'savePlanning', 'rules' => 'required'],
            'start' => ['field' => 'start', 'label' => trad('Campaign start', 'campaign'), 'post' => isset($data['start']) ? date('d-m-Y H:i:s', $data['start']) : '', 'rules' => 'required'],
            'end' => ['field' => 'end', 'label' => trad('Campaign end', 'campaign'), 'post' => isset($data['end']) ? date('d-m-Y H:i:s', $data['end']) : '', 'rules' => 'string'],
            'volume' => ['field' => 'volume', 'label' => trad('Campaign volume', 'campaign'), 'post' => isset($data['volume']) ? $data['volume'] : '1000', 'rules' => 'required'],
        ];

        return $form;
    }

    public function listCampaignTypes()
    {
        $output = [];
        foreach(CampaignType::getAll() as $key => $value) {
            $output[$key] = ucfirst(trad($value));
        }

        return $output;
    }

    public function listEconomicModel()
    {
        $output = [];

        foreach (ModelEconomic::getAll() as $key => $value) {
            $output[strtolower(str_replace('_',' ',$key))] = str_replace('_',' ', $value);
        }

        return $output;
    }

    public static function listChannelTypes()
    {
        $output = [];
        foreach(campaignChannelType::getAll() as $key => $value) {
            $output[$key] = ucfirst(trad($value));
        }

        return $output;
    }

    public function listCampaignChannels($campaignId)
    {
        $data = [];
        $channels = $this->selectChannelsByCampaign($campaignId);
        if (!empty($channels)) {
            foreach ($channels as $channel) {
                $data[$channel['id']] = $this->listChannelTypes()[$channel['channel_id']];
            }
        } else {
            $data = $this->listChannelTypes();
        }

        return $data;
    }

    public function listTags()
    {
        $output = [];
        foreach(CampaignTag::getAll() as $key => $value) {
            $output[$key] = $value;
        }

        return $output;
    }

    public function findCampaignByID($id)
    {
        $rows = $this->db->table('campaigns as c')
                      ->select('c.*, co.message')
                      ->join('contact_us as co','c.id=co.campaign_id', 'left')
                      ->where('c.id', $id)
                      ->get()
                      ->getResultArray();

        return isset($rows[0]) ? $rows[0] : null;
    }

    public function getContentCampaign(int $campaignId)
    {
        $builder = $this->db->table('campaigns as ca');
        return $builder
                ->select('ca.id as campaign_id, ca.*, cce.*, ccs.*')
                ->join('campaign_content_email as cce', 'cce.campaign_id=ca.id', 'left')
                ->join('campaign_content_sms as ccs', 'ccs.campaign_id=ca.id', 'left')
                ->where('ca.id', $campaignId)
                ->get()
                ->getResultArray()[0];
    }

    public function findPlanningByID($id)
    {
        $rows = $this->db->table('campaign_planning')
            ->where('campaign_id', $id)
            ->get()
            ->getResultArray();

        return isset($rows[0]) ? $rows[0] : [];
    }

    public function findChannelByCampaignId($campaignId)
    {
        $rows = $this->db->table('campaign_to_channel')
                     ->select('*')
                     ->where('campaign_id', $campaignId)
                     ->get()
                     ->getResultArray();

        return isset($rows[0]) ? $rows[0] : [];
    }

    public function findContentByCampaignId($campaignId)
    {
        $rows = $this->db->table('campaign_content')
                     ->select('*')
                     ->where('campaign_content.campaign_id', $campaignId)
                     ->get()
                     ->getResultArray();

        return $rows ? $rows : [];
    }

    public function findContentEmail(int $contentId)
    {
        $rows = $this->db->table('campaign_content_email')
            ->select('*')
            ->where('id', $contentId)
            ->get()
            ->getResultArray();

        return isset($rows[0]) ? $rows[0] : [];
    }

    public function findPlanningByCampaignId($campaignId)
    {
        $rows = $this->db->table('campaign_planning')
                     ->select('*')
                     ->where('campaign_id', $campaignId)
                     ->get()
                     ->getResultArray();

        return isset($rows) ? $rows : [];
    }

    public function findCampaignBySegmentationId(int $id){
        $rows = $this->db->table('campaigns')
            ->select('campaigns.*')
            ->join('campaign_segments','campaign_segments.campaign_id=campaigns.id','inner')
            ->where('campaign_segments.id',$id)
            ->get()
            ->getResultArray();

        return isset($rows[0]) ? $rows[0] : [];
    }

    public function getCompanyCampaign(int $campaignId,string $select='fiscal_name'){
        $data = [];
        $rows = $this->findCampaign($campaignId);
        foreach ($rows as $row) {
            $companies = $this->db->table('companies')
                ->select($select)
                ->where('companies.id',$row['company_id'])
                ->get()
                ->getRowArray();
            $data[] = $companies[$select];
        }
        
        return $data;
    }

    public function insertCampaignApi(array $params)
    {
        $this->db->table("campaign_api")->insert($params);
    }

    public function mergeCampaignApi(array $params){
        $where = ['campaign_id'=>$params['campaign_id'],'channel_id'=>$params['channel_id']];
        $counter = $this->db->table('campaign_api')->selectCount('id','total')->where($where)->get()->getRowArray();
        
        if((int)$counter['total']==0)
            $this->db->table("campaign_api")->insert($params);
        else
            $this->db->table("campaign_api")->update($params,$where);
    }

    public function updateCampaignApi(int $campaignId, int $channelId, int $validation, int $programmation)
    {
        $this->db->table("campaign_api")
            ->update(['programmation' => $programmation], [
                'campaign_id' => $campaignId, 'channel_id' => $channelId, 'validation' => $validation
            ]);
    }

    public function getCampainApiValidation(int $campaignId){
        $campaignApiId = false;
        if($campaignData = $this->db->table("campaign_api")->orderBy('campaign_api.validation','DESC')->getWhere(['campaign_api.campaign_id' => $campaignId])->getRowArray()){
            $campaignApiId = (int) $campaignData['validation'];
        }
        return $campaignApiId;
    }

    private function isChannelExist($campaignId, $channelId, $level) 
    {
        $criteria = ['campaign_id' => $campaignId, 'channel_id' => $channelId, 'level' => $level];

        $rows = $this->db->table('campaign_to_channel')
                     ->select('*')
                     ->where($criteria)
                     ->get()
                     ->getResultArray();

        return sizeof($rows) > 0 ? true : false;
    }

    private function isPlanningExist($campaignId) 
    {
        $rows = $this->db->table('campaign_planning')
                     ->select('*')
                     ->where('campaign_id', $campaignId)
                     ->get()
                     ->getResultArray();

        return sizeof($rows) > 0 ? true : false;
    }
  
    public function deleteCampaign($id)
    {
        $this->db->table('campaign_planning')->delete(['campaign_id' => $id]);
        $this->db->table('campaign_to_channel')->delete(['campaign_id' => $id]);
        $this->db->table('campaign_to_companies')->delete(['comapny_id' => $id]);
        $this->db->table('campaign_to_visual')->delete(['campaign_id' => $id]);
        $this->db->table('campaigns')->delete(['id' => $id]);
    }

    public function updateCampaignStatus($campaignId, $status)
    {
        $builder = $this->db->table('campaigns');
        $row = $builder->select('*')
                ->where('id', $campaignId)
                ->get()
                ->getResultArray()[0];

        if ($row !== null) {
            $builder->set('status', $status)
                    ->where('id', $campaignId)
                    ->update();
        }

        return $row;
    }

    public function findCampaign($campaignId)
    {
        $rows = $this->db->table('campaigns as ca')
                       ->select('ca.id as campaign_id, ca.*, co.id as company_id, co.fiscal_name')
                       ->join('campaign_company as cc', 'cc.campaign_id=ca.id','left')
                       ->join('companies as co', 'cc.company_id=co.id','left')
                       ->where('ca.id', $campaignId)
                       ->get()
                       ->getResultArray();

        return isset($rows) ? $rows : null;
    }

    private function insertDataContent(array $data, int $id)
    {
        $builder = $this->db->table('campaign_content');

        if (is_array($data['content'])) {
            foreach($data['content'] as $c) {
                $contentChild = array(
                    'content_id' => $id, 
                    'content' => $c
                );

                $this->db->table('campaign_to_content')
                     ->insert($contentChild);
            }
        }
    }

    public function deleteContent(int $id)
    {
        $this->db->table("campaign_content")->delete(["id" => $id]);
    }

    public function getUsers(?int $subCompanyId)
    {
        $builder = $this->db->table('campaigns')->select('cc.sender, cc.campaign_id')
            ->join('campaign_content as cc', 'campaigns.id = cc.campaign_id', 'LEFT')
            ->join('campaign_company as co', 'co.campaign_id = campaigns.id', 'LEFT')
            ->join('companies as com', 'com.id = co.company_id', 'LEFT');
        if ($subCompanyId) {
            $builder->where('co.company_id', $subCompanyId);
        }
        return $builder->groupBy('cc.sender')
            ->get()
            ->getResultArray()
        ;
    }

    public function campaignsWithContentByCompany(?int $subCompanyId, ?string $startDate = null, ?string $endDate = null, ?string $user = null, ?int $campaignChannelType = null)
    {
        $startDate = $startDate != '' ? $startDate : null;
        $endDate = $endDate != '' ? $endDate : null;
        $user = $user != '' ? $user : null;
        if ($startDate) {
            if (checkFormatDate($startDate)) {
                $startDate = new \DateTime($startDate);
                $startDate = $startDate->getTimestamp();
            } else {
                return new \Exception(trad('invalid startDate'));
            }
        }
        if ($endDate) {
            if (checkFormatDate($endDate)) {
                $endDate = new \DateTime($endDate);
                $endDate = $endDate->getTimestamp();
            } else {
                return new \Exception(trad('invalid endDate'));
            }
        }
        
        $builder = $this->db->table('campaigns')
            ->join('campaign_planning as cp', 'campaigns.id = cp.campaign_id', 'INNER')
            ->join('campaign_company as co', 'co.campaign_id = campaigns.id', 'INNER')
            ->join('companies as com', 'com.id = co.company_id', 'INNER')
            ->where('campaigns.status', CampaignStatus::VALIDATED);
        $select = 'campaigns.id, campaigns.name, campaigns.type, campaigns.status, co.company_id, '
                .'FROM_UNIXTIME(cp.date_send) AS startDate, FROM_UNIXTIME(cp.date_send) AS endDate, ';
        if ($campaignChannelType == campaignChannelType::EMAIL) {
            $builder->join('campaign_content_email as cc', 'campaigns.id = cc.campaign_id', 'INNER')
            ->select($select.'cc.object AS subject, "email" AS visual_name ');
            if ($user && $user != 'ALL') {
                $builder->where('cc.sender', $user);
            }
        }
        if ($campaignChannelType == campaignChannelType::SMS) {
            $builder->join('campaign_content_sms as cc', 'campaigns.id = cc.campaign_id', 'INNER')
            ->select($select.'cc.mobile_message AS subject, "sms" AS visual_name, ');
        }
        if ($subCompanyId) {
            $builder->where('co.company_id', $subCompanyId);
        }
        if ($startDate) {
            $builder->where('cp.date_send >= ', $startDate);
        }
        if ($endDate) {
            $builder->where('cp.date_send <= ', $endDate);
        }
 
        return $builder->get()->getResultArray();
    }

    public function statCampaign(int $campaignChannelType, ?int $subCompanyId, ?string $dateMois = null, ?string $startDate = null, ?string $endDate = null)
    {
        if(!$company = $this->db->table('companies')->select('companies.id_client_datawork')
        ->where('companies.id', $subCompanyId)->get()->getRowArray()) return 0;
        $arrayFieldPost = [
            'id_client'     => (int)$company['id_client_datawork'],
            'type_campagne' => CampaignType::getDescriptionById(CampaignType::PREMIUM),
        ];
        if ($dateMois) {
            $arrayFieldPost['date_mois'] = $dateMois;
        }
        $campaignPremium = json_decode(connectApi('POST', '/campagne', $arrayFieldPost), true);
        $arrayFieldPost['type_campagne'] = CampaignType::getDescriptionById(CampaignType::PERFORMANCE);
        $campaignPerformance = [];//TODO : $campaignPerformance = json_decode(connectApi('POST', '/campagne', $arrayFieldPost), true);
        $return = array_merge($campaignPremium, $campaignPerformance);
        $totalSend = 0;
        if ($return['resultat'] == 1) {
            foreach ($return['campagnes'] as $campagne) {
                $arrayFieldPost2 = [
                    'canal'      => CampaignCanalType::getDescriptionById($campaignChannelType),
                    'date_debut' => $startDate,
                    'date_fin'   => $endDate,
                ];
                $return2 = json_decode(connectApi('POST', '/campagne/'.$campagne['id_campagne'].'/statistique', $arrayFieldPost2), true);
                if ($return2['resultat'] == 1) {
                    //$return2['statistique'];
                    // TODO control if envoye
                    $totalSend++;
                }
                // TODO : STANDBY , IF BUG OF ENDPOINT OF ALL STAT OF CLIENT AVAILABLE, DELETE THIS INSTRUCTION
                if ($totalSend > 2) {
                    break;
                }
            }
        }

        return $totalSend;
    }

    public function getCampaignByAjax(?int $subCompanyId, ?string $startDate = null, ?string $endDate = null, ?string $user = null, ?int $campaignChannelType = null)
    {
        $builder = $this->db->table('campaigns')->select(
            'campaigns.id'
            )->join('campaign_planning as cp', 'campaigns.id = cp.campaign_id', 'INNER')
            ->join('campaign_company as co', 'co.campaign_id = campaigns.id', 'INNER')
            ->join('companies as com', 'com.id = co.company_id', 'INNER')
            ->where('campaigns.status', CampaignStatus::VALIDATED);

        if ($campaignChannelType == campaignChannelType::EMAIL) {
            $builder->join('campaign_content_email as cc', 'campaigns.id = cc.campaign_id', 'INNER');
            if ($user) {
                $builder->where('cc.sender', $user);
            }
        }
        if ($campaignChannelType == campaignChannelType::SMS) {
            $builder->join('campaign_content_sms as cc', 'campaigns.id = cc.campaign_id', 'INNER');
        }
        if ($subCompanyId) {
            $builder->where('co.company_id', $subCompanyId)->where('com.id', $subCompanyId);
        }
        if ($startDate) {
            $builder->where('cp.date_send >= ', $startDate);
        }
        if ($endDate) {
            $builder->where('cp.date_send <= ', $endDate);
        }

        return count($builder->get()->getResultArray());
    }

    public function requestSegments(int $campaignId)
    {
        $segments = $this->segmentationModel->getSegmentations(['campaign_id' => $campaignId]);

        if (!$segments) {
            throw new \Exception(trad("Segments empty"));
        }

        $request = [];
        foreach ($segments as $segment) {
            if ($segment['civility']) {
                $row = [
                    'field' => 'civility',
                    'condition' => 'equal',
                    'value' => $segment['civility']
                ];

                $operator = ['operator' => 'and'];
                $fields = [
                    ['field' => 'postal_code', 'condition' => 'equal', 'value_in' => ['75012', '75013']],
                    ['operator' => 'or'],
                    ['field'    => 'iris', 'condition' => 'equal', 'value_in' => explode(',', $segment['iris'])],
                ];

                array_push($request, $row, $operator, $fields);
            }
        }

        return $request;
    }

    public function findClientDataWork(int $campaignId)
    {
        $builder = $this->db->table('campaign_company as cc');
        return $builder
                    ->join('companies as co', 'co.id = cc.company_id', 'left')
                    ->where('cc.campaign_id',$campaignId)
                    ->get()->getRow();
    }

    public function updateClientDataWork(int $campaignId, int $clientDataWork)
    {
        $builder = $this->db->table('companies');
        $company = $this->findClientDataWork($campaignId);
        $builder->update(['id_client_datawork' => $clientDataWork], ['id' => $company->id]);
    }

    public function getSegmentRequest(int $campaignId){
        $segments = $this->db->table('campaign_segments')
                         ->where('campaign_id', $campaignId)
                         ->get()->getResultArray();

        $companyId = $this->db->table('campaign_company')->where('campaign_id', $campaignId)
            ->get()->getRowArray()['company_id'];

        $companyLocalization = $this->db->table('company_localization')->where('company_id', $companyId)
                                    ->get()->getResultArray();
        $postalCode = [];
        $iris = [];
        foreach ($companyLocalization as $localization) {
            $postalCode[] = $localization['postal_code'];
            $iris[] = $localization['iris'];
        }

        if (!$segments) {
            throw new \Exception(trad("Segments empty"));
        }
        $request = [];
        foreach ($segments as $key=>$segment) {
            $subRequest=[];
            if($segment['age_min'] || $segment['age_max']){
                $age_min=$segment['age_min'] && $segment['age_min'] != '0' ?$segment['age_min']:18;
                $age_max=$segment['age_max']??100;
                $subRequest[]=[
                    ['field' => 'age', 'condition' => 'greater_than_equal', 'value' => $age_min],
                    ['operator' => 'and'],
                    ['field' => 'age', 'condition' => 'lower_than_equal', 'value' => $age_max],
                ];
                $subRequest[]=['operator' => 'and'];
            }
            if($segment['civility']!=''){
                $subRequest[]=[['field' => 'civility', 'condition' => 'equal', 'value_in' => explode(',', $segment['civility'])]];
                $subRequest[]=['operator' => 'and'];
            }
            if($segment['nature']!=''){
                $subRequest[]=[['field' => 'nature', 'condition' => 'equal', 'value_in' => explode(',', $segment['nature'])]];
                $subRequest[]=['operator' => 'and'];
            }
            if($segment['car_owner']!=''){
                $subRequest[]=[['field' => 'car_owner', 'condition' => 'equal', 'value_in' => explode(',', $segment['car_owner'])]];
                $subRequest[]=['operator' => 'and'];
            }
            if($segment['is_auto_intention'] == AutoIntentionnistType::YES){
                $current=new \DateTime();
                $subRequest[]=[['field' => 'automobile_intention_date_collect', 'condition' => 'equal', 'value' => $current->format('Y-m-d')]];
                $subRequest[]=['operator' => 'and'];
            }
            if($segment['auto_owned']!=''){
                $subRequest[]=[[
                    ['field' => 'auto_owned_1_segment_clear', 'condition' => 'equal', 'value_in' => explode(',', $segment['auto_owned'])],
                    ['operator' => 'or'],
                    ['field' => 'auto_owned_2_segment_clear', 'condition' => 'equal', 'value_in' => explode(',', $segment['auto_owned'])],
                ]];
                $subRequest[]=['operator' => 'and'];
            }
            if (count($postalCode)) {
                $subRequest[]=[['field' => 'postal_code', 'condition' => 'equal', 'value_in' => $postalCode]];
                $subRequest[]=['operator' => 'and'];
            }
            if (count($iris)) {
                $subRequest[]=[['field' => 'iris', 'condition' => 'equal', 'value_in' => $iris]];
                $subRequest[]=['operator' => 'and'];
            }
            if(count($subRequest)){
                array_pop($subRequest);
                $request[]=$subRequest;
                $request[]=['operator' => 'or'];
            }
        }
        if(count($request)) array_pop($request);

        return $request;
    }
}
