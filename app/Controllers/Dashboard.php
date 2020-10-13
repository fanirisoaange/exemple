<?php

namespace App\Controllers;

use App\Libraries\Layout;
use App\Libraries\Utils;
use App\Libraries\Traductions;
use App\Enum\campaignChannelType;

class Dashboard extends BaseController
{
    public function __construct()
    {
        helper(['order']);
        helper(['campaign']);
    }

    public function index()
    {
        $users = [];
        $usersSelected = null;
        $qtyCampaignEmailExecuted = 0;
        $qtyCampaignSMSExecuted = 0;
        $qtyCampaignEmailSend = 0;
        $qtyCampaignSMSSend = 0; 
        $budget = 0.00;
        $date = new \DateTime();
        if (isset($_SESSION['current_sub_company'])) {
            $startDate = $date->format('Y-m-01');
            $endDate = $date->format('Y-m-t');
            $dateMois = $date->format('Y-m');
            $currentSubCompany = $_SESSION['current_sub_company'];
            $qtyCampaignEmailExecuted = model('CampaignModel')->getCampaignByAjax(
                (int)$_SESSION['current_sub_company'],
                $startDate,
                $endDate,
                null,
                campaignChannelType::EMAIL
            );
            $qtyCampaignSMSExecuted = model('CampaignModel')->getCampaignByAjax(
                (int)$_SESSION['current_sub_company'],
                $startDate,
                $endDate,
                null,
                campaignChannelType::SMS
            );
            $qtyCampaignEmailSend = model('CampaignModel')->statCampaign(campaignChannelType::EMAIL, $currentSubCompany, $dateMois, $startDate, $endDate);
            $qtyCampaignSMSSend = model('CampaignModel')->statCampaign(campaignChannelType::SMS, $currentSubCompany, $dateMois, $startDate, $endDate);
            $users = model('CampaignModel')->getUsers($currentSubCompany);
            $budget = model('InvoiceModel')->getBudget($currentSubCompany, $startDate, $endDate);
        }
        $dateDeb = $date->format('01/m/Y');
        $dateFin = $date->format('t/m/Y');
        $labelStartDateEndDate = trad('From '.$dateDeb.' to '.$dateFin);
        $labelLeadGenerated = trad('Leads generated:'.$date->format(' 1 M, Y').' - '.$date->format('t M, Y'));
        
        $options = [
            'title'                    => trad('Dashboard'),
            'metadescription'          => trad('Dashboard', 'user'),
            'content_only'             => false,
            'no_js'                    => false,
            'nofollow'                 => true,
            'top_content'              => [
                'layout/default/header',
                'layout/default/sidebar',
            ],
            'bottom_content'           => 'layout/default/footer',
            'content'                  => 'layout/default/content',
            'page_title'               => '<i class="fas fa-chart-pie"></i>' . trad(
                ' Dashboard'
            ),
            'users'                    => $users,
            'labelStartDateEndDate'    => $labelStartDateEndDate,
            'labelLeadGenerated'       => $labelLeadGenerated,
            'usersSelected'            => $usersSelected,
            'qtyCampaignEmailExecuted' => $qtyCampaignEmailExecuted,
            'qtyCampaignSMSExecuted'   => $qtyCampaignSMSExecuted,
            'qtyCampaignEmailSend'     => $qtyCampaignEmailSend,
            'qtyCampaignSMSSend'       => $qtyCampaignSMSSend,
            'budget'                   => number_format($budget, 2, ',', ' '),
            'companyId'                => isset($_SESSION['current_main_company']) ? $_SESSION['current_main_company'] : null,
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
                LIBRARY . 'chart.js/Chart.min',
                ASSETS . 'js/dashboard',
                ASSETS . 'js/helper',
            ]
        );

        return $layout->view('dashboard/index', $options);
    }

    public function getCampaignByAjax(int $period = null)
    {
        $qtyCampaignEmailSend = 0;
        $qtyCampaignSMSSend = 0;
        $startDateSelected = null;
        $endDateSelected = null;
        $mailEnabled = false;
        $smsEnabled = false;
        $campaignChannelType = 0;
        $user = null;
        $budget = 0.00;
        $startDateInvoice = null;
        $endDateInvoice = null;
        $labelStartDateEndDate = '';
        $date = new \DateTime();
        $data = json_decode($this->request->getPost('data'));
        $campaignChannelType = (int)$data->campaignChannelType;
        $mailEnabled = in_array($campaignChannelType, [0, 1]) ? true : false;
        $smsEnabled = in_array($campaignChannelType, [0, 2]) ? true : false;
        $user = $data->user != '' ? $data->user : null;

        if ($period) {
            if ($period == 1) {
                $startDate = $date->format('Y-m-01');
                $endDate = $date->format('Y-m-t');
                $titleCardPiechart = trad('Monthly campaign recap');
            }
            if ($period == 3) {
                $startDate = $date->format('Y').'-'. getLatestMonth(3)[2]['month'] .'-01';
                $endDate = $date->format('Y-m-t');
                $titleCardPiechart = trad('Quarterly campaign recap');
            }
            if ($period == 12) {
                $startDate = $date->format('Y-01-01');
                $endDate = $date->format('Y-m-t');
                $titleCardPiechart = trad('Annual campaign recap');
            }
        } else {
            $startDate = $data->startDate != '' ? $data->startDate : null;
            $endDate = $data->endDate != '' ? $data->endDate : null;
        }

        if ($startDate) {
            if (checkFormatDate($startDate)) {
                $startDate = new \DateTime($startDate);
                $startDateSelected = $startDate;
                $startDateInvoice = $startDate->format('Y-m-d');
                $labelStartDateEndDate .= trad('From '.$startDateSelected->format('d/m/Y'));
                $startDate = $startDate->getTimestamp();
            } else {
                return new \Exception(trad('invalid startDate'));
            }
        }
        if ($endDate) {
            if (checkFormatDate($endDate)) {
                $endDate = new \DateTime($endDate);
                $endDateSelected = $endDate;
                $endDateInvoice = $endDate->format('Y-m-d');
                $labelStartDateEndDate .= trad(' To '.$endDateSelected->format('d/m/Y'));
                $endDate = $endDate->getTimestamp();
            } else {
                return new \Exception(trad('invalid endDate'));
            }
        }
        
        $dateDeb = $date->format('01/m/Y');
        $dateFin = $date->format('t/m/Y');
        $dateMois = $date->format('Y-m');
        $labelStartDateEndDate = $labelStartDateEndDate == '' ? trad('From '.$dateDeb.' to '.$dateFin) : $labelStartDateEndDate;
        if (isset($_SESSION['current_sub_company'])) {
            $currentSubCompany = $_SESSION['current_sub_company'];
            $startDateCampaign = $startDateInvoice ? $startDateInvoice : $date->format('Y-01-01');
            $endDateCampaign = $endDateInvoice ? $endDateInvoice : $date->format('Y-m-t');
            $qtyCampaignEmailSend = model('CampaignModel')->statCampaign(campaignChannelType::EMAIL, $currentSubCompany, $dateMois, $startDateCampaign, $endDateCampaign);
            $qtyCampaignSMSSend = model('CampaignModel')->statCampaign(campaignChannelType::SMS, $currentSubCompany, $dateMois, $startDateCampaign, $endDateCampaign);
            $users = model('CampaignModel')->getUsers($currentSubCompany);
            $budget = model('InvoiceModel')->getBudget($currentSubCompany, $startDateInvoice, $endDateInvoice);
        }
        if ($campaignChannelType == 0) {
            $data = [
                'numberMailExecuted' => model('CampaignModel')->getCampaignByAjax(
                    (int)$_SESSION['current_sub_company'],
                    $startDate,
                    $endDate,
                    $user,
                    campaignChannelType::EMAIL
                ),
                'numberSMSExecuted'  => model('CampaignModel')->getCampaignByAjax(
                    (int)$_SESSION['current_sub_company'],
                    $startDate,
                    $endDate,
                    $user,
                    campaignChannelType::SMS
                ),
            ];
        } else {
            $numberExecuted = model('CampaignModel')->getCampaignByAjax(
                (int)$_SESSION['current_sub_company'],
                $startDate,
                $endDate,
                $user,
                $campaignChannelType
            );
            $data = [
                'numberMailExecuted' => $mailEnabled ? $numberExecuted : 0,
                'numberSMSExecuted'  => $smsEnabled ? $numberExecuted : 0,
            ];
        }
        $startDateSelected = $startDateSelected ? $startDateSelected->format('d/m/Y') : $date->format('01/01/Y');
        $endDateSelected = $endDateSelected ? $endDateSelected->format('d/m/Y') : $date->format('t/m/Y');
        $titleCardPiechart = !$period ? trad('Recap of the campaigns from '.$startDateSelected.' to '.$endDateSelected) : $titleCardPiechart;
        
        $additionalData = [
            'mailEnabled'           => $mailEnabled,
            'smsEnabled'            => $smsEnabled,
            'startDateSelected'     => $startDateSelected,
            'endDateSelected'       => $endDateSelected,
            'labelStartDateEndDate' => $labelStartDateEndDate,
            'qtyCampaignEmailSend'  => $qtyCampaignEmailSend,
            'qtyCampaignSMSSend'    => $qtyCampaignSMSSend,
            'budget'                => number_format($budget, 2, ',', ' '),
            'titleCardPiechart'     => $titleCardPiechart,
            'period'                => $period,
        ];

        return json_encode(array_merge($data, $additionalData));
    }
}
