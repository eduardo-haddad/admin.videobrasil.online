<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Client\Client;
use Jenssegers\Date\Date;
use App\Http\Services\Facebook\Controllers\Account;
use App\Http\Services\Facebook\Controllers\Campaign;
use App\Http\Services\Facebook\Controllers\Adset;
use App\Http\Services\Facebook\Controllers\Creative;
use App\Http\Services\Facebook\Controllers\Ad;
use App\Http\Services\Facebook\Controllers\Page;
use App\Http\Services\Facebook\Controllers\BatchRequest;
use App\Http\Services\Facebook\Jobs\AdImageGenerate;
use \Carbon\Carbon;


trait CampaignFacebookTrait
{
    /**
     * Display Facebook Campaigns from current month
     */

    public function indexCampaignFacebook(Request $request)
    {
        Date::setLocale('pt_BR');
        $account = new Account();
        $campaign = new Campaign();

        $date = $request->month && $request->year ? strtoupper($request->month.$request->year) : false;

        //Get campaigns from current month on facebook
        $requestFB = [
            'fields'        => ['id', 'name', 'status', 'created_time', 'insights.date_preset(lifetime){cost_per_action_type}'],
            'params'        =>  [
                'filtering'     => [[
                    'field'     => 'campaign.name',
                    'operator'  => 'CONTAIN',
                    'value'     => $date ? $date : substr(Date::now()->format('F'), 0, 3).Date::now()->year
                ]],
                'limit'         => '100',
                'date_preset'   => 'lifetime'
            ]
        ];

        if($request->search_string) {
            $requestFB['params']['filtering'][0]['value'] = strtoupper($request->search_string);
        }

        $campaigns = $campaign->all($requestFB['fields'], $requestFB['params']);

        if($request->by_client) {
            return redirect()->route('campaigns.facebook.create', $request->by_client);
        }

        if($request->ajax()) {
            return response($campaigns, 200);
        } 

        return view('campaigns.facebook.index', ['campaigns' => $campaigns]);
    }

    /**
     * Display Campaign Facebook create
     */
    public function createCampaignFacebook(Client $client)
    {
        //Get facebook Lead gen forms
        $page = new Page();
        $forms = $page->getForms();

        Date::setLocale('pt_BR');

        $data = [
            'campaign_name' => strtoupper('LG_'.$client->user_name.'_'.$client->user_id.'_'.substr(Date::now()->format('F'), 0, 3).Date::now()->year),
            'adset_name'    => strtoupper('LG_ALL_'.$client->user_name.'_'.$client->user_id.'_'),
            'ad_name'       => 'IMG1 - '.Date::now()->format('Y-m-d'),
            'forms'         => $forms
        ];

        return view('campaigns.facebook.create', ['data' => $data, 'client' => $client]);
    }

    /**
     * Display Campaign Facebook to edit
     */
    public function editCampaignFacebook(Request $request)
    {
        $client = Client::find(explode('_', $request->name)[2]);

        //Get facebook Lead gen forms
        $page = new Page();
        $forms = $page->getForms();

        Date::setLocale('pt_BR');

        $data = [
            'campaign_name' => $request->campaign_name,
            'campaign_id'   => $request->campaign_id,
            'adset_name'    => strtoupper('LG_ALL_'.$client->user_name.'_'.$client->user_id.'_'),
            'ad_name'       => 'IMG1 - '.Date::now()->format('Y-m-d'),
            'forms'         => $forms,
        ];

        return view('campaigns.facebook.create', ['data' => $data, 'client' => $client]);
    }

