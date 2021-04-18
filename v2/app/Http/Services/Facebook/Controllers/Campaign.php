<?php

namespace App\Http\Services\Facebook\Controllers;

use FacebookAds\Object\AdAccount;
use FacebookAds\Object\Campaign as campaignFB;
use FacebookAds\Object\Fields\CampaignFields;
use FacebookAds\Object\AdSet as AdsetFB;
use Facebook\Facebook;
use GuzzleHttp\Client as GuzzClient;
use App\Http\Services\Facebook\Components\Paginate;

class Campaign extends \App\Http\Services\Facebook\SDK
{
    public function all($fields, $params = null){
        $campaigns = $this->account->getCampaigns($fields, $params)->getResponse()->getContent()['data'];

        //Order by status active
        usort($campaigns, function($a, $b) {
            return $a['status'] <=> $b['status'];
        });

        return $campaigns;
    }

    public function create($name)
    {
        $campaign = $this->account->createCampaign([], [
            CampaignFields::NAME                => $name, 
            CampaignFields::OBJECTIVE           => 'LEAD_GENERATION', 
            CampaignFields::BUYING_TYPE         => 'AUCTION',
            CampaignFields::STATUS              => 'PAUSED',
            CampaignFields::SPECIAL_AD_CATEGORY => 'none'
        ]);

        return $campaign;
    }

    public function update($data)
    {
        $campaign = new campaignFB($data->campaign_id);
        $data = Self::parseData($data->toArray());
        $campaign = $campaign->updateSelf([], $data);

        return $campaign;
    }

    public function copy($data){
        $client = new GuzzClient();

        $campaign = new campaignFB($data->campaign_id);

        $campaignCopy = $campaign->createCopy([], [
            'status' => $data->status
        ]);

        $campaignCopy = new campaignFB($campaignCopy->copied_campaign_id);

        $campaignCopy->updateSelf([], [
            'name' => $data->name
        ]);

        $adsets = $campaign->getAdSets()->getObjects();

        foreach($adsets as $adset){
            $adset->createCopy([], [
                'campaign_id' => $campaignCopy->id,
                'deep_copy' => 'true',
                'rename_options' => json_encode([
                    'rename_strategy' => 'NO_RENAME'
                ])
            ]);
        }

        return $campaignCopy;
    }

    public function delete($campaign_id) {
        $campaign_id = new campaignFB($campaign_id);
        $campaign_id->deleteSelf();
    }

    public function getAdsets($campaign_id, $fields, $params, $paginate = null) {
        $data = [];
        $campaign = new campaignFB($campaign_id);

        $response = $campaign->getAdsets($fields, $params);

        if($paginate){
            Paginate::make($response, $paginate['index']);
            $data['paging'] = isset($response->getResponse()->getContent()['paging']) ? $response->getResponse()->getContent()['paging'] : false;
        }

        usort($response->getResponse()->getContent()['data'], function($a, $b) {
            return $a['status'] <=> $b['status'];
        });

        foreach($response->getResponse()->getContent()['data'] as $adset){
            $adsetObj = new AdsetFB();
            $adsetObj->setData($adset);
            $data['itens'][] = $adsetObj;
        }

        return $data;
    }

    function parseData($data) {
        $parseData = [];

        if(isset($data['name'])) $parseData[CampaignFields::NAME] = $data['name'];
        if(isset($data['bid_strategy'])) $parseData[CampaignFields::BID_STRATEGY] = $data['bid_strategy'];
        if(isset($data['budget'])) $parseData[CampaignFields::DAILY_BUDGET] = preg_replace('/\D/', '', $data['budget']);
        if(isset($data['status'])) $parseData[CampaignFields::STATUS] = $data['status'];
        if(isset($data['adset_budgets'])) $parseData['adset_budgets'] = $data['adset_budgets'];

        return $parseData;
    }
}

?>
