<?php

namespace App\Http\Services\Facebook\Controllers;

use FacebookAds\Object\Lead as leadFB;
use App\Http\Services\Facebook\Controllers\Ad;

class Lead extends \App\Http\Services\Facebook\SDK
{

    public function get($id)
    {
        try {
            return $lead = (new leadFB($id))->getSelf(
                ['id', 'adset_name','field_data','form_id','ad_id']
              )->exportAllData();
        } catch (\Exception $e) {
            return false;
        }
        
    }

    public function ad($id, $relation = NULL){
        $ad = new Ad();
        $lead = Self::get($id);

        if(!$lead) return null;

        return $ad->$relation($lead['ad_id']);
    }
}

?>
