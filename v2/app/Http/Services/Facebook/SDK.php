<?php

namespace App\Http\Services\Facebook;

use App;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\AdAccount;
use FacebookAds\Cursor;

abstract class SDK
{
    public function __construct($custom_token = null)
    {
        Cursor::setDefaultUseImplicitFetch(true);

        try{
            if($custom_token) {
                Api::init(env('FACEBOOK_APP_ID'), env('FACEBOOK_APP_SECRET'), $custom_token);
            }else{
                Api::init(env('FACEBOOK_APP_ID'), env('FACEBOOK_APP_SECRET'), env('FACEBOOK_ACCESS_TOKEN'));
            }

            $this->account = new AdAccount('act_39199831');

            $this->account->getSelf();
        } catch (\Exception $e){
            if($e->getCode() == '17'){
               return back()->with('error', 'API do Facebook está fora do ar no momento. Por favor, tente novamente mais tarde');
            }
            return back()->with('error', $e->getMessage());
        }
        
    }
}
