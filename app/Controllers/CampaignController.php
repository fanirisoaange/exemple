<?php

namespace App\Controllers;

use App\Enum\campaignChannelStatus;
use App\Enum\campaignChannelType;
use App\Enum\CampaignTag;
use App\Libraries\Layout;
use App\Enum\CampaignStatus;
use App\Enum\CampaignType;
use App\Enum\CampaignCanalType;
use CodeIgniter\HTTP\Response;

class CampaignController extends BaseController
{
    protected $campaignModel;
    protected $segmentationModel;

    const PATH = './assets/uploads/';

    public function __construct()
    {
        $this->campaignModel = model('CampaignModel', true, $this->db);
        $this->segmentationModel = model('SegmentationModel', true, $this->db);
        helper(['campaign']);
    }

    public function list()
    {
        if ( ! isMemberAccounting())
        {
            return accessDenied();
        }

        $id = isset($_SESSION['current_main_company'])
            ? $_SESSION['current_main_company'] : NULL;
        $campaigns =[];
        if (isset($_SESSION['current_sub_company'])) {
            $campaigns = $this->campaignModel->getCampaigns($_SESSION['current_sub_company']);
        }
        $options = [
            'title'           => trad('campaign List'),
            'metadescription' => trad('List of campaign', 'user'),
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
                    ' Campaign List'
                ),
            'campaigns'       => $campaigns,
            'companyId'       => $id,
        ];

        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_css(
            [
                LIBRARY . 'datatables-bs4/css/dataTables.bootstrap4.min',
                LIBRARY . 'datatables-responsive/css/responsive.bootstrap4.min',
                ASSETS . 'css/summernote.min',
            ]
        );
        $layout->add_js(
            [
                LIBRARY . 'datatables/jquery.dataTables.min',
                LIBRARY . 'datatables-bs4/js/dataTables.bootstrap4.min',
                LIBRARY . 'datatables-responsive/js/dataTables.responsive.min',
                LIBRARY . 'datatables-responsive/js/responsive.bootstrap4.min',
                LIBRARY . 'fullcalendar-3.4.0/lib/moment.min',
                LIBRARY . 'bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min',
                ASSETS . 'js/summernote.min',
                LIBRARY . 'jquery-validation/jquery.validate.min',
                LIBRARY . 'jquery-validation/additional-methods.min',
                ASSETS . 'js/campaigns',
                ASSETS . 'js/helper',
            ]
        );

