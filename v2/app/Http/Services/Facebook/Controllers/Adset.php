<?php

namespace App\Http\Services\Facebook\Controllers;

use FacebookAds\Object\AdSet as AdsetFB;
use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdSetFields;
use FacebookAds\Object\Fields\TargetingFields;
use FacebookAds\Object\Values\AdSetBillingEventValues;
use FacebookAds\Object\Values\AdSetOptimizationGoalValues;
use FacebookAds\Object\Targeting;
use FacebookAds\Object\TargetingSearch;
use FacebookAds\Object\Search\TargetingSearchTypes;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\AuthorizationException;

class Adset extends \App\Http\Services\Facebook\SDK
{
    public function get($id, $fields = null){
        $adset = new AdsetFB($id);

        if($fields){
            $adset = $adset->getSelf($fields);
        }else{
            $adset = $adset->getSelf();
        }

        return $adset;
    }
    public function create($data, $campaign_id)
    {
        $data = Self::parseUpdateData($data);
        $data = array_merge($data, [
            AdSetFields::CAMPAIGN_ID    => $campaign_id,
            AdSetFields::BILLING_EVENT  => AdSetBillingEventValues::IMPRESSIONS,
            AdSetFields::PROMOTED_OBJECT    => [
                'page_id' => '277823286593'
            ],
        ]);

        Self::validateData($data);

        $adset = $this->account->createAdSet([], $data);

        return $adset;
    }

    public function update($data) {
        $adset = new AdsetFB($data['adset_id']);
        $data = Self::parseUpdateData($data);
        $adset->updateSelf([], $data);

        return $adset;
    }

    public function delete($adset_id) {
        $adset = new AdsetFB($adset_id);
        return $adset->deleteSelf();
    }

    public function copy($data) {
        $adset = new adsetFB($data->adset_id);

        $adsetCopy = $adset->createCopy([], [
            'campaign_id'       => $data->campaign_id,
            'status_option'     => $data->status,
            'deep_copy'         => 'true',
            'rename_options'    => json_encode([
                'rename_strategy' => 'NO_RENAME'
            ])
        ]);

        $adsetCopy = new adsetFB($adsetCopy->copied_adset_id);

        $adsetCopy->updateSelf([], [
            'name'          => $data->name
        ]);

        return $adsetCopy;
    }

    public function getAds($adset_id, $fields, $params = null){
        $adset = new AdsetFB($adset_id);

        if($params) {
            $response = $adset->getAds($fields, $params);
        }else{
            $response = $adset->getAds($fields, []);
        }

        return $response->getResponse()->getContent();
    }

    public function parseZip($data){
        $ceps['raw'] = preg_split('/\r\n|\r|\n/', $data);

        foreach($ceps['raw'] as $cep){
            $ceps['fb'][]['key'] = 'BR:'.substr($cep, 0, 5);
        }

        unset($ceps['raw']);
        $ceps = array_map("unserialize", array_unique(array_map("serialize", $ceps['fb'])));
        sort($ceps);

        foreach($ceps as $key => $value){
            $result = TargetingSearch::search(
                TargetingSearchTypes::GEOLOCATION,
                null,
                str_replace('BR:', '', $value['key']),
                array(
                  'location_types' => array('zip'),
                ));

            if(!empty($result->getResponse()->getContent()['data'])) {
                if(!in_array('Brazil', array_column($result->getResponse()->getContent()['data'], 'country_name'))) {
                    unset($ceps[$key]);
                }
            }else{
                unset($ceps[$key]);
            }
        }

        sort($ceps);
        return $ceps;
    }

    function parseUpdateData($data) {
        $parseData = [];

        if(isset($data['name'])) $parseData[AdSetFields::NAME] = $data['name'];
        if(isset($data['budget']) && preg_replace('/\D/', '', $data['budget']) !== '000') $parseData[AdSetFields::DAILY_BUDGET] = preg_replace('/\D/', '', $data['budget']);
        if(isset($data['bid'])) $parseData[AdSetFields::BID_AMOUNT] = preg_replace('/\D/', '', $data['bid']);
        if(isset($data['status'])) $parseData[AdSetFields::STATUS] = $data['status'];
        if(isset($data['age_min'])) $parseData[AdSetFields::TARGETING][TargetingFields::AGE_MIN] = $data['age_min'];
        if(isset($data['age_max'])) $parseData[AdSetFields::TARGETING][TargetingFields::AGE_MAX] = $data['age_max'];

        if(isset($data['cep'])) {
            $ceps = Self::parseZip($data['cep']);
            $parseData[AdSetFields::TARGETING][TargetingFields::GEO_LOCATIONS]['zips'] = $ceps;
        }

        if(!isset($data['cep']) && isset($data['current_zips'])) {
            $parseData[AdSetFields::TARGETING][TargetingFields::GEO_LOCATIONS]['zips'] = $data['current_zips'];
        }

        if(isset($data['interest'])) {
            foreach($data['interest'] as $interest) {
                $parseData[AdSetFields::TARGETING][TargetingFields::INTERESTS][] = ['id' => $interest];
            }
        }

        if(isset($data['behaviors'])) {
            foreach($data['behaviors'] as $behavior) {
                $parseData[AdSetFields::TARGETING][TargetingFields::BEHAVIORS][] = ['id' => $behavior];
            }
        }

        if(isset($data['life_events'])) {
            foreach($data['life_events'] as $event) {
                $parseData[AdSetFields::TARGETING][TargetingFields::LIFE_EVENTS][] = ['id' => $event];
            }
        }

        if(isset($data['exclude_interest'])) {
            foreach($data['exclude_interest'] as $interest) {
                $parseData[AdSetFields::TARGETING][TargetingFields::EXCLUSIONS][TargetingFields::INTERESTS][] = ['id' => $interest];
            }
        }

        if(isset($data['exclude_behaviors'])) {
            foreach($data['exclude_behaviors'] as $behavior) {
                $parseData[AdSetFields::TARGETING][TargetingFields::EXCLUSIONS][TargetingFields::BEHAVIORS][] = ['id' => $behavior];
            }
        }

        if(isset($data['life_events'])) {
            foreach($data['life_events'] as $event) {
                $parseData[AdSetFields::TARGETING][TargetingFields::EXCLUSIONS][TargetingFields::LIFE_EVENTS][] = ['id' => $event];
            }
        }

        return $parseData;
    }

    public function validateData($data) {
        $response['valid'] = true;
        if(!isset($data['targeting']))                          $response = ['valid' => false, 'field' => 'targeting'];
        if(!isset($data['targeting']['geo_locations']))         $response = ['valid' => false, 'field' => 'geo_locations'];
        if(!isset($data['targeting']['geo_locations']['zips'])) $response = ['valid' => false, 'field' => 'zips'];
        if(!isset($data['targeting']['interests']))             $response = ['valid' => false, 'field' => 'interests'];

        if(!$response['valid']) {
            throw new \Exception('Dados inválidos. Campo com problema: '.$response['field']);
        }

        return $response['valid'];
    }
}

?>
