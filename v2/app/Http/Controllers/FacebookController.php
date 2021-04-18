<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lead\Pre;

class FacebookController extends Controller
{
    
    /**
    * Handle Webhook Payload.
    *
    * @return \Illuminate\Http\Response
    */
    public function webhook(Request $request)
    {
        if(!$request->filled('entry')){
            abort(400);
        }
        
        $entries = json_decode($request->getContent(), true)['entry'];
        
        foreach($entries as $entry){
            foreach($entry['changes'] as $change){
                $baseName = ucwords(camel_case($change['field']));
                $reflection = new \ReflectionClass("App\Http\Services\FacebookWebhook\\$baseName");
                $webhook = $reflection->newInstance();
                $webhook->handle($change['value']);
            }
        }
        
        return response('OK', 200);
    }
    
    public function adPreview(Request $request){
        $reflection = new \ReflectionClass($request->model);
        $model = $reflection->newInstance();
        $model = $model::findOrFail($request->id);
        
        try {
            $ad = $model->getAdPreview();
            if($ad) return $model->getAdPreview()->body;
        } catch (\Exception $e) {
            return response($e, 500);
        }
        
    }
}
