<?php

namespace Feedback\Controllers;

use App\Campaign;
use App\Listing\CampaignListing;
use App\Listing\Listing;
use App\Lead\Lead;
use Feedback\Lead\Access;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class CampaignController extends Controller
{
    /**
     *
     */
    public function index(Request $request)
    {
        $campaign = Campaign::where('id', $request->campaign)
        ->withCount([
            'leads',
            'leads as leads_valid' => function($query){ $query->disqualified(false); },
            'leads as leads_disqualified' => function($query){ $query->disqualified(true); },
            'leads as leads_contacted' => function($query){ $query->whereHas('qa', function ($query) {
                $query->whereNotNull('first_contact_at');
            });},
            'leads as leads_analyzed' => function($query){ $query->whereHas('qa', function ($query) {
                $query->whereNotNull('talked_to_broker')
                      ->whereNotNull('searching_immobile');
            });},
            'leads as leads_wants_visist' => function($query) { $query->whereHas('qa', function ($query) {
                $query->where('booked_visit', 'p');
            });},
            'leads as leads_booked' => function($query) { $query->whereHas('qa', function ($query) {
                $query->where('booked_visit', 's');
            });},
            'leads as leads_interest_no_contact' => function($query) { $query->whereHas('qa', function ($query) {
                $query->where('talked_to_broker', 'n')
                      ->where('searching_immobile', 's');
            });},
            'leads as leads_hotlead' => function($query) { $query->whereHas('qa', function ($query) {
                $query->whereNotNull('hotlead');
            });},
            'leads as leads_no_broker_contact' => function($query) { $query->whereHas('qa', function ($query) {
                $query->where('talked_to_broker', 'n');
            });},
            'leads as attempts_whatsapp' => function($query) { $query->whereHas('qa.attempts', function ($query) {
                $query->where('channel', 'w');
            });},
            'leads as attempts_phone' => function($query) { $query->whereHas('qa.attempts', function ($query) {
                $query->where('channel', 't');
            });},
        ])->first();

        $pivots = CampaignListing::withCount([
            'leads' => function($query) use ($request) { $query->whereHas('campaign', function ($query) use ($request) {
                $query->where('id', $request->campaign);
            });},
            'leads as leads_valid' => function($query) use ($request) {
                $query->whereHas('campaign', function ($query) use ($request) {
                    $query->where('id', $request->campaign);
                })
                ->disqualified(false);
            },
            'leads as leads_contacted' => function($query) use ($request) {
                $query->whereHas('qa', function ($query) {
                        $query->whereNotNull('first_contact_at');
                      });
            },
            'leads as leads_analyzed' => function($query) use ($request) {
                $query->whereHas('qa', function ($query) {
                        $query->whereNotNull('talked_to_broker')
                              ->whereNotNull('searching_immobile');
                      });
            },
            'leads as leads_wants_visist' => function($query) use ($request) {
                $query->whereHas('qa', function ($query) {
                        $query->where('booked_visit', 'p');
                      });
            },
            'leads as leads_booked' => function($query) use ($request) {
                $query->whereHas('qa', function ($query) {
                        $query->where('booked_visit', 's');
                      });
            },
            'leads as leads_interest_no_contact' => function($query) use ($request) {
                $query->whereHas('qa', function ($query) {
                        $query->where('talked_to_broker', 'n')
                              ->where('searching_immobile', 's');
                      });
            },
            'leads as leads_hotlead' => function($query) use ($request) {
                $query->whereHas('qa', function ($query) {
                        $query->whereNotNull('hotlead');
                      });
            },
            'leads as leads_no_broker_contact' => function($query) use ($request) {
                $query->whereHas('qa', function ($query) {
                        $query->where('talked_to_broker', 'n');
                      });
            },
            'leads as attempts_whatsapp' => function($query) use ($request) {
                $query->whereHas('qa.attempts', function ($query) {
                        $query->where('channel', 'w');
                      });
            },
            'leads as attempts_phone' => function($query) use ($request) {
                $query->whereHas('qa.attempts', function ($query) {
                        $query->where('channel', 't');
                      });
            }
        ])
        ->with(['newconst:listing_id,listing_title'])
        ->where('campaign_id', $campaign->id)
        ->paginate(30);

        $client = $campaign->client;

        //calc service rate average
        $campaign->service_rates = $campaign->leads()->whereHas('qa', function($query) {
            $query->whereNotNull('service_rate')
                  ->where('service_rate', '!=', 'sr');
        })->get()->pluck('qa.service_rate');

        if($campaign->service_rates->count()){
            $campaign->service_rate_avg = ceil($campaign->service_rates->sum() / $campaign->service_rates->count());
        }else{
            $campaign->service_rate_avg = '-';
        }

        foreach($pivots as $key => $pivot) {
            $pivots[$key]->service_rates = Lead::where('listing_id', $pivot->listing_id)
                                               ->where('campaign_id', $pivot->campaign_id)
                                               ->whereHas('qa', function($query) {
                                                   $query->whereNotNull('service_rate')
                                                         ->where('service_rate', '!=', 'sr');
                                                })->get()->pluck('qa.service_rate');
            
            if($pivots[$key]->service_rates->count() !== 0) {
                $pivots[$key]->service_rate_avg = ceil($pivots[$key]->service_rates->sum() / $pivots[$key]->service_rates->count());
            }else{
                $pivots[$key]->service_rate_avg = '-';
            }
        }

        $client_service_rate = (object)[
            'current_month' => (object)[
                'service_rate' => $client->leads()
                                         ->where('campaign_id', $request->campaign)
                                         ->where('datetime', '>=', Carbon::now()->firstOfMonth())
                                         ->where('datetime', '<=', Carbon::now()->lastOfMonth())
                                         ->whereHas('qa', function($query) {
                                             $query->whereNotNull('service_rate')
                                                   ->where('service_rate', '!=', 'sr');
                                         })->get()->pluck('qa.service_rate'),
                'avg' => '-'
            ],
            'prev_month' => (object)[
                'service_rate' => $client->leads()
                                         ->where('campaign_id', $request->campaign)
                                         ->where('datetime', '>=', new Carbon('first day of last month'))
                                         ->where('datetime', '<=', new Carbon('last day of last month'))
                                         ->whereHas('qa', function($query) {
                                             $query->whereNotNull('service_rate')
                                                    ->where('service_rate', '!=', 'sr');
                                         })->get()->pluck('qa.service_rate'),
                'avg' => '-'
            ]
        ];

        if($client_service_rate->current_month->service_rate->count() !== 0) {
            $client_service_rate->current_month->avg = ceil($client_service_rate->current_month->service_rate->sum() / $client_service_rate->current_month->service_rate->count());
        }
        if($client_service_rate->prev_month->service_rate->count() !== 0) {
            $client_service_rate->prev_month->avg = ceil($client_service_rate->prev_month->service_rate->sum() / $client_service_rate->prev_month->service_rate->count());
        }

        $client->service_rate = $client_service_rate;

        //percentage calc
        $percentages = [
            'leads_contacted'           => $campaign->leads_contacted           !== 0 ? ($campaign->leads_contacted / $campaign->leads_count * 100) : '-',
            'leads_analyzed'            => $campaign->leads_analyzed            !== 0 ? ($campaign->leads_analyzed / $campaign->leads_count * 100) : '-',
            'leads_wants_visist'        => $campaign->leads_wants_visist        !== 0 ? ($campaign->leads_wants_visist / $campaign->leads_count * 100) : '-',
            'leads_booked'              => $campaign->leads_booked              !== 0 ? ($campaign->leads_booked / $campaign->leads_count * 100) : '-',
            'leads_hotlead'             => $campaign->leads_hotlead             !== 0 ? ($campaign->leads_hotlead / $campaign->leads_count * 100) : '-',
            'leads_no_broker_contact'   => $campaign->leads_no_broker_contact   !== 0 ? ($campaign->leads_no_broker_contact / $campaign->leads_count * 100) : '-',
            'leads_interest_no_contact' => $campaign->leads_interest_no_contact !== 0 ? ($campaign->leads_interest_no_contact / $campaign->leads_count * 100) : '-',
        ];

        foreach($percentages as $key => $value) {
            $percentages[$key] = is_numeric($value) ? ceil($value).'%' : '-';
        }

        $campaign->percentages = (object)$percentages;

        return view('feedback::campaigns.index', ['client' => $client, 'campaign' => $campaign, 'pivots' => $pivots]);
    }
}
