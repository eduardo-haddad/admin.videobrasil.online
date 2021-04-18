<?php

namespace App\Traits;

use Cache;
use PiplApi_SearchAPIRequest;
use PiplApi_SearchRequestConfiguration;

trait PiplTrait
{
    /**
     *
     */
    public function getPiplAttribute()
    {
        if(Cache::has($this->fromemail)){
            return Cache::get($this->fromemail);
        }

        return false;

        /*if(!isset($this->attributes['pipl'])){
            $cache = app()->environment(['local']) ? cache() : Cache::tags(['leads']);
            //$cache->flush(); // This is here only for testing purpose.

            $this->attributes['pipl'] = $cache->rememberForever($this->fromemail, function(){
                $config = new PiplApi_SearchRequestConfiguration();
                $config->api_key = env('PIPL_SOCIAL_KEY');
                $config->hide_sponsored = true;

                $request = new PiplApi_SearchAPIRequest([
                    'email' => $this->fromemail,
                    'raw_name' => $this->fromname
                ], $config);

                try{
                  $response = $request->send();
                } catch(\Exception $e){
                  report($e);
                  return;
                }

                if($response->person){
                    $person = $response->person->to_array();
                    $pipl = new \stdClass;
                    $pipl->name = $person['names'][0]['display'];

                    if(isset($person['images'])){
                        $pipl->picture = array_pop($person['images'])['url'];
                    }

                    if(isset($person['addresses']) && isset($person['addresses'][0]['city']) && isset($person['addresses'][0]['state'])){
                        $pipl->location = $person['addresses'][0]['city'] . ', ' . $person['addresses'][0]['state'];
                    }

                    if(isset($person['dob'])){
                        $pipl->age = str_replace('years old', 'anos', $person['dob']['display']);
                    }

                    if(isset($person['jobs'])){
                        $pipl->job = $person['jobs'][0]['display'];
                    }

                    if(isset($person['educations'])){
                        $pipl->education = $person['educations'][0]['display'];
                    }

                    if(isset($person['urls'])){
                        $pipl->urls = collect($person['urls'])->pluck('url', '@domain')->all();
                    }

                    return $pipl;
                }

                return false;
            });
        }

        return $this->attributes['pipl'];*/
    }
}
