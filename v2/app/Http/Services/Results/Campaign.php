<?php

namespace App\Http\Services\Results;

use DB;
use Carbon\Carbon;
use App\Lead\Lead;
use App\Http\Services\Contracts\Result as ResultInterface;
use App\Http\Services\Facebook\Controllers\Account;
use Illuminate\Support\Facades\Cache;

class Campaign extends Result implements ResultInterface
{
    /**
     * Gather data to build the Campaigns report
     */
    public function get()
    {

        $request = $this->request;
        $from_date = Carbon::createFromFormat('d/m/Y', $request->get('from_date'));
        $to_date = Carbon::createFromFormat('d/m/Y', $request->get('to_date'));
        $search_string = $request->filled('search_string') ? $request->get('search_string') : '';

        /*
        |--------------------------------------------------------------------------
        | Chart
        |--------------------------------------------------------------------------
        */

        $leads = Lead::select(DB::raw(
                        'SUM(CASE WHEN ga_ecommerce = 0 THEN 1 ELSE 0 END) AS leads,
                         SUM(CASE WHEN ga_ecommerce = 1 THEN 1 ELSE 0 END) AS unique_leads,
                         DATE_FORMAT(datetime, "%d/%m/%Y") AS created_at'
                     ))->has('campaign')
                       ->disqualified(false)
                       ->whereDateTimeBetween('datetime', [$from_date, $to_date])
                       ->when(!empty($search_string), function($query) use($search_string){
                           $query->whereHas('campaign', function($query) use($search_string){
                               $query->where('name', 'like', '%' . $search_string . '%')
                                     ->orWhere('id', $search_string);
                           });
                       })->groupBy('created_at')->get();

        $labels = $leads->pluck('created_at')->sortBy(function($value){
            return Carbon::createFromFormat('d/m/Y', $value)->timestamp;
        })->values();

        $datasets = [
            [
                'label' => 'Leads',
                'data' => $leads->pluck('leads')->toArray(),
                'backgroundColor' => 'rgba(251, 169, 25, 0.7)',
                'borderColor' => '#FBA919',
                'borderWidth' => '1',
            ],
            [
                'label' => 'Leads Únicos',
                'data' => $leads->pluck('unique_leads')->toArray(),
                'backgroundColor' => 'rgba(38, 185, 154, 0.7)',
                'borderColor' => '#26B99A',
                'borderWidth' => '1'
            ]
        ];

        /*
        |--------------------------------------------------------------------------
        | Campaigns
        |--------------------------------------------------------------------------
        */

        $campaigns = \App\Campaign::withCount([
            'listings',
            'pre' => function($query){
                $query->disqualified(false);
            },
            'pre as pre_leads_per_period' => function($query) use($from_date, $to_date){
                $query->disqualified(false)
                      ->whereDateTimeBetween('datetime', [$from_date, $to_date]);
            },
            'pre as pre_disqualified_leads' => function($query){
                $query->disqualified(true);
            },
            'pre as pre_leads_unique_count' => function($query){
                 $query->disqualified(false)
                       ->unique();
             },
            'pre as pre_leads_from_yesterday' => function($query) {
                $query->disqualified(false)
                      ->whereDate('datetime', Carbon::yesterday());
            },
            'pre as pre_leads_from_today' => function($query) {
                $query->disqualified(false)
                      ->whereDate('datetime', Carbon::today());
            },
            'leads' => function($query){
                $query->disqualified(false);
            },
            'leads as leads_per_period' => function($query) use($from_date, $to_date){
                $query->disqualified(false)
                      ->whereDateTimeBetween('datetime', [$from_date, $to_date]);
            },
            'leads as leads_unique_period' => function($query) use($from_date, $to_date){
                $query->disqualified(false)
                      ->whereDateTimeBetween('datetime', [$from_date, $to_date])
                      ->unique();
            },
            'leads as disqualified_leads' => function($query){
                $query->disqualified(true);
            },
            'leads as leads_unique_count' => function($query){
                 $query->disqualified(false)
                       ->unique();
             },
            'leads as leads_from_yesterday' => function($query) {
                $query->disqualified(false)
                      ->whereDate('datetime', Carbon::yesterday());
            },
            'leads as leads_from_today' => function($query) {
                $query->disqualified(false)
                      ->whereDate('datetime', Carbon::today());
            },
            'leads as hotleads' => function($query) {
                $query->whereHas('qa', function($query) {
                    $query->whereNotNull('hotlead');
                });
            },
            'leads as prequal' => function($query) {
                $query->whereNotNull('prequal_lead_id');
            }
        ])->where(function($query) use($from_date, $to_date){
            if($query->has('pre')){
                $query->whereHas('pre', function($query) use($from_date, $to_date){
                    // Campaigns that generated leads between the date range
                    $query->disqualified(false)
                          ->whereDateTimeBetween('datetime', [$from_date, $to_date]);
                })->orWhere(function($query) use($from_date, $to_date){
                    // Campaigns that were/are active between the date range
                    $query->whereBetween('start_date', [$from_date, $to_date])
                          ->orWhereBetween('end_date', [$from_date, $to_date])
                          ->orWhereRaw(DB::raw("'" . $from_date->format('Y-m-d') . "' BETWEEN start_date AND end_date"))
                          ->orWhereRaw(DB::raw("'" . $to_date->format('Y-m-d') . "' BETWEEN start_date AND end_date"));
                });
            }else{
                $query->whereHas('leads', function($query) use($from_date, $to_date){
                    // Campaigns that generated leads between the date range
                    $query->disqualified(false)
                          ->whereDateTimeBetween('datetime', [$from_date, $to_date]);
                })->orWhere(function($query) use($from_date, $to_date){
                    // Campaigns that were/are active between the date range
                    $query->whereBetween('start_date', [$from_date, $to_date])
                          ->orWhereBetween('end_date', [$from_date, $to_date])
                          ->orWhereRaw(DB::raw("'" . $from_date->format('Y-m-d') . "' BETWEEN start_date AND end_date"))
                          ->orWhereRaw(DB::raw("'" . $to_date->format('Y-m-d') . "' BETWEEN start_date AND end_date"));
                });
            }
        })->when(!empty($search_string), function($query) use($search_string){
            $query->where('name', 'like', '%' . $search_string . '%');
        })->orderBy('created_at', 'ASC')
          ->get();

        /*
        |--------------------------------------------------------------------------
        | Spends values
        |--------------------------------------------------------------------------
        */
        
        $fb_ids = (array_filter($campaigns->pluck('facebook_id')->all(), function($value) { return !is_null($value) && $value !== ''; }));

        $requestFB = [
            'fields' => ['campaign_id', 'spend', 'cost_per_action_type'],
            'params' => [
                'filtering'     => ['field' => 'campaign.id', 'operator' => 'IN', 'value' => $fb_ids],
                'time_range'   => "{'since': '".$from_date->format('Y-m-d')."', 'until': '".$to_date->format('Y-m-d')."'}",
                'level'         => 'campaign',
                'limit'         => 1000
                ]
        ];

        if(!Cache::get('request') || (Cache::get('request') && Cache::get('request') != $request)) {
            $fb = new Account();
            $insights = json_decode($fb->insights($requestFB), true);

            Cache::put('request', $request, now()->addMinutes(5));
            Cache::forget('insights');
            Cache::forever('insights', $insights);
        }else{
            $insights = Cache::get('insights');
        }
        
        foreach($campaigns as $key => $campaign) {
            $spend = $cpl = $cpl_valid = 0;

            foreach($insights['data'] as $insight) {
                if($insight['campaign_id'] == $campaign->facebook_id) {
                    $spend = $insight['spend'] + $spend;
                    $cpl = isset($insight['cost_per_action_type']) ? getLeadgenSpend($insight, ['currency' => false]) : 0;
                }
            }

            if($campaign->leads_unique_period > $campaign->budget) {
                $campaigns[$key]->revenue_period = $campaign->lead_price * $campaign->budget;
            } else{
                $campaigns[$key]->revenue_period = $campaign->lead_price * $campaign->leads_unique_period;
            }
            $campaigns[$key]->cpl = $cpl;
            $campaigns[$key]->cpl_valid = $spend !== 0 && $campaign->leads_per_period !== 0 ? $spend/$campaign->leads_per_period : 0;
            $campaigns[$key]->spend = $spend;
            $campaigns[$key]->gross_margin = $campaign->revenue_period > 0 ? substr((($campaign->revenue_period - $spend) / $campaign->revenue_period)*100, 0, 2) : '0';
            $campaigns[$key]->cpl_meta = $campaign->lead_price*0.3;
        }

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = $campaigns->isNotEmpty() ? [
            'listings' => $campaigns->sum('listings_count'),
            'budget' => $campaigns->sum('budget'),
            'leads' => $campaigns->sum('leads_count'),
            'disqualified_leads' => $campaigns->sum('disqualified_leads'),
            'hotleads' => $campaigns->sum('hotleads'),
            'leads_per_period' => $campaigns->sum('leads_per_period'),
            'leads_unique' => $campaigns->sum('leads_unique_count'),
            'leads_from_yesterday' => $campaigns->sum('leads_from_yesterday'),
            'leads_from_today' => $campaigns->sum('leads_from_today'),
            'revenue' => $campaigns->sum('revenue_period'),
            'spend' => $campaigns->sum('spend'),
            'cpl' => $campaigns->sum('spend') !== 0 ? $campaigns->sum('spend') / $campaigns->sum('leads_unique_count') : 0,
            'gross_margin' => $campaigns->sum('cpl_valid') !== 0 ? ceil((($campaigns->sum('revenue_period') - $campaigns->sum('spend')) / $campaigns->sum('revenue_period'))*100) : 0,
        ] : [];

        /*
        |--------------------------------------------------------------------------
        | Return the data
        |--------------------------------------------------------------------------
        */

        return [
            'campaigns' => $campaigns,
            'summary' => $summary,
            'chart' => [
                'labels' => $labels,
                'datasets' => $datasets
            ]
        ];
    }
}