        return $layout->view('campaigns/list', $options);
    
    }
  
    public function delete(int $id)
    {
        try {

            $campaign = $this->campaignModel->findCampaignByID($id);
            if(!canDeleteCampagn((int)$campaign['status'])){
                return accessDenied();
            }
            $this->campaignModel->deleteCampaign($id);
            return json_encode(['message' => trad('Campaign deleted successfully')]);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
  
    public function createCampaign($campaign = null)
    {
        $id = isset($_SESSION['current_main_company'])
            ? $_SESSION['current_main_company'] : NULL;
            
        $post = $this->request->getPost();
        
        if ($post) {
            $campaignId = $this->campaignModel->saveCampaign($post, $campaign ? $campaign['id'] : null);

            if ($campaignId && $campaignId !== 0) {
                if ($campaign !== null) {
                    return redirect()->to(route_to('edit_channel', $campaignId));
                } 

                return redirect()->to(route_to('create_channel', $campaignId));
            }
        }

        $companiesIds = [];
        foreach ($this->campaignModel->findCampaign($campaign ? $campaign['id']: 0) as $item) {
           if (!is_null($item['company_id'])) {
              $companiesIds[] = $item['company_id'];
           }
        }

        $formCampaignViewData = [
            'form' => $this->campaignModel->formCampaign(empty($post) && !empty($campaign) ? $campaign : $post),
            'validation' => $this->validation,
            'created_date' => null,
            'updated_date' => null,
            'companyId' => $companiesIds ? implode(',', $companiesIds) : $id
        ];

        $parameters = array(
            'title' => trad('Campaign detail', 'campaign'),
            'metadescription' => trad('Campaign detail', 'campaign'),
            'page_title' => trad('Campaign detail', 'campaign'),
            'template' => view('campaigns/forms/campaign', $formCampaignViewData),
            'status' => $campaign ? $campaign['status'] : 0,
            'campaignId' => $campaign ? $campaign['id'] : 1,
        );

        $data = array_merge($this->init(null), $parameters);

        return $this->layout->view('campaigns/detail', $data);
    }

    public function channel($channel = null) 
    {   
        $post = $this->request->getPost();

        if ($post) {
            if (empty($post['channel'])) {
                return redirect()->to(route_to('create_channel', getSegment(3)));
            }else{
                $campaignId = $this->campaignModel->saveChannel($post, $channel);
                if ($campaignId !== 0) {
                    return redirect()->to(route_to('create_segmentation', $campaignId));
                }
            }
        }
        
        $formChannelViewData = [
            'channel' => $channel ? $channel['channel_id'] : '',
            'level' => $channel ? $channel['level'] : '',
            'validation' => $this->validation,
        ];

        $parameters = array(
            'title' => trad('Channel create', 'channel'),
            'metadescription' => trad('Channel create', 'channel'),
            'page_title' => trad('Channel create', 'channel'),
            'template' => view('campaigns/forms/channel', $formChannelViewData),
            'status' => $this->getCampaign($channel ? $channel['campaign_id'] : getSegment(3), CampaignStatus::CHANNEL),
            'mainCompaniesOverride' => $this->campaignModel->getCompanyCampaign($channel ? $channel['campaign_id'] : getSegment(3),'id')[0],
            'campaignId' => $channel ? $channel['campaign_id'] : getSegment(3),
        );

        $data = array_merge($this->init(null), $parameters);

        return $this->layout->view('campaigns/detail', $data);
    }

    public function createSegmentation($campaign = null)
    {
       /**
        * send all segments data to views, il will be showed with ajax.
        */
        $segments = $this->segmentationModel->getSegmentations(['campaign_id'=>$campaign],'id,campaign_id');

        $formVisualViewData = [
            'campaignId' => $campaign ? $campaign : getSegment(4),
            'status' => $this->getCampaign($campaign ? $campaign : getSegment(4), CampaignStatus::SEGMENTATION),
            'segments'=> $segments,
        ];

        $parameters = array(
            'title' => trad('Segmentation create', 'segmentation'),
            'metadescription' => trad('Segmentation create', 'segmentation'),
            'page_title' => trad('Segmentation create', 'segmentation'),
            'template' => view('campaigns/forms/segmentation/segmentation', $formVisualViewData),
            'status' => $this->getCampaign($campaign ? $campaign : getSegment(4), CampaignStatus::SEGMENTATION),
            'mainCompaniesOverride' => $this->campaignModel->getCompanyCampaign($campaign ?? getSegment(4),'id')[0],
            'campaignId' => $campaign ? $campaign : getSegment(4),
        );

        $data = array_merge($this->init($campaign), $parameters);

        return $this->layout->view('campaigns/detail', $data);
    }

    public function startCounting($campaignId){

        $payload = [
            'id_bases' => [],
            'repoussoir' => ['national'],
            'taux_inactif' => 'non',
            'canal' => 'email',
            'fichier_segment_crypte_url' => null,
            'fichier_segment_crypte_action' => 'inclure',
            'group_by' => 'postal_code'
        ];

        $channels = $this->campaignModel->findChannelByCampaignId($campaignId)['channel_id'];
        $isOk = false;
        $message = '';
        $total = 0;
        foreach (explode(',', $channels) as $channel) {
            $payload['requete'] = $this->segmentationModel->getSegmentRequest($campaignId, $channel);

            $payload['canal'] = $channel == campaignChannelType::EMAIL ? "email":'mobile';

            $result = connectApi("POST", "/".getClientDataWorkCompany($campaignId)."/segmentation", $payload);
            $response = json_decode($result, true);

            if (key_exists('resultat', $response)) {
                if ($response['resultat'] == 1) {
                    $totalSegmentation = $response['segmentation']['total'];
                    $res = [
                        'campaign_id'   => $campaignId,
                        'channel_id'    => $channel,
                        'segmentation'  => $totalSegmentation,
                        'programmation' => null
                    ];
                    $message .= ' Total ('.$payload['canal'].') :'.$totalSegmentation;
                    $total +=$totalSegmentation;
                    $this->campaignModel->mergeCampaignApi($res);
                    $isOk = true;
                } else {
                    throw new \Exception($response['erreur']);
                }
            }
        }

        return $isOk ? json_encode([
            'status'  => Response::HTTP_OK,
            'message' => $total>0 ? $message : trad('No contacts available, please expand your segmentation'),
            'total' => $total,
        ]) : null;
    }

    public function submitSegmentation($campaignId){
        $this->protectCampaign($campaignId);

        $campaignData = $this->campaignModel->getCampaign($campaignId);


        if ($campaignData['channels'] && in_array(campaignChannelType::TELEMARKETING, explode(',', $campaignData['channels']['channel_id']))) {
            return redirect()->to(route_to('contact_us',$campaignId));
        }

        if ($campaignData['content']) {
            return redirect()->to(route_to('edit_content', $campaignId));
        }

        return redirect()->to(route_to('create_content', $campaignId));
    }

    public function createSegmentationItem($id = 0,$campaignId=1){
        $post = $this->request->getPost();

        if($post){

            $post['civility']='';
            if(array_key_exists('civilities',$post)){
                $post['civility'] = join(',',$post['civilities']);
                unset($post['civilities']);
            }

            $post['nature']='';
            if(array_key_exists('natures',$post)){
                $post['nature'] = join(',',$post['natures']);
                unset($post['natures']);
            }

            $post['car_owner']='';
            if(array_key_exists('car_owners',$post)){
                $post['car_owner'] = join(',',$post['car_owners']);
                unset($post['car_owners']);
            }

            $post['auto_owned']='';
            if(array_key_exists('auto_owneds',$post)){
                $post['auto_owned'] = join(',',$post['auto_owneds']);
                unset($post['auto_owneds']);
            }

            $post['is_auto_intention']='';
            if(array_key_exists('is_auto_intentions',$post)){
                $post['is_auto_intention'] = join(',',$post['is_auto_intentions']);
                unset($post['is_auto_intentions']);
            }

            $id=$this->segmentationModel->editSegment($post);

        }else{ // create default one
            $id=$this->segmentationModel->editSegment(['id'=>$id,'campaign_id'=>$campaignId]);
        }

        $data = $this->segmentationModel->get($id);
        $data['campaign'] = $this->campaignModel->findCampaignBySegmentationId($id);
        $data['form'] = $this->segmentationModel->formCreate(array_merge($post,$data));
        $html=view('campaigns/forms/segmentation/segmentationItem',$data);

        echo $this->request->isAJAX() ? json_encode(array_merge(['html'=>$html],$data)) : $html;

    }

    public function detect(int $id){
        $campaign = $this->campaignModel->findCampaignByID($id);

        if(!$campaign){
            throw new \Exception("undefined campaign");
        }

        if ($campaign['status'] == CampaignStatus::VALIDATED) {
            return redirect()->to(route_to("create_campaign"));
        }
      
        switch ((int)$campaign['status']) {
            case CampaignStatus::CREATE:
                $workflow = 'edit_channel';
                break;
            case CampaignStatus::CHANNEL:
                $workflow = 'edit_segmentation';
                break;
            case CampaignStatus::SEGMENTATION:
                $workflow = 'edit_content';
                break;
            case CampaignStatus::CONTENT:
                $workflow = 'create_planning';
                break;
            case CampaignStatus::CAMPAIGN_SUBMITTED:
                $workflow = 'campaign_summarize';
                break;
            case CampaignStatus::PLANNING:
            case CampaignStatus::VALIDATION:
            case CampaignStatus::VALIDATED:
                $workflow = 'edit_planning';
                break;
            case CampaignStatus::CAMPAIGN_SUBMITTED:
                $workflow = 'create_channel';
                break;
            default:
                $workflow = 'edit_campaign';
            break;
        }

        return redirect()->to(route_to($workflow, $id));
    }

    public function deleteSegmentationItem(int $id)
    {
        try {
            $campaign = $this->campaignModel->findCampaignBySegmentationId($id);
            if(!canDeleteCampagn((int)$campaign['status'])){
               throw new Exception("can't delete segment");
            }
            $this->segmentationModel->deleteSegment($id);
            return json_encode(['message' => trad('segment deleted successfully')]);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function planning($campaign = null, $planning = [])
    {
        $post = $this->request->getPost();
        $parts = explode('/', current_url());
        $campaignId = end($parts);

        if ($post) {
            $campaignId = $this->campaignModel->savePlanning($post, $campaignId);
            if ($campaignId !== 0) {
                return redirect()->to(route_to('edit_planning', $campaignId));
            }
        }

        $segmentationVolume = $this->campaignModel->getVolumeSegmentation($campaignId);
        $formPlanningViewData = [
            'planning'           => $planning,
            'validation'         => $this->validation,
            'channel'            => $this->campaignModel->findChannelByCampaignId($campaignId),
            'segmentationVolume' => $segmentationVolume
        ];

        $title = getSegment(3) == "edit" ? "Edit planning" : "Planning create";
        $parameters = array(
            'title'           => trad($title, 'planning'),
            'metadescription' => trad($title, 'planning'),
            'page_title'      => trad($title, 'planning'),
            'template'        => view('campaigns/forms/planning', $formPlanningViewData),
            'status'          => $this->getCampaign($campaignId, CampaignStatus::PLANNING),
            'mainCompaniesOverride' => $this->campaignModel->getCompanyCampaign($campaignId,'id')[0],
            'campaignId'      => $campaignId
        );

        $data = array_merge($this->init($campaign), $parameters);

        return $this->layout->view('campaigns/detail', $data);
    }

    public function content($campaignId, $content = [])
    {

        $post = $this->request->getPost();

        if ($post) {

            $contentId = $this->campaignModel->saveContent($post, $content);
            if (!$content && $contentId !== 0) {
                return redirect()->to(route_to('create_planning', $campaignId));
            }

            return redirect()->to(route_to('edit_planning', $campaignId));
        }

        $channel = $this->campaignModel->findChannelByCampaignId($campaignId);
        $parameters = array(
            'title'           => trad('Content create', 'content'),
            'metadescription' => trad('Content create', 'content'),
            'page_title'      => trad('Content create', 'content'),
            'template'        => view('campaigns/forms/content', ['content' => $content, 'channels' => $channel['channel_id']]),
            'status'          => $this->getCampaign($content ? getSegment(4) : getSegment(3), CampaignStatus::CONTENT),
            'mainCompaniesOverride' => $this->campaignModel->getCompanyCampaign($campaignId,'id')[0],
            'campaignId'      => $content ? getSegment(4) : getSegment(3),
        );

        $data = array_merge($this->init(null), $parameters);

        return $this->layout->view('campaigns/detail', $data);
    }

    public function deleteContent(int $id)
    {
        if ($this->request->isAJAX()) {
            $this->campaignModel->deleteContent($id);
            return json_encode([
                'status' => 200, 
                'message' => trad('Content deleted.', 'message')
            ]);
        } 

        throw new \Exception("Invalid request");
    }

    public function editCampaign()
    {
        $campaignId = getSegment(3);
        $this->protectCampaign($campaignId);

        if ($campaignId) {
            $campaign = $this->campaignModel->findCampaign($campaignId)[0];
            if (!empty($campaign)) {
                return $this->createCampaign($campaign);
            }
        }

        return redirect()->to(route_to('create_campaign', $campaignId));
    }

    public function editChannel()
    {
        $campaignId = getSegment(4);
        $this->protectCampaign($campaignId);

        if ($campaignId) {
            $channel = $this->campaignModel->findChannelByCampaignId($campaignId);
            if (!empty($channel)) {
                return $this->channel($channel);
            }
        }

        return redirect()->to(route_to('create_channel', $campaignId));
    }

    public function editContent()
    {
        $campaignId = getSegment(4);
        $this->protectCampaign($campaignId);

        if ($campaignId) {
            $content = $this->campaignModel->getContentCampaign($campaignId);
            if (!is_null($content['sender']) || !is_null($content['sms_oneclick'])) {
                return $this->content($campaignId, $content);
            }
        }

        return redirect()->to(route_to('create_content', $campaignId));
    }

    public function editPlanning()
    {
        $campaignId = getSegment(4);
        $this->protectCampaign($campaignId);

        if ($campaignId) {
            $planning = $this->campaignModel->findPlanningByCampaignId($campaignId);
            return $this->planning($campaignId, $planning);
        }

        return redirect()->to(route_to('create_planning', $campaignId));
    }

    public function editSegmentation($campaign)
    {
        return $this->createSegmentation($campaign);
    }

    public function validationReload(int $campaignId)
    {
        if ($this->request->isAJAX()) {
            if ($campaignId) {
                $campaign = $this->campaignModel->getCampaign($campaignId);
                if (!$campaign) {
                    throw new \Exception(trad("Campaign not found."));
                }

                return view('campaigns/partials/validation', ['getCampaign' => $campaign]);
            }
        }

        throw new \Exception("Invalid request");
    }

    public function loadContentForm()
    {
        $parts = explode('/', current_url());
        $campaignId = end($parts);
        $content = $this->campaignModel->findContentByCampaignId($campaignId);
        if ($this->request->isAJAX()) {
            return view('campaigns/partials/content', ['content' => $content ? $content : null]);
        }

        throw new \Exception("Invalid request");
    }

    public function previewContent(int $contentId)
    {
        if ($contentId) {
            $content = $this->campaignModel->findContentEmail($contentId);
            if ($content) {
                return view('campaigns/partials/preview-content', ['content' => $content]);
            }

            throw new \Exception("Content not found");
        }
    }

    public function validateCampaign(int $campaignId)
    {
        $campaign = $this->campaignModel->findCampaignByID($campaignId);
        if (!$campaign) {
            throw new \Exception("Campaign not found");
        }

        if(!$validation = $this->campaignModel->getCampainApiValidation($campaignId)){
            $payload = [
                'nom'               => $campaign['name'],
                'type_campagne'     => CampaignType::getDescriptionById($campaign['type']),
                'annonceur'         => 'test',
                'id_client'         => getClientDataWorkCompany($campaignId),
                'modele_economique' => $campaign['model_economique'],
                'tarif_unitaire'    => 5.23,
                'tag'               => [CampaignTag::AUTOMOBILE],
            ];

            $result = connectApi("POST", "/campagne/creation", $payload);
            if ($result){
                $response = json_decode($result, true);
                if ($response["resultat"] == 1) {
                    $validation = $response['id_campagne'];
                }else{
                    return json_encode(['error' => $response['erreur']]);
                }

            }else{
                return json_encode(['error' => "unable to connect to server"]);
            }

        }
    
        return $this->programmation($validation, $campaignId);
    }

    public function programmation($campaignIdApi, $campaignId)
    {
        $request = $this->campaignModel->getSegmentRequest($campaignId);
        $contentSms = $this->campaignModel->getContentSms($campaignId);
        $contentEmail = $this->campaignModel->getContentEmail($campaignId);
        $programmations = [];

        $params = [
            'id_campagne'        => $campaignIdApi,
            'nom'                => 'name',
            'type_programmation' => 'push',
            'id_bases'           => [],
            'repoussoir'         => ['local', 'national'],
            'requete'            => $request,
            'taux_inactif'       => 'oui',
            'volume'             => 1000
        ];

        $html = '<html lang="en"><head><meta charset="utf-8"></head><body>';

        if ($contentEmail) {
            $email = [
                'canal'                  => 'email',
                'champ_personnalisation' => [],
                'html'                   => $html . $contentEmail['html'] . '</body></html>',
                'html_text'              => $contentEmail['html_text'],
                'objet'                  => $contentEmail['object'],
                'sender'                 => $contentEmail['sender'],
            ];
            $programmations[] = array_merge($params, $email);
        }

        if ($contentSms) {
            $mobile = [
                'canal'                  => 'mobile',
                'sms_oneclick'           => isset($contentSms['sms_oneclick']) == 1 ? "oui" : "non",
                'mobile_expediteur'      => $contentSms['mobile_expediteur'],
                'mobile_message'         => $contentSms['mobile_message'],
                'mobile_url_redirection' => $contentSms['mobile_url_redirection'],
            ];
            $programmations[] = array_merge($params, $mobile);
        }

        $isValidated = false;
        $error = null;
        foreach ($programmations as $programmation) {
            try {
                $result = connectApi("POST", "/campagne/".$campaignIdApi."/programmation/creation", $programmation);
                $response = json_decode($result, true);

                if (key_exists("resultat", $response)) {
                    if ($response["resultat"] == 1) {
                        $programmationId = $response['id_programmation'];
                        $channel = null;
                        switch ($programmation['canal']) {
                            case 'email':
                                $channel = campaignChannelType::EMAIL;
                                break;
                            case 'mobile':
                                $channel = campaignChannelType::SMS;
                                break;
                            default:
                                break;
                        }

                        $this->campaignModel->updateCampaignStatus($campaignId, CampaignStatus::VALIDATED);
                        $this->campaignModel->mergeCampaignApi(['campaign_id' => $campaignId, 'channel_id' => $channel, 'validation' => $campaignIdApi, 'programmation' => $programmationId ]);
                        $isValidated = true;
                    }
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return $isValidated ? json_encode([
            'status'  => Response::HTTP_OK,
            'success' => 'Campaign validated!',
            'error'   => $error ?? null
        ]) : null;
    }

    public function doUpload()
    {
        if (!file_exists(self::PATH)) {
            mkdir(self::PATH, 0777, true);
        }

        $config = array(
            'allowed_types' => "gif|jpg|png|jpeg|csv",
        );

        if ($file = $this->request->getFile("file")) {
            if ($file->isValid() && !$file->hasMoved()) {
                $originalName = $file->getClientName();
                $fileExtension = $file->getClientExtension();

                if (!in_array($fileExtension, explode("|", $config['allowed_types']))) {
                    throw new \Exception(sprintf(
                        "Extension not allowed, available extensions %s", $config['allowed_types'])
                    );
                }

                $newName = md5($originalName) . "." .$fileExtension;
                $file->move(self::PATH, $newName);

                return base_url('public/assets/uploads') . '/' . $newName;
            }
        }
    }

    private function init($campaignId = null) 
    {
        $data = array(
            'content_only'   => false,
            'no_js'          => false,
            'nofollow'       => true,
            'top_content'    => array('layout/default/header', 'layout/default/sidebar'),
            'bottom_content' => 'layout/default/footer',
            'content'        => 'layout/default/content',
            'campaign_id'    => $campaignId,
        );

        $this->layout = new Layout();
        $this->layout->load_assets('default');
        $this->layout->add_css([
            LIBRARY . 'bootstrap4-datetimepicker/css/bootstrap-datetimepicker.min',
            LIBRARY . 'codemirror/lib/codemirror',
            LIBRARY . 'bootstrap-slider/css/bootstrap-slider',
            LIBRARY . 'codemirror/theme/dracula',
            ASSETS . 'css/summernote.min',
            ASSETS . 'css/font-awesome.min',
        ]);
        $this->layout->add_js([
            LIBRARY . 'bootstrap-switch/js/bootstrap-switch.min',
            LIBRARY . 'moment/moment-with-locales.min',
            LIBRARY . 'bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min',
            LIBRARY . 'bootstrap-slider/bootstrap-slider',
            LIBRARY . 'codemirror/lib/codemirror',
            LIBRARY . 'codemirror/mode/xml/xml',
            LIBRARY . 'codemirror/mode/javascript/javascript',
            LIBRARY . 'codemirror/mode/css/css',
            LIBRARY . 'codemirror/mode/htmlmixed/htmlmixed',
            LIBRARY . 'codemirror/addon/selection/active-line',
            LIBRARY . 'jquery-validation/jquery.validate.min',
            LIBRARY . 'jquery-validation/additional-methods.min',
            ASSETS . 'js/summernote.min',
            ASSETS . 'js/sweetalert.min',
            ASSETS . 'js/campaigns',
            ASSETS . 'js/programmation'
        ]);

        return $data;
    }

    private function getCampaign(int $campaignId, int $status): string
    {
        $campaign = $this->campaignModel->findCampaign($campaignId);
        return $campaign[0]['status'];
    }

    private function protectCampaign(int $campaignId): array
    {
        $campaign = $this->campaignModel->findCampaign($campaignId)[0];

        if(!$campaign) {
            throw new \Exception("Campaign not found.");
        }

        if (!hasCampaignAccess($campaign['company_id'])) {
            return accessDenied();
        }

        return $campaign;
    }

}