    /**
     * Send requests via Graph API to create a Campaign/Adset/Ad/Creative
     */
    public function storeCampaignFacebook(Request $request)
    {
        if(!$request->campaign_id){
            $campaign = new Campaign();
            $campaign = $campaign->create($request->name);
        }else{
            $campaign = (object)['id' => $request->campaign_id];
        }

        foreach($request->adset as $adset){

            if(isset($adset['creative']['images'])){
                $img_path = AdImageGenerate::generate($adset['creative']['images'], 'multiple');
            }elseif(isset($adset['creative']['file'])){
                $img_path = AdImageGenerate::generate($adset['creative']['file'], 'single');
            }else{
                return back()->with('error', 'Selecione imagens para os anúncios');
            }

            try {
                $adsetFb = new Adset();
                $adsetFb = $adsetFb->create($adset, $campaign->id);
                
                $creative = new Creative();
                $creativeImg = $creative->createImg($img_path);
                $creativeImg = array_shift($creativeImg);
                $creativeAd = $creative->create($adset['creative'], $creativeImg);
    
                $ad = new Ad();
                $ad = $ad->create($adset['creative']['ad_name'], $adsetFb->id, $creativeAd);
            } catch (\FacebookAds\Http\Exception\AuthorizationException $e) {
                $error = $e->getResponse()->getContent();

                $msg = isset($error['error']['error_user_title']) ? $error['error']['error_user_title'] : '';
                $msg .= isset($error['error']['error_user_msg']) ? $error['error']['error_user_msg'] : '';
                $msg .= isset($error['error']['message']) ? $error['error']['message'] : '';

                return back()->with('error', $msg)->withInputs($request->all());
            } catch (\Exception $e) {
                return back()->with('error', $e->getMessage())->withInputs($request->all());
            }

        }

        return redirect()->route('campaigns.facebook.index')->with('success', 'Campanha '.$request->name.($request->campaign_id ? ' atualizada':' criada').' com sucesso!');
    }

    public function getCreativePreview(Request $request)
    {
        try{
            if(isset($request->adset[$request->active_adset]['creative']['images'])){
                $img_path = AdImageGenerate::generate($request->adset[$request->active_adset]['creative']['images'], 'multiple');
            }elseif($request->adset[$request->active_adset]['creative']['file']){
                $img_path = AdImageGenerate::generate($request->adset[$request->active_adset]['creative']['file'], 'single');
            }elseif($request->images){
                $img_path = AdImageGenerate::generate($request->images, 'multiple');
            }elseif($request->file){
                $img_path = AdImageGenerate::generate($request->file, 'single');
            }
    
            $creative = new Creative();
            $creativeImg = $creative->createImg($img_path);
            $creativeImg = array_shift($creativeImg);
    
            if($request->adset){
                return $creative->getPreview($request->adset[$request->active_adset]['creative'], $creativeImg);
            }else{
                $update = $request->creative_id ? true : false;
                return $creative->getPreview($request->toArray(), $creativeImg, $update);
            }
        } catch (\FacebookAds\Http\Exception\AuthorizationException $e) {
            $error = $e->getResponse()->getContent();
            $msg = isset($error['error']['error_user_title']) ? $error['error']['error_user_title'] : '';
            $msg .= isset($error['error']['error_user_msg']) ? $error['error']['error_user_msg'] : '';
            $msg .= isset($error['error']['message']) ? $error['error']['message'] : '';
                
            if($request->ajax()) {
                return response($msg, 500);
            }

            return back()->with('error', $msg)->withInput($request->all());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInputs($request->all());
        }

    }

    public function copyCampaign(Request $request) {
        try{
            $ca = new Campaign();
            $copy = $ca->copy($request);
        } catch (\FacebookAds\Http\Exception\AuthorizationException $e){
            $error = $e->getResponse()->getContent();
            $msg = isset($error['error']['error_user_title']) ? $error['error']['error_user_title'] : '';
            $msg .= isset($error['error']['error_user_msg']) ? $error['error']['error_user_msg'] : '';
            $msg .= isset($error['error']['message']) ? $error['error']['message'] : '';
                
            if($request->ajax()) {
                return response($msg, 500);
            }

            return back()->with('error', $msg)->withInput($request->all());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInputs($request->all());
        }

        if($request->ajax()) {
            return response('Campanha '.$request->campaign_id. ' clonada.', 200);
        }

        return redirect()->route('campaigns.facebook.index')->with('success', 'Campanha '.$request->name.' clonada com sucesso! ');
    }

