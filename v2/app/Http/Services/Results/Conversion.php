<?php

namespace App\Http\Services\Results;

use App\Http\Services\Facebook\Controllers\Account;
use App\Lead\Pre;
use App\Lead\Lead;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;
use Cache;

class Conversion
{

    public function __construct() {
        $this->fb = new Account();
    }

    public function get($filters = null){
        $fb = new Account();
        $dateRange = [
            'from_date' => $filters && $filters['from_date'] ? Carbon::createFromFormat('d/m/Y H:i:s', $filters['from_date'].' 00:00:00') : Carbon::now()->subMonths(1)->startOfMonth(),
            'to_date'   => $filters && $filters['to_date'] ? Carbon::createFromFormat('d/m/Y H:i:s', $filters['to_date'].' 23:59:59') : Carbon::now()->endOfMonth(),
        ];
        
        $leads = Pre::with(['purchase'])
                    ->where('listing_user_id', '487046')
                    ->where('datetime', '>=', $dateRange['from_date'])
                    ->where('datetime', '<=', $dateRange['to_date'])
                    ->whereHas('listing', function ($query) use ($filters)  {
                        $query->when($filters && $filters['listing'], function ($query) use ($filters) {
                            return $query->where('listing_id', $filters['listing']);
                        });

                        return $query->whereNotNull('listing_id');
                    })
                    ->orderBy('datetime','ASC')->get(['lead_id', 'datetime']);

        $data = ['no_data' => 0];

        // Group leads per day
        $period = CarbonPeriod::create($dateRange['from_date'], $dateRange['to_date']);

        foreach ($period as $date) {
            $data['day'][$date->format('Y-m-d')] = [];
            foreach($leads as $key => $lead){
                if($lead->datetime->format('Y-m-d') == $date->format('Y-m-d')){
                    $data['day'][$date->format('Y-m-d')][] = $lead;
                }
            }
        }

        // Slit leads per week
        $week = 0;
        $i = 0;
        foreach($data['day'] as $key => $day){
            if($i !== 0 && Carbon::parse($key)->dayOfWeek == 1){
                $week = $week+1;
                $data['week'][$week]['date_from'] = $key;
            }elseif($i == 0){
                $data['week'][$week]['date_from'] = $key;
            }

            $data['week'][$week]['date_to'] = $key;

            foreach($day as $lead){
                $data['week'][$week]['leads'][] = $lead;
            }

            $i++;
        }

        foreach($data['week'] as $key => $weekNumber){
            if(!isset($weekNumber['leads'])) {
                $data['no_data']++;
                continue;
            }

            $weekNumber['date_from'] = Carbon::parse($weekNumber['date_from'].' 00:00:00');
            $weekNumber['date_to'] = Carbon::parse($weekNumber['date_to'].' 23:59:59');

            $data['week'][$key]['leads_summary']['contacted'] = Pre::where('listing_user_id', '487046')
                                                    ->where('datetime', '>=', $weekNumber['date_from'])
                                                    ->where('datetime', '<=', $weekNumber['date_to'])
                                                    ->whereHas('qa', function ($query) use ($weekNumber) {
                                                        return $query->whereNotNull('first_contact_at');
                                                    })
                                                    ->whereHas('listing', function ($query) use ($filters)  {
                                                        $query->when($filters && $filters['listing'], function ($query) use ($filters) {
                                                            return $query->where('listing_id', $filters['listing']);
                                                        });
                                
                                                        return $query->whereNotNull('listing_id');
                                                    })->count();

            $data['week'][$key]['leads_summary']['chat'] = Pre::where('listing_user_id', '487046')
                                                    ->where('datetime', '>=', $weekNumber['date_from'])
                                                    ->where('datetime', '<=', $weekNumber['date_to'])
                                                    ->whereHas('qa', function ($query) use ($weekNumber) {
                                                        return $query->whereNotNull('first_talk_at');
                                                    })
                                                    ->whereHas('listing', function ($query) use ($filters)  {
                                                        $query->when($filters && $filters['listing'], function ($query) use ($filters) {
                                                            return $query->where('listing_id', $filters['listing']);
                                                        });
                                
                                                        return $query->whereNotNull('listing_id');
                                                    })->count();
                                                    
            $booked_visit = Pre::where('listing_user_id', '487046')
                                ->where('datetime', '>=', $weekNumber['date_from'])
                                ->where('datetime', '<=', $weekNumber['date_to'])
                                ->whereNotNull('sent_at')
                                ->whereHas('listing', function ($query) use ($filters)  {
                                    $query->when($filters && $filters['listing'], function ($query) use ($filters) {
                                        return $query->where('listing_id', $filters['listing']);
                                    });
            
                                    return $query->whereNotNull('listing_id');
                                })->count();
            
            $data['week'][$key]['leads_summary']['booked_visit'] = $booked_visit;
            
            $data['week'][$key]['leads_summary']['visited'] = Pre::where('listing_user_id', '487046')
                                                    ->where('datetime', '>=', $weekNumber['date_from'])
                                                    ->where('datetime', '<=', $weekNumber['date_to'])
                                                    ->whereHas('qa', function ($query) use ($weekNumber) {
                                                        return $query->where('visited', 's');
                                                    })
                                                    ->whereHas('listing', function ($query) use ($filters)  {
                                                        $query->when($filters && $filters['listing'], function ($query) use ($filters) {
                                                            return $query->where('listing_id', $filters['listing']);
                                                        });
                                
                                                        return $query->whereNotNull('listing_id');
                                                    })->count();
            
            $data['week'][$key]['leads_summary']['purchased'] = Pre::where('listing_user_id', '487046')
                                                           ->where('datetime', '>=', $weekNumber['date_from'])
                                                           ->where('datetime', '<=', $weekNumber['date_to'])
                                                           ->whereHas('purchase', function ($query) {
                                                                return $query->whereNotNull('made_at');
                                                           })
                                                           ->whereHas('listing', function ($query) use ($filters)  {
                                                            $query->when($filters && $filters['listing'], function ($query) use ($filters) {
                                                                return $query->where('listing_id', $filters['listing']);
                                                            });
                                    
                                                            return $query->whereNotNull('listing_id');
                                                            })->count();
            
            $average_price['leads'] = Pre::with('purchase')
                                         ->where('listing_user_id', '487046')
                                         ->where('datetime', '>=', $weekNumber['date_from'])
                                         ->where('datetime', '<=', $weekNumber['date_to'])
                                         ->whereHas('purchase', function ($query) {
                                            return $query->whereNotNull('product_price');
                                         })
                                         ->whereHas('listing', function ($query) use ($filters)  {
                                            $query->when($filters && $filters['listing'], function ($query) use ($filters) {
                                                return $query->where('listing_id', $filters['listing']);
                                            });
                                            return $query->whereNotNull('listing_id');
                                        })->get();

            $average_price['product'] = 0;
            $average_price['commission'] = 0;
            $average_price['commission_percentage'] = 0;

            foreach($average_price['leads'] as $lead) {
                $average_price['product'] = $average_price['product'] + $lead->purchase->product_price;
                $average_price['commission'] = $average_price['commission'] + $lead->purchase->commission;
            }

            $data['week'][$key]['leads_summary']['values'] = [
                'product'               => $average_price['product'],
                'commission'            => $average_price['commission'],
            ];

            $data['week'][$key]['leads_summary']['disqualified'] = Pre::where('listing_user_id', '487046')
                                                               ->where('datetime', '>=', $weekNumber['date_from'])
                                                               ->where('datetime', '<=', $weekNumber['date_to'])
                                                               ->where('disqualified', '1')
                                                               ->count();

            $request = [
                'fields' => ['spend'],
                'params' => [
                    'filtering'     => ['field' => 'campaign.name', 'operator' => 'CONTAIN', 'value' => 'CASATEC'],
                    'time_range'    => ['since' => $weekNumber['date_from']->format('Y-m-d'), 'until' => $weekNumber['date_to']->format('Y-m-d')],
                    'level'         => 'campaign'
                    ]
            ];

            if($filters && (isset($filters['listing']) || isset($filters['ads']) )){
                $insights = Self::ads($weekNumber, $filters);
            }else{
                $insights = Self::campaign($weekNumber);
            }

            $spend = 0;
            foreach($insights->data as $campaign) {
                $spend = ($spend+$campaign->spend);
            }
            $data['week'][$key]['spend'] = $spend;
            $data['week'][$key]['cpl'] = $spend/count($weekNumber['leads']);
        }

        return $data['week'];
    }

