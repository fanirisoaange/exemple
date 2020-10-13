<?php

namespace App\Controllers;

use App\Enum\campaignChannelType;
use App\Libraries\Layout;
use Exception;

class CommunicationPlan extends BaseController
{
    private $campaignModel;

    public function __construct()
    {
        $this->campaignModel = model('CampaignModel', true, $this->db);
        helper(['order']);
    }

    public function list()
    {
        if (!isMemberAdmin()) {
            return accessDenied();
        }
        $id = isset($_SESSION['current_sub_company'])
            ? $_SESSION['current_sub_company'] : null;
        $communicationPlans = [];
        $users = [];
        $usersSelected = null;
        $positionCompany = null;
        $startDateSelected = null;
        $endDateSelected = null;
        if (isset($_SESSION['current_sub_company'])) {
            $users = model('CampaignModel')->getUsers((int)$_SESSION['current_sub_company']);
            $positionCompany = model('CompanyModel')->positionCompany((int)$_SESSION['current_sub_company']);
            if ($this->request->getMethod() == 'post') {
                $communicationPlansEmail = model('CampaignModel')->campaignsWithContentByCompany(
                    (int)$this->request->getPost('companySelected'),
                    $this->request->getPost('startDate'),
                    $this->request->getPost('endDate'),
                    $this->request->getPost('users'),
                    campaignChannelType::EMAIL
                );
                $communicationPlansSMS = model('CampaignModel')->campaignsWithContentByCompany(
                    (int)$this->request->getPost('companySelected'),
                    $this->request->getPost('startDate'),
                    $this->request->getPost('endDate'),
                    $this->request->getPost('users'),
                    campaignChannelType::SMS
                );
                $externalCampaign = model('ExternalCampaignModel')->listExternalCampaign(
                    (int)$this->request->getPost('companySelected'),
                    $this->request->getPost('startDate'),
                    $this->request->getPost('endDate'),
                    $this->request->getPost('users')
                );
                $communicationPlans = array_merge($communicationPlansEmail, $communicationPlansSMS, $externalCampaign);
                $usersSelected = $this->request->getPost('users');
                $startDateSelected = $this->request->getPost('startDate');
                $endDateSelected = $this->request->getPost('endDate');
            } else {
                $communicationPlansEmail = model('CampaignModel')->campaignsWithContentByCompany((int)$_SESSION['current_sub_company'], null, null, null, campaignChannelType::EMAIL);
                $communicationPlansSMS = model('CampaignModel')->campaignsWithContentByCompany((int)$_SESSION['current_sub_company'], null, null, null, campaignChannelType::SMS);
                $externalCampaign = model('ExternalCampaignModel')->listExternalCampaign((int)$_SESSION['current_sub_company']);
                $communicationPlans = array_merge($communicationPlansEmail, $communicationPlansSMS, $externalCampaign);
            }
        }
        $options = [
            'title'              => trad('Comunication Plan'),
            'metadescription'    => trad('List of Comunication Plan', 'user'),
            'content_only'       => false,
            'no_js'              => false,
            'nofollow'           => true,
            'top_content'        => [
                'layout/default/header',
                'layout/default/sidebar',
            ],
            'bottom_content'     => 'layout/default/footer',
            'content'            => 'layout/default/content',
            'page_title'         => '<i class="fas fa-chart-pie"></i>' . trad(
                ' Comunication Plan'
            ),
            'communicationPlans' => json_encode($communicationPlans, true),
            'users'              => $users,
            'companyId'          => $id,
            'usersSelected'      => $usersSelected,
            'positionCompany'    => $positionCompany,
            'startDateSelected'  => $startDateSelected,
            'endDateSelected'    => $endDateSelected,
        ];

        $layout = new Layout();
        $layout->load_assets('default');
        $layout->add_css(
            [
                LIBRARY . 'fullcalendar-3.4.0/fullcalendar.min',
                LIBRARY . 'fullcalendar-3.4.0/fullcalendar.print.min',
            ]
        );
        $layout->add_js(
            [
                LIBRARY . 'bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min',
                LIBRARY . 'fullcalendar-3.4.0/lib/jquery-ui.min',
                LIBRARY . 'fullcalendar-3.4.0/lib/moment.min',
                LIBRARY . 'fullcalendar-3.4.0/fullcalendar.min',
                ASSETS . 'js/calendar-plan',
                ASSETS . 'js/helper',
            ]
        );

        return $layout->view('communication_plan/list', $options);
    }

    public function ajaxExternalCampaignSave()
    {
        if (!isMemberAdmin()) {
            return accessDenied();
        }
        $data = json_decode($this->request->getPost('data'));
        return json_encode(model('ExternalCampaignModel', true, $this->db)->createExternalCampaign($data));
    }

    public function detail(int $id)
    {
        if (!isMemberAdmin()) {
            return accessDenied();
        }

        $options = [
            'title'            => trad('External Campaign detail'),
            'metadescription'  => trad('External Campaign detail'),
            'content_only'     => false,
            'no_js'            => false,
            'nofollow'         => true,
            'top_content'      => [
                'layout/default/header',
                'layout/default/sidebar',
            ],
            'bottom_content'   => 'layout/default/footer',
            'content'          => 'layout/default/content',
            'page_title'       => '<i class="fas fa-chart-pie"></i>' . trad(
                'External Campaign detail'
            ),
            'externalCampaign' => model('ExternalCampaignModel', true, $this->db)->get(
                $id
            ),
        ];

        $layout = new Layout();
        $layout->load_assets('default');

        return $layout->view('communication_plan/detail', $options);
    }
}