    public function updateCampaign(Request $request) {
        try{
            $campaign = new Campaign();
            $campaign = $campaign->update($request);
        } catch (\FacebookAds\Http\Exception\AuthorizationException $e){
            $error = $e->getResponse()->getContent();
            $msg = isset($error['error']['error_user_title']) ? $error['error']['error_user_title'] : '';
            $msg .= isset($error['error']['error_user_msg']) ? $error['error']['error_user_msg'] : '';
            $msg .= isset($error['error']['message']) ? $error['error']['message'] : '';
                
            if($request->ajax()) {
                return response($msg, 500);
            }

            return back()->with('error', $msg)->withInput($request->all());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInputs($request->all());
        }

        if($request->ajax()) {
            return response('Campanha atualizada', 200);
        }

        return back()->with('success', 'Campanha '.$request->name.' editada com sucesso! ');
    }

    public function deleteCampaign(Request $request) {
        try{
            $campaign = new Campaign();
            $campaign->delete($request->campaign_id);
        } catch (\FacebookAds\Http\Exception\AuthorizationException $e){
            $error = $e->getResponse()->getContent();
            $msg = isset($error['error']['error_user_title']) ? $error['error']['error_user_title'] : '';
            $msg .= isset($error['error']['error_user_msg']) ? $error['error']['error_user_msg'] : '';
            $msg .= isset($error['error']['message']) ? $error['error']['message'] : '';
                
            if($request->ajax()) {
                return response($msg, 500);
            }

            return back()->with('error', $msg)->withInput($request->all());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInputs($request->all());
        }

        if($request->ajax()) {
            return response('Campanha '.$request->campaign_id. ' deletada.', 200);
        }

        return back()->with('success', 'Campanha '.$request->campaign_id.' deletada.');
    }

    public function adsets(Request $request) {
        $filtering = false;
        if($request->search_string) {
            $filtering = [json_encode([
                'field'         => 'adset.name',
                'operator'      => 'CONTAIN',
                'value'         => strtoupper($request->search_string),
            ])];
        }
        $ca = new Campaign();
        $fields = ['id', 'name', 'bid_amount', 'daily_budget', 'lifetime_imps', 'status', 'campaign{name, bid_strategy, daily_budget}', 'insights.date_preset(lifetime){spend,cost_per_action_type,actions,impressions}'];
        $params = [
            'filtering'     => $filtering,
            'date_preset'   => 'lifetime',
            'limit' => 10
        ];
        $paginate = ['paging' => $request->paging, 'index' => $request->index];

        try{
            $adsets = $ca->getAdsets($request->campaign_id, $fields, $params, $paginate);

            $index = false;
            if($request->index){
                $index = $request->index+1;
            }
        } catch (\FacebookAds\Http\Exception\AuthorizationException $e){
            $error = $e->getResponse()->getContent();
            $msg = isset($error['error']['error_user_title']) ? $error['error']['error_user_title'] : '';
            $msg .= isset($error['error']['error_user_msg']) ? $error['error']['error_user_msg'] : '';
            $msg .= isset($error['error']['message']) ? $error['error']['message'] : '';
                
            if($request->ajax()) {
                return response($msg, 500);
            }

            return back()->with('error', $msg)->withInput($request->all());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInputs($request->all());
        }

        return view('campaigns.facebook.adset.index', ['adsets' => $adsets, 'index' => $index]);
    }

    public function adsetEdit(Request $request) {
        $adset = new Adset();
        $adset = $adset->get($request->adset_id);
        $adset = $adset->getSelf(['id', 'name', 'bid_amount', 'daily_budget', 'targeting', 'campaign_id']);

        $client = Client::find(explode('_', $adset->name)[3]);

        $page = new Page();
        $forms = $page->getForms();

        return view('campaigns.facebook.adset.edit', ['adset' => $adset, 'client' => $client]);
    }

