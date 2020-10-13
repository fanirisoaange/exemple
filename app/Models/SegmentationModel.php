<?php

namespace App\Models;

use App\Enum\campaignChannelType;
use CodeIgniter\Model;
use App\Enum\autoSegmentClearType;
use App\Enum\civilityType;
use App\Enum\campaignNatureType;
use App\Enum\AutoIntentionnistType;
use App\Enum\AutoOwned;

class SegmentationModel extends Model
{

    public function __construct()
    {
        parent::__construct();

        $this->builder = $this->db->table("campaign_segments");
    }

	public function editSegment(array $params){
        $dateTime = new \DateTime();
		$params['created']= $dateTime->format('Y-m-d H:i:s');

		if($this->builder->where(['id'=>$params['id']])->get()->getResult()){
			$this->builder->set($params)->where(['id'=>$params['id']])->update();
			return $params['id'];
		}
		else{
            if(isset($params['id'])) unset($params['id']);
			if($inserted=$this->builder->insert($params)){
				return $inserted->connID->insert_id;
			}
		}
	}

	public function get(int $id){
        return $this->builder->where('id', $id)->get()->getRowArray();
	}

	public function getSegmentations($where=[],$columns='*'){
            $this->builder->select($columns);
            foreach ($where as $key=>$values) {
                $this->builder->where($key,$values)->orderBy('id');
            }

        return $this->builder->get()->getResultArray();
    }

    public function formCreate($data = null){
        if ($data) {
            $data = trim_data($data);
        }
        $form = [
        	'action' => [
        		'field' => route_to('campaign_segmentation',$data['id'],$data['campaign_id']),
        		'attributes' => [
                    'id' => 'formSegmentation-'.$data['id'],
                    'onLoad' => "alert('ready')",
                    ],
        		'hidden' => ['campaign_id' => $data['campaign_id'],'id' => $data['id']]
        	],
        	'age_min' => [
        		'type' => 'hidden',
        		'name' => 'age_min',
        		'value' => $data['age_min'],
        		'id' => 'age_min-'.$data['id']
        	],
        	'age_max' => [
        		'type' => 'hidden',
        		'name' => 'age_max',
        		'value' => $data['age_max'],
        		'id' => 'age_max-'.$data['id'],
        	],
        	'nature_individual' =>[
        		'type' => 'checkbox',
        		'name' => 'natures[]',
        		'value' => campaignNatureType::INDIVIDUAL,
        		'id' => 'individual-'.$data['id'],
        		'checked' => in_array(campaignNatureType::INDIVIDUAL, explode(',', $data['nature']))
        	],
        	'nature_company' =>[
        		'type' => 'checkbox',
        		'name' => 'natures[]',
        		'value' => campaignNatureType::COMPANY,
        		'id' => 'company-'.$data['id'],
        		'checked' => in_array(campaignNatureType::COMPANY, explode(',', $data['nature']))
        	],
        	'civility_male' =>[
        		'type' => 'checkbox',
        		'name' => 'civilities[]',
        		'value' => civilityType::HOMME,
        		'id' => 'male-'.$data['id'],
        		'checked' => in_array(civilityType::HOMME, explode(',', $data['civility']))
        	],
        	'civility_female' =>[
        		'type' => 'checkbox',
        		'name' => 'civilities[]',
        		'value' => civilityType::FEMME,
        		'id' => 'female-'.$data['id'],
        		'checked' => in_array(civilityType::FEMME, explode(',', $data['civility']))
        	],
        	'car_owner_yes' =>[
        		'type' => 'checkbox',
        		'name' => 'car_owners[]',
        		'value' => AutoOwned::YES,
        		'id' => 'car_owner_yes-'.$data['id'],
        		'checked' => in_array(AutoOwned::YES, explode(',', $data['car_owner'])),
        	],
        	'car_owner_no' =>[
        		'type' => 'checkbox',
        		'name' => 'car_owners[]',
        		'value' => AutoOwned::NO,
        		'id' => 'car_owner_no-'.$data['id'],
        		'checked' => in_array(AutoOwned::NO, explode(',', $data['car_owner'])),
        	],
        	'auto_owned' =>[
        		'options' => autoSegmentClearType::getAll(),
        		'selected' => explode(',',$data['auto_owned']),
        		'name' => 'auto_owneds[]',
				'class' => 'form-control',
				'id' => 'auto_owned-'.$data['id'],
				'multiple'=>true,
        	],
            'is_auto_intention_yes' => [
                'name' => 'is_auto_intentions[]',
                'id' => 'is_auto_intention_yes-'.$data['id'],
				'value' => AutoIntentionnistType::YES,
				'checked'=> in_array(AutoIntentionnistType::YES, explode(',', $data['is_auto_intention'])),
                'class' => 'form-control'
			],
            'is_auto_intention_no' => [
                'name' => 'is_auto_intentions[]',
                'id' => 'is_auto_intention_no-'.$data['id'],
				'value' => AutoIntentionnistType::NO,
				'checked'=> in_array(AutoIntentionnistType::NO, explode(',', $data['is_auto_intention'])),
                'class' => 'form-control'
            ]
        ];
        return $form;
    }

    public function deleteSegment($id)
    {
        $this->builder->delete(['id' => $id]);
    }

    public function getSegmentRequest(int $campaignId, int $channel = null){
        $segments = $this->getSegmentations(['campaign_id' => $campaignId]);

        $companyId = $this->db->table('campaign_company')->where('campaign_id', $campaignId)
            ->get()->getRowArray()['company_id'];
        $companyLocalization = $this->db->table('company_localization')->where('company_id', $companyId)
            ->get()->getResultArray();

        $iris = [];
        foreach ($companyLocalization as $localization) {
            $iris[] = $localization['iris'];
        }

        if (!$segments && $channel == campaignChannelType::EMAIL) {
            return [['field' => 'email', 'condition' => 'equal', 'value_in' =>[
                "hotmail","orange","sfr","free","gmail","laposte","voila","bbox","yahoo","aol","noos","numericable"
            ]]];
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