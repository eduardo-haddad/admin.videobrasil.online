<?php

namespace App\Http\Services\Facebook\Controllers;

use FacebookAds\Object\Ad as adFB;
use FacebookAds\Object\Fields\AdFields;
use FacebookAds\Object\Fields\AdPreviewFields;
use FacebookAds\Object\AdAccount;

class Ad extends \App\Http\Services\Facebook\SDK
{
    public function get($id, $fields)
    {
        $ad = new adFB($id);
        return $ad->getSelf($fields);
    }

    public function preview($id){
        $ad = new adFB($id);

        return $ad->getPreviews([], ['ad_format' => 'DESKTOP_FEED_STANDARD'])->current();
    }

    public function create($name, $adset_id, $creative){
        $ad = $this->account->createAd([], [
            AdFields::CREATIVE  => $creative,
            AdFields::NAME      => $name,
            AdFields::ADSET_ID  => $adset_id,
            AdFields::STATUS    => 'ACTIVE',
        ]);

        return $ad;
    }

    public function update($ad_id, $creative, $data = null){
        $ad = new AdFB($ad_id);
        $ad = $ad->updateSelf([], [
            AdFields::CREATIVE  => $creative['new']->getData(),
            AdFields::NAME => $data['ad_name'],
            AdFields::STATUS => 'ACTIVE']
        );

        $creative['old']->deleteSelf();

        return $ad;
    }

    public function delete($ad_id) {
        $ad = new AdFB($ad_id);
        return $ad->deleteSelf();
    }
}

?>