    function ads($data, $filters){
        $filtering_target = 'CASATEC';
        if($filters['listing']) {
            $filtering_target = $filters['listing'];
        }
        $request = [
            'fields' => ['spend', 'adset_name'],
            'params' => [
                'filtering'     => ['field' => 'adset.name', 'operator' => 'CONTAIN', 'value' => $filtering_target],
                'time_range'    => ['since' => $data['date_from']->format('Y-m-d'), 'until' => $data['date_to']->format('Y-m-d')],
                'level'         => 'ad',
                'limit'         => '1000'
                ]
            ];
        
        $insights = json_decode($this->fb->insights($request));
        
        return $insights;
    }

    function campaign($data){
        $request = [
            'fields' => ['spend'],
            'params' => [
                'filtering'     => ['field' => 'adset.name', 'operator' => 'CONTAIN', 'value' => 'CASATEC'],
                'time_range'    => ['since' => $data['date_from']->format('Y-m-d'), 'until' => $data['date_to']->format('Y-m-d')],
                'level'         => 'campaign',
                'limit'         => '1000'
                ]
        ];

        return json_decode($this->fb->insights($request));
    }

    function spend($insights, $type){
        $spend = 0;

        foreach($insights->data as $campaign) {
            $spend = ($spend+$campaign->spend);
        }

        return ['spend' => $spend, 'cpl' => $spend/count($weekNumber['leads'])];
    }
}
