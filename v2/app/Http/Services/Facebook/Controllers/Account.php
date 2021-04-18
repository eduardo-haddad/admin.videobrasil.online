<?php

namespace App\Http\Services\Facebook\Controllers;

use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Fields\AdFields;
use FacebookAds\Object\Fields\AdPreviewFields;
use FacebookAds\Object\TargetingSearch;
use FacebookAds\Object\Search\TargetingSearchTypes;

class Account extends \App\Http\Services\Facebook\SDK
{
    public function __construct($custom_token = null){
        parent::__construct($custom_token);
    }

    public function get($id)
    {
        return $this->account->getSelf(array(
            AdFields::ID,
        ))->exportAllData();
    }

    public function ads(){
        return $this->account->getAds(['name'], [])->exportData();
    }

    public function campaigns(array $params = null){
        if(isset($params['filtering'])) $params['filtering'] = [json_encode($params['filtering'])];

        $reponse = $this->account->getCampaigns($params['fields'], [
            'filtering'     => isset($params['filtering']) ? $params['filtering'] : null ,
            'time_range'    => isset($params['time_range']) ? $params['time_range'] : null,
            'limit'         => isset($params['limit']) ? $params['limit'] : null,
            'date_preset'   => 'this_month'
        ]);

        return $reponse->getResponse()->getBody();
    }

    public function getForms(){
        $forms = $this->account->getLeadGenForms();

        return $forms->getObjects();
    }

    public function insights(array $data = null){
        if(isset($data['params']['filtering'])) $data['params']['filtering'] = [json_encode($data['params']['filtering'])];

        return $this->account->getInsights($data['fields'], $data['params'])->getResponse()->getBody(); 
    }

    public function getInterests($data) {
        $results = null;
        
        if($data->request && $data->search){
            $response = $this->account->getTargetingSearch([], ['q' => $data->search, 'locale' => 'pt_BR', 'whitelisted_types' => $data->target, 'limit' => '1000']);
            $results = $response->getResponse()->getContent()['data'];
        }else{
            $results = $this->account->getTargetingBrowse([], ['locale' => 'pt_BR', 'whitelisted_types' => $data->target, 'limit' => '1000'])->getResponse()->getContent()['data'];
        }

        return $results;
    }

    public function getReachEstimate($data) {
        return $this->account->getReachEstimate([], $data)->getResponse()->getContent()['data'];
    }
}

?>
