<?php

namespace App\Models;

use App\Enum\OrderStatus;
use App\Enum\Notifications;
use CodeIgniter\Model;

class ExternalCampaignModel extends Model
{
    public function __construct()
    {
        parent::__construct();

        $this->ionAuth = new \IonAuth\Libraries\IonAuth();
        $this->builder = $this->db->table("external_campaign");
    }

    public function listExternalCampaign(?int $subSompanyId, ?string $startDate = null, ?string $endDate = null, ?string $users = null)
    {
        $startDate = $startDate != '' ? $startDate : null;
        $endDate = $endDate != '' ? $endDate : null;
        $users = $users != '' ? $users : null;
        if ($startDate) {
            if (checkFormatDate($startDate)) {
                $startDate = new \DateTime($startDate);
                $startDate = $startDate->format('Y-m-d H:i:s');
            } else {
                return new \Exception(trad('invalid startDate'));
            }
        }
        if ($endDate) {
            if (checkFormatDate($endDate)) {
                $endDate = new \DateTime($endDate);
                $endDate = $endDate->format('Y-m-d H:i:s');
            } else {
                return new \Exception(trad('invalid endDate'));
            }
        }
        
        $builder = $this->builder->select(
            'external_campaign.id, external_campaign.name , "externalCampaign" as visual_name, '
            .'external_campaign.company_id, external_campaign.startDate, external_campaign.endDate'
            );
        if ($subSompanyId) {
            $builder->where('external_campaign.company_id', $subSompanyId);
        }
        if ($startDate) {
            $builder->where('external_campaign.endDate >= ', $startDate);
        }
        if ($endDate) {
            $builder->where('external_campaign.startDate <= ', $endDate);
        }
        return $builder->get()->getResultArray();
    }

    public function deleteExternalCampaign($id): void
    {
        $this->db->table('external_campaign')->delete(['id' => $id]);
    }

    public function createExternalCampaign($data): array
    {
        $startDate = $data->startDateCampaign != '' ? $data->startDateCampaign : null;
        $endDate = $data->endDateCampaign != '' ? $data->endDateCampaign : null;
        if ($startDate) {
            if (checkFormatDate($startDate)) {
                $startDate = new \DateTime($startDate);
                $startDate = $startDate->format('Y-m-d H:i:s');
            } else {
                return new \Exception(trad('invalid startDate'));
            }
        }
        if ($endDate) {
            if (checkFormatDate($endDate)) {
                $endDate = new \DateTime($endDate);
                $endDate = $endDate->format('Y-m-d H:i:s');
            } else {
                return new \Exception(trad('invalid endDate'));
            }
        }

        $createdAt = new \DateTime();
        $createdAt = $createdAt->format('Y-m-d H:i:s');

        $this->db->transStart();
        
        $externalCampaign = [
            'name'       => $data->nameCampaign,
            'startDate'  => $startDate,
            'endDate'    => $endDate,
            'company_id' => $data->companyId,
            'createdAt'  => $createdAt,
        ];
        $this->db->table('external_campaign')->insert($externalCampaign);

        return $externalCampaign;
    }

    public function updateExternalCampaign(string $orderId, $data)
    {
        $startDate = $data->startDateCampaign != '' ? $data->startDateCampaign : null;
        $endDate = $data->endDateCampaign != '' ? $data->endDateCampaign : null;
        if ($startDate) {
            if (checkFormatDate($startDate)) {
                $startDate = new \DateTime($startDate);
                $startDate = $startDate->format('Y-m-d H:i:s');
            } else {
                return new \Exception(trad('invalid startDate'));
            }
        }
        if ($endDate) {
            if (checkFormatDate($endDate)) {
                $endDate = new \DateTime($endDate);
                $endDate = $endDate->format('Y-m-d H:i:s');
            } else {
                return new \Exception(trad('invalid endDate'));
            }
        }
        $externalCampaign = [
            'id'         => $data->id,
            'name'       => $data->nameCampaign,
            'startDate'  => $startDate,
            'endDate'    => $endDate,
            'company_id' => $data->companyId,
        ];
        $this->db->table('external_campaign')->update($externalCampaign, ['id' => $data->id]);

        return $externalCampaign;
    }

    public function get(int $id)
    {
        $res =$this->builder->where('id', $id)->get()->getRowArray();
        return $res;
    }
}
