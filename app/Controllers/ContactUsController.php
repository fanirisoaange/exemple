<?php

namespace App\Controllers;

use App\Enum\CampaignStatus;
use App\Libraries\Layout;
use App\Mailer\Mailer;

class ContactUsController extends BaseController
{
    protected $campaignModel;
    protected $session;
    protected $mailer;

    public function __construct()
    {
        $this->campaignModel = model('CampaignModel', true, $this->db);
        $this->session = \Config\Services::session();
        $this->mailer = new Mailer();
        helper(['campaign']);
    }

    public function contactUs(int $campaignId)
    {
        $post = $this->request->getPost();
        $campaign = $this->campaignModel->findCampaignById($campaignId);

        $id = isset($_SESSION['current_main_company'])
            ? $_SESSION['current_main_company'] : NULL;

        if ($post) {
            $this->campaignModel->saveCampaign($post, $campaign ? $campaign['id'] : null);
            $this->session->setFlashdata('success', trad("Your request is sent, we will answer you as soon as possible"));
            $this->campaignModel->updateCampaignStatus($campaignId, CampaignStatus::CAMPAIGN_SUBMITTED);

            // send email
            $view = view('contactUs/email/message',$this->campaignModel->getCampaign($campaignId));
            $response = $this->mailer->sendEmail(getenv('MAILER_USER'),$post['email'], "Custom quote", $view);

            if ($response['status'] == 1) {
                return redirect()->to(route_to('campaign_summarize', $campaignId));
            } else {
                throw new \Exception($response['message']);
            }
        }

        $companiesIds = [];
        foreach ($this->campaignModel->findCampaign($campaign['id']) as $item) {
           if (!is_null($item['company_id'])) {
              $companiesIds[] = $item['company_id'];
           }
        }

        $parameters = array(
            'title'           => trad('Contact Us', 'contact us'),
            'metadescription' => trad('Contact Us', 'contact us'),
            'form'            => $this->campaignModel->formCampaign(empty($post) && !empty($campaign) ? $campaign : $post),
            'companyId'       => $companiesIds ? implode(',', $companiesIds): $id,
            'campaign'        => $campaign ? $campaign : null,
        );

        $data = array_merge($this->init(null), $parameters);

        return $this->layout->view('contactUs/contact-us', $data);
    }

    public function summarize(int $campaignId)
    {
        if (!$this->session->getFlashdata("success")) {
            return redirect()->to(route_to("contact_us", $campaignId));
        }

        $companies = $this->campaignModel->getCompanyCampaign($campaignId);

        $campaign = $this->campaignModel->findCampaignById($campaignId);
        $parameters = array(
            'title'           => trad('Contact Us', 'contact us'),
            'metadescription' => trad('Contact Us', 'contact us'),
            'campaign'        => $campaign,
            'companies'       => $companies
        );

        $data = array_merge($this->init(null), $parameters);

        return $this->layout->view('contactUs/summarize', $data);
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
            ASSETS . 'js/summernote.min',
            ASSETS . 'js/sweetalert.min',
            ASSETS . 'js/campaigns',
            ASSETS . 'js/programmation'
        ]);

        return $data;
    }
}