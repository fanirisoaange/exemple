<?php

use Config\Services;
use App\Enum\CampaignStatus;
use App\Enum\CampaignType;
use App\Models\CampaignModel;
use App\Enum\campaignChannelType;

if (!function_exists('hasCampaignAccess')) {
    function hasCampaignAccess(string $companyId = null)
    {
        return true;
    }
}

if (!function_exists('campaign_status')) {

    /**
     * Return status text for order
     *
     * @param int $campaignStatus
     * @throws Exception
     * @internal param int $orderStatus The database value of the status
     *
     */
    function campaign_status(int $campaignStatus)
    {
        $status = CampaignStatus::getDescriptionById($campaignStatus);

        if (!$status) throw new Exception('invalid campaign status');

        switch ($campaignStatus) {
            case CampaignStatus::VALIDATED:
                $badge = 'success';
                $value = 'validated';
                break;
            case CampaignStatus::CAMPAIGN_SUBMITTED:
                $badge = 'purple';
                $value = 'campaign submitted';
                break;
            default:
                $badge = 'warning';
                $value = 'in progress';
                break;
        }

        echo '<span class="badge badge-' . $badge . '">' . trad($value) . '</span>';
    }
}

if (!function_exists('campaign_type')) {
    function campaign_type(int $campaignType)
    {

        $type = CampaignType::getDescriptionById($campaignType);

        if (!$type) throw new Exception('invalid campaign type');

        echo trad($type);
    }
}

if (!function_exists("canDeleteCampagn")) {
    function canDeleteCampagn(int $status)
    {
        return CampaignStatus::VALIDATED !== $status;
    }
}

if (!function_exists("canEditCampagn")) {
    function canEditCampagn(int $campaignId,bool $redirect = false)
    {
        return CampaignStatus::VALIDATED !== $status;
    }
}

if (!function_exists("choiceCampaignType")) {

    /**
     * Return campaign type or channel type
     *
     * @param int $type
     * @param string $section
     * @return null|string
     * @throws Exception
     */
    function choiceCampaignType(int $type = null, string $section)
    {

        if ($type) {
            switch ($section) {
                case "campaign":
                    $label = CampaignType::getDescriptionById($type);
                    break;
                case "channel":
                    $label = campaignChannelType::getDescriptionById($type);
                    break;
                default:
                    throw new Exception('invalid section');
            }

            return (
                '<span class="badge badge-primary">'
                . ucfirst(trad($label)) .
                '</span>'
            );
        }

        return null;
    }
}


if (!function_exists("campaignIconStatus")) {

    /**
     * Return status icon
     *
     * @param int $type
     * @return string
     *
     */
    function campaignIconStatus(int $type = null): string
    {

        if ($type) {
            return '<i class="fa fa-check-circle"></i>';
        }

        return '<i class="fa fa-times-circle"></i>';
    }
}


if (!function_exists("campaignWorkflow")) {

    /**
     * Return array of workflow steps
     *
     * @param int $status
     * @param int $campaignId
     * @param string $currentUrl
     * @return array
     * @throws Exception
     */
    function campaignWorkflow(int $status, int $campaignId, string $currentUrl): array
    {
        $campaignModel = new CampaignModel();

        if ($campaignId) {
            $campaign = $campaignModel->findCampaignByID($campaignId);

            if (!$campaign) {
                throw new Exception("Campaign not found for this ID");
            }
        }

        $steps = [
            [
                'label' => trad('Campaign', 'campaign'),
                'icon' => 'fas fa-bullhorn',
                'status' => 'enabled',
                'activeLink' => uri_string() == "campaign" ? "active" : "",
                'href' => ($status <= CampaignStatus::CREATE || $status > CampaignStatus::CREATE) ? route_to('edit_campaign', $campaignId) : route_to('create_campaign', $campaignId)
            ],
            [
                'label' => trad('Channel', 'campaign'),
                'icon' => 'fas fa-envelope',
                'status' => $status > 0 ? 'enabled' : 'disabled',
                'activeLink' => $currentUrl == "channel" ? "active" : "",
                'href' => $status <= CampaignStatus::CREATE ? route_to('create_channel', $campaignId) : route_to('edit_channel', $campaignId)
            ],
            [
                'label' => trad('Segmentation', 'campaign'),
                'icon' => 'fas fa-user-cog',
                'status' => $status > CampaignStatus::CREATE ? 'enabled' : 'disabled',
                'activeLink' => $currentUrl == "segmentation" ? "active" : "",
                'href' => $status <= CampaignStatus::CHANNEL ? route_to('create_segmentation', $campaignId) : route_to('edit_segmentation', $campaignId)
            ],
            [
                'label' => trad('Content', 'campaign'),
                'icon' => 'fas fa-images',
                'status' => $status > CampaignStatus::CHANNEL ? 'enabled' : 'disabled',
                'activeLink' => $currentUrl == "content" ? "active" : "",
                'href' => $status <= CampaignStatus::SEGMENTATION ? route_to('create_content', $campaignId) : route_to('edit_content', $campaignId)
            ],
            [
                'label' => trad('Planning', 'campaign'),
                'icon' => 'fas fa-calendar-alt',
                'status' => $status > CampaignStatus::SEGMENTATION ? 'enabled' : 'disabled',
                'activeLink' => $currentUrl == "planning" ? "active" : "",
                'href' => $status <= CampaignStatus::CONTENT ? route_to('create_planning', $campaignId) : route_to('edit_planning', $campaignId)
            ]
        ];

        return $steps;
    }
}

if (!function_exists("disableButtonCampaign")) {

    /**
     * Disabled button validate campaign
     *
     * @param $status
     * @return bool
     * @internal param $campaign
     */
    function disableButtonCampaign($status)
    {
        if ($status >= CampaignStatus::PLANNING) {
            return true;
        }

        return false;
    }
}

if (!function_exists("parseHTML")) {

    /**
     * Parse HTML content
     * @param string $body
     * @return string
     */
    function parseHTML(string $body): string
    {
        return html_entity_decode($body);
    }
}

if (!function_exists("getClientDataWorkCompany")) {

    /**
     * Get client datawork
     * @param int $campaignId
     * @return null
     */
    function getClientDataWorkCompany(int $campaignId)
    {
        $campaignModel = new CampaignModel();
        $company = $campaignModel->findClientDataWork($campaignId);

        $idClient = null;
        if ($company->id_client_datawork == null) {
            $result = connectApi("POST", "/creation", [
                'nom'                => $company->fiscal_name,
                'type_client'        => 'national',
                'id_client_national' => null
            ]);

            $idClient = json_decode($result, true)['id_client'];
            $campaignModel->updateClientDataWork($campaignId, $idClient);
        }

        return $company->id_client_datawork ?? $idClient;
    }
}

if (!function_exists("getLatestMonth")) {
    /**
     * Get Latest Month
     * @param int $dernierMois
     * @return null
     */
    function getLatestMonth($dernierMois){
        $arParMois = array();
        $dateCourant = date("Y-m-d");
        for($i = 0; $i < $dernierMois; $i++){
            if($i === 0){
                $arParMois[$i] = array(
                    'month' => date("m")
                );
            }else{
                //- 1 mois à la date du jour
                $mois = date("m", strtotime("-1 month", strtotime($dateCourant)));
                $arParMois[$i] = array(
                    'month' => $mois
                );
                $dateCourant = date("Y-".$mois."-d");
            }
        }
        return $arParMois;
    }
}