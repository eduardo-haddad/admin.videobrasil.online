<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Services\RDStation\Client as RDClient;
use App\Lead\Pre;
use App\Lead\Lead;

trait RDStationTrait
{
    public function rdstation(Request $request) {
        if($request->pre) {
            $lead = Pre::where('lead_id', $request->lead_id)->with('listing')->first();
        }else{
            $lead = Lead::where('lead_id', $request->lead_id)->with('listing')->first();
        }

        if(!$lead) return response('Lead not found', 404);

        $listing = $lead->listing;

        $client = new RDClient();
        return $client->handle($listing, $lead);
    }
}