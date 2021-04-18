<?php

namespace Feedback\Controllers;

use App\Client\Client;
use App\Campaign;
use Feedback\Lead\Access;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use App\Report;

class ClientController extends Controller
{
    /**
     *
     */
    public function index(Request $request) {
        $clients = Client::whereIn('user_type', ['Incorporadora', 'Imobiliaria'])
                         ->whereHas('campaigns')
                         ->orderBy('user_name', 'asc')
                         ->paginate(15, ['user_id', 'user_name']);

        return view('feedback::clients.index', ['clients' => $clients]);
    }

    public function campaigns(Client $client) {
        $campaigns = Campaign::with([
                               'leads' => function($query){ $query->disqualified(false)->count(); }
                            ])
                             ->where('user_id', $client->user_id)
                             ->orderBy('start_date', 'desc')
                             ->paginate(15);
        
        foreach($campaigns as $key => $campaign) {
            $report = Report::where('name', 'like', '%'.$campaign->id.'%')->orderBy('created_at', 'desc')->first();
            if($report) $campaigns[$key]->report = $report;
        }

        return view('feedback::clients.campaigns', ['client' => $client, 'campaigns' => $campaigns]);
    }
}