    public function adsetCopy(Request $request) {
        try{
            $adset = new Adset();
            $adset = $adset->copy($request);
        } catch (\FacebookAds\Http\Exception\AuthorizationException $e){
            $error = $e->getResponse()->getContent();
            $msg = isset($error['error']['error_user_title']) ? $error['error']['error_user_title'] : '';
            $msg .= isset($error['error']['error_user_msg']) ? $error['error']['error_user_msg'] : '';
            $msg .= isset($error['error']['message']) ? $error['error']['message'] : '';
                
            if($request->ajax()) {
                return response($msg, 500);
            }

            return back()->with('error', $msg)->withInput($request->all());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInputs($request->all());
        }

        if($request->ajax()) {
            return response('Adset '.$request->name.' clonada com sucesso! ', 200);
        }

        return redirect()->route('campaigns.facebook.index')->with('success', 'Adset '.$request->name.' clonada com sucesso! ');
    }

    public function adsetDelete(Request $request) {
        try {
            $adset = new Adset();
            $adset = $adset->delete($request->adset_id);
        } catch (\FacebookAds\Http\Exception\AuthorizationException $e){
            $error = $e->getResponse()->getContent();
            $msg = isset($error['error']['error_user_title']) ? $error['error']['error_user_title'] : '';
            $msg .= isset($error['error']['error_user_msg']) ? $error['error']['error_user_msg'] : '';
            $msg .= isset($error['error']['message']) ? $error['error']['message'] : '';
                
            if($request->ajax()) {
                return response($msg, 500);
            }

            return back()->with('error', $msg)->withInput($request->all());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Adset '.$request->adset_id.' deletado!');
    }

    public function updateAdset(Request $request) {
        $adset = new Adset();
        try{
            if($request->copy){
                $adset = $adset->create($request->toArray(), $request->campaign_id);
            }else{
                $adset = $adset->update($request->toArray());
            }

        } catch (\FacebookAds\Http\Exception\AuthorizationException $e){
            $error = $e->getResponse()->getContent();
            $msg = isset($error['error']['error_user_title']) ? $error['error']['error_user_title'] : '';
            $msg .= isset($error['error']['error_user_msg']) ? $error['error']['error_user_msg'] : '';
            $msg .= isset($error['error']['message']) ? $error['error']['message'] : '';
                
            if($request->ajax()) {
                return response($msg, 500);
            }

            return back()->with('error', $msg)->withInput($request->all());
        } catch (\Exception $e){
                
            if($request->ajax()) {
                return response($e->getMessage(), 500);
            }

            return back()->with('error', $e->getMessage())->withInput($request->all());
        }

        if($request->ajax()) {
            return response('Adset atualizado', 200);
        }

        return redirect()->route('campaigns.facebook.adset', $request->campaign_id)->with('success', 'Adset '.$adset->name.($request->copy?'clonado':'atualizado').' com sucesso!');
    }

    public function ads(Request $request) {
        $adset = new Adset();

        $fields = ['id', 'name'];
        $ads = $adset->getAds($request->adset_id, $fields)['data'];

        return view('campaigns.facebook.ad.index', ['ads' => $ads]);
    }

    public function adEdit(Request $request) {
        $ad = new Ad();
        $page = new Page();

        $fields = ['id', 'name', 'adset{id,name,campaign_id}', 'creative{id,name,body,call_to_action_type,object_story_spec}'];
        $ad = $ad->get($request->ad_id, $fields);
        $preview = $ad->getPreviews([], ['ad_format' => 'DESKTOP_FEED_STANDARD'])->getResponse()->getContent()['data'][0]['body'];

        $client = Client::find(explode('_', $ad->adset['name'])[3]);
        $forms = $page->getForms();

        return view('campaigns.facebook.ad.form', ['client' => $client, 'ad' => $ad, 'data' => ['forms' => $forms], 'preview' => $preview]);
    }

    public function adCreate(Request $request) {
        $client = Client::find(explode('_', $request->adset_name)[3]);
        $adset = new Adset();
        $page = new Page();

        $forms = $page->getForms();
        $adset = $adset->get($request->adset_id, ['name', 'campaign_id']);

        Date::setLocale('pt_BR');
 
        $data = [
            'ad_name'       => '_IMG1 - '.Date::now()->format('Y-m-d'),
            'forms'         => $forms
        ];

        return view('campaigns.facebook.ad.form', ['data' => $data, 'client' => $client, 'adset' => $adset]);
    }

    public function adStore(Request $request) {
        try{
            if($request->images){
                $img_path = AdImageGenerate::generate($request->images, 'multiple');
            }elseif($request->file){
                $img_path = AdImageGenerate::generate($request->file, 'single');
            }
            
            $creative = new Creative();
            $creativeImg = $creative->createImg($img_path);
            $creativeImg = array_shift($creativeImg);
            $creativeAd = $creative->create($request->toArray(), $creativeImg);

            $ad = new Ad();
            $ad = $ad->create($request->ad_name, $request->adset_id, $creativeAd);
        } catch (\FacebookAds\Http\Exception\AuthorizationException $e) {
            $error = $e->getResponse()->getContent();
            $msg = isset($error['error']['error_user_title']) ? $error['error']['error_user_title'] : '';
            $msg .= isset($error['error']['error_user_msg']) ? $error['error']['error_user_msg'] : '';
            $msg .= isset($error['error']['message']) ? $error['error']['message'] : '';

            return back()->with('error', $msg)->withInput($request->all());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInputs($request->all());
        }

        return redirect()->route('campaigns.facebook.adset', $request->campaign_id)->with('success', 'AD criado com sucesso!');
    }

    public function adUpdate(Request $request) {
        try{
            $img_path = null;
            if($request->images){
                $img_path = AdImageGenerate::generate($request->images, 'multiple');
            }elseif($request->file){
                $img_path = AdImageGenerate::generate($request->file, 'single');
            }
    
            $creative = new creative();
            $creative = $creative->update($request->toArray(), $img_path);
    
            $ad = new Ad();
            $ad = $ad->update($request->ad_id, $creative, $request->toArray());
        } catch (\FacebookAds\Http\Exception\AuthorizationException $e) {
            $error = $e->getResponse()->getContent();
            $msg = isset($error['error']['error_user_title']) ? $error['error']['error_user_title'] : '';
            $msg .= isset($error['error']['error_user_msg']) ? $error['error']['error_user_msg'] : '';
            $msg .= isset($error['error']['message']) ? $error['error']['message'] : '';
                
            if($request->ajax()) {
                return response($msg, 500);
            }

            return back()->with('error', $msg)->withInput($request->all());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInputs($request->all());
        }

        return back()->with('success', 'AD atualizado com sucesso!');
    }

    public function adDelete(Request $request) {
        try {
            $ad = new Ad();
            $ad = $ad->delete($request->ad_id);
        } catch (\FacebookAds\Http\Exception\AuthorizationException $e) {
            $error = $e->getResponse()->getContent();
            $msg = isset($error['error']['error_user_title']) ? $error['error']['error_user_title'] : '';
            $msg .= isset($error['error']['error_user_msg']) ? $error['error']['error_user_msg'] : '';
            $msg .= isset($error['error']['message']) ? $error['error']['message'] : '';
                
            if($request->ajax()) {
                return response($msg, 500);
            }

            return back()->with('error', $msg)->withInput($request->all());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInputs($request->all());
        }

        return back()->with('success', 'AD '.$request->ad_id.' deletado!');
    }

    public function getInterests(Request $request) {
        $account = new Account();

        return $account->getInterests($request);
    }

    public function getReachEstimate(Request $request) {
        $adset = new Adset();
        $data = $adset->parseUpdateData($request->all());

        if(!empty($data) && isset($data['targeting'])) {
            $data['targeting_spec'] = $data['targeting'];
            unset($data['targeting']);
        }
        $account = new Account($request->custom_token);

        try {
            $response = $account->getReachEstimate($data);
        } catch (\FacebookAds\Http\Exception\AuthorizationException $e) {
            $error = $e->getResponse()->getContent();
            $msg = isset($error['error']['error_user_title']) ? $error['error']['error_user_title'] : '';
            $msg .= isset($error['error']['error_user_msg']) ? $error['error']['error_user_msg'] : '';
            $msg .= isset($error['error']['message']) ? $error['error']['message'] : '';
                
            if($request->ajax()) {
                return response($msg, 500);
            }

            return back()->with('error', $msg)->withInput($request->all());
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInputs($request->all());
        }

        return $response;
    }
}