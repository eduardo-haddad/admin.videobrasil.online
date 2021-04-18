<?php

namespace App\Http\Services\Results\Leads;

use App\Lead\Lead;
use App\Lead\Pre;
use Carbon\Carbon;
use App\Lead\Qa\ContactAttempt;
use DB;

class NoTalked
{
    //
    public function __construct ($request) {
        $this->date['from'] = $request->start_date_from ? Carbon::createFromFormat('d/m/Y H:i:s', $request->start_date_from. ' 00:00:00') : Carbon::today()->subDays(5)->startOfDay();
        $this->date['to'] = $request->start_date_to ? Carbon::createFromFormat('d/m/Y H:i:s', $request->start_date_to.' 23:59:59') : Carbon::today()->endOfDay();
        $this->date['diff'] = $this->date['from']->diffInDays($this->date['to']);
        $this->minutes = $request->minutes ? Carbon::now()->subMinutes($request->minutes) : Carbon::now()->subMinutes(5);
        $this->minutes_raw = $request->minutes;
        $this->pre_type = $request->pre_type ? $request->pre_type : '2';
        $this->disqualified = isset($request->include['disqualified']) ? true : false;
        $this->contacted = isset($request->include['contacted']) ? true : false;
    }


    public function index () {
        // Leads from X minutes
        $leads['minutes']['attempts'][0] = DB::table("prequal_leads")
                                            ->select(DB::raw("count(*) as aggregate"))
                                            ->whereRaw("not exists 
                                                        (select * from lead_qas where prequal_leads.lead_id = lead_qas.lead_id 
                                                         and first_talk_at is null
                                                         ".(!$this->contacted ? "and first_contact_at is null" : '').")")
                                            ->whereRaw("datetime >= '".$this->minutes."'
                                                        and datetime <= '".Carbon::now()."'")
                                            ->when(!$this->disqualified, function ($query) {
                                                $query->whereRaw("disqualified != '1'");
                                            })
                                            ->count();

        // Leads from period
        for ($i=0; $i <= 9; $i++) {
            $leads['daily']['attempts'][$i] = DB::table("prequal_leads")
                                                ->select(DB::raw("count(*) as aggregate"))
                                                ->when($i !== 0, function ($query) use ($i) {
                                                    $query->whereRaw("exists (select * from lead_qas where prequal_leads.lead_id = lead_qas.lead_id
                                                    and lead_qas.first_talk_at is null
                                                    ".(!$this->contacted ? "and first_contact_at is null" : '')."
                                                    and (
                                                        not exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id`)
                                                        or exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id` and `name` != 'CRM')
                                                       )
                                                    and (select count(*) from lead_qa_contact_attempts 
                                                    where lead_qas.id = lead_qa_contact_attempts.lead_qa_id 
                                                    ) = ".$i.") ");
                                                })
                                                ->when($i == 0, function ($query) {
                                                    $query->whereRaw("(not exists (select * from lead_qas where prequal_leads.lead_id = lead_qas.lead_id)
                                                    OR exists (select * from lead_qas where prequal_leads.lead_id = lead_qas.lead_id
                                                    and lead_qas.first_talk_at is null
                                                    ".(!$this->contacted ? "and first_contact_at is null" : '')."
                                                    and (
                                                        not exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id`)
                                                        or exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id` and `name` != 'CRM')
                                                       )
                                                    and (select count(*) from lead_qa_contact_attempts where lead_qas.id = lead_qa_contact_attempts.lead_qa_id
                                                    ) = '0'))");
                                                })
                                                ->when($this->pre_type, function ($query) {
                                                    $query->whereRaw("exists 
                                                    (select * from db_res_utf8.res_users where agenteimovel.prequal_leads.listing_user_id = db_res_utf8.res_users.user_id 
                                                    and db_res_utf8.res_users.".($this->pre_type == '1' ? 'pre_qualification' : 'pre_sale')." = '1' )");
                                                })
                                                ->when(!$this->disqualified, function ($query) {
                                                    $query->whereRaw("disqualified != '1'");
                                                })
                                                ->whereRaw("datetime >= '".$this->date['from']."' and datetime <= '".$this->date['to']."'")
                                                ->count();
        }

        // Leads per day
        for ($day=0; $day <= $this->date['diff'] ; $day++) {
            for ($attempts=0; $attempts <= 9; $attempts++) {
                $current_date = Carbon::parse($this->date['to'])->subDays($day)->format('Y-m-d');
                $qa = DB::table("prequal_leads")
                        ->select(DB::raw("select count(*) as aggregate"))
                        ->whereRaw("exists (select * from lead_qas where agenteimovel.prequal_leads.lead_id = lead_qas.lead_id)")
                        ->whereRaw("datetime >= '".$current_date." 00:00:00'")
                        ->whereRaw("datetime <= '".$current_date." 23:59:59'")
                        ->count();

                if($qa == 0) {
                    $qa = DB::table("prequal_leads")
                            ->select(DB::raw("select count(*) as aggregate"))
                            ->whereRaw("(not exists (select * from lead_qas where prequal_leads.lead_id = lead_qas.lead_id)
                                            OR exists (select * from lead_qas where prequal_leads.lead_id = lead_qas.lead_id
                                            and lead_qas.first_talk_at is null
                                            ".(!$this->contacted ? "and first_contact_at is null" : '')."
                                            and (
                                                not exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id`)
                                                or exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id` and `name` != 'CRM')
                                               )
                                            and (select count(*) from lead_qa_contact_attempts where lead_qas.id = lead_qa_contact_attempts.lead_qa_id
                                            ) = '0'))")
                            ->whereRaw("exists (select * from db_res_utf8.res_users 
                                            where agenteimovel.prequal_leads.listing_user_id = db_res_utf8.res_users.user_id 
                                                and ".($this->pre_type == '1' ? 'pre_qualification' : 'pre_sale')." = '1')")
                            ->when(!$this->disqualified, function ($query) {
                                $query->whereRaw("disqualified != '1'");
                            })
                            ->whereRaw("datetime >= '".$current_date." 00:00:00'")
                            ->whereRaw("datetime <= '".$current_date." 23:59:59'")
                            ->count();
                    
                    if($qa !== 0) $leads[$current_date]['attempts'][0] = $qa;
                    continue;
                }


                $amount[0] = Self::query($day, $attempts)->count();
                $amount[1] = Self::query($day, $attempts, 1)->count();
                $amount[2] = Self::query($day, $attempts, 2)->count();
                $amount[3] = Self::query($day, $attempts, 3)->count();
                $amount[4] = Self::query($day, $attempts, 4)->count();

                if(($amount[0] == 0 && $amount[1] == 0 && $amount[2] == 0 && $amount[3] == 0 && $amount[4] == 0)) break;

                $leads[$current_date]['attempts'][$attempts] = $amount[0];
            }
        }

        $leads = Self::url($leads);

        return $leads;
    }

    public function get($request) {
        if (strpos($request->date, ',') !== false) {
            $request->date = explode(',', $request->date);
            $request->date[0] = Carbon::parse($request->date[0].' 00:00:00');
            $request->date[1] = Carbon::parse($request->date[1].' 00:00:00');
        }

        $leads = Pre::when(isset($request->date) && !is_array($request->date), function ($query) use ($request) {
                        $query->whereDate('datetime', $request->date);
                    })
                    ->when(isset($request->date) && is_array($request->date), function ($query) use ($request) {
                        $query->whereDate('datetime', '>=', $request->date[0])
                              ->whereDate('datetime', '<=', $request->date[1]);
                    })
                    ->when($request->minutes, function ($query) use ($request) {
                        $query->where('datetime', '>=', $this->minutes)
                              ->where('datetime', '<=', Carbon::now());
                    })
                    ->when(isset($request->attempts) && $request->attempts !== '0', function ($query) use ($request) {
                        $query->whereRaw("exists (select * from lead_qas where prequal_leads.lead_id = lead_qas.lead_id 
                        and lead_qas.first_talk_at is null
                        ".(!$this->contacted ? "and first_contact_at is null" : '')."
                        and (
                         not exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id`)
                         or exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id` and `name` != 'CRM')
                        )
                        and (select count(*) from lead_qa_contact_attempts 
                        where lead_qas.id = lead_qa_contact_attempts.lead_qa_id) = ".$request->attempts.") ");
                    })
                    ->when(isset($request->attempts) && $request->attempts == '0', function ($query) {
                        $query->whereRaw("(not exists (select * from lead_qas where prequal_leads.lead_id = lead_qas.lead_id)
                                           OR exists (select * from lead_qas where prequal_leads.lead_id = lead_qas.lead_id and lead_qas.first_talk_at is null
                                           and (
                                            not exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id`)
                                            or exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id` and `name` != 'CRM')
                                           )
                                           ".(!$this->contacted ? "and first_contact_at is null" : '')."
                                           and (select count(*) from lead_qa_contact_attempts where lead_qas.id = lead_qa_contact_attempts.lead_qa_id
                                           ) = '0'))");
                    })
                    ->when(!isset($request->attempts) && !isset($request->minutes) || $request->minutes == '', function ($query) {
                        $query->whereRaw("(exists (select * from lead_qas where agenteimovel.prequal_leads.lead_id = lead_qas.lead_id and lead_qas.first_talk_at is null
                                        and (
                                            not exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id`)
                                            or exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id` and `name` != 'CRM')
                                        )
                                        ".(!$this->contacted ? "and first_contact_at is null" : '').")
                                          OR not exists (select * from lead_qas where prequal_leads.lead_id = lead_qas.lead_id))");
                    })
                    ->when(isset($request->pre_type) && $this->pre_type, function ($query) {
                        $query->whereRaw("exists 
                        (select * from db_res_utf8.res_users where agenteimovel.prequal_leads.listing_user_id = db_res_utf8.res_users.user_id 
                        and db_res_utf8.res_users.".($this->pre_type == '1' ? 'pre_qualification' : 'pre_sale')." = '1')");
                    })
                    ->when(!$this->disqualified, function ($query) {
                        $query->whereRaw("disqualified != '1'");
                    })
                    ->orderBy('datetime', 'desc')
                    ->paginate(15);

        return $leads;
    }


    public function query ($day, $attempts, $next = null) {
        if($next) $attempts = $attempts+$next;

        $current_date = Carbon::parse($this->date['to'])->subDays($day)->format('Y-m-d');

        return DB::table("prequal_leads")
                    ->select(DB::raw("count(*) as aggregate"))
                    ->when($attempts !== 0, function ($query) use ($attempts) {
                        return $query->whereRaw("exists (select * from lead_qas where prequal_leads.lead_id = lead_qas.lead_id 
                        and (
                            not exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id`)
                            or exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id` and `name` != 'CRM')
                           )
                        and (select count(*) from lead_qa_contact_attempts 
                        where lead_qas.id = lead_qa_contact_attempts.lead_qa_id and first_talk_at is null
                        ".(!$this->contacted ? "and first_contact_at is null" : '').") = ".$attempts.") ");
                    })
                    ->when($attempts == 0, function ($query) {
                        return $query->whereRaw("(not exists (select * from lead_qas where prequal_leads.lead_id = lead_qas.lead_id)
                                                OR exists (select * from lead_qas where prequal_leads.lead_id = lead_qas.lead_id and first_talk_at is null
                                                and (
                                                    not exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id`)
                                                    or exists (select * from `users` where `lead_qas`.`user_id` = `users`.`id` and `name` != 'CRM')
                                                   )
                                                ".(!$this->contacted ? "and first_contact_at is null" : '')."
                                                and (select count(*) from lead_qa_contact_attempts where lead_qas.id = lead_qa_contact_attempts.lead_qa_id
                                                ) = '0'))");
                    })
                    ->when($this->pre_type, function ($query) {
                        $query->whereRaw("exists 
                        (select * from db_res_utf8.res_users where agenteimovel.prequal_leads.listing_user_id = db_res_utf8.res_users.user_id 
                        and db_res_utf8.res_users.".($this->pre_type == '1' ? 'pre_qualification' : 'pre_sale')." = '1' )");
                    })
                    ->when(!$this->disqualified, function ($query) {
                        $query->whereRaw("disqualified != '1'");
                    })
                    ->whereRaw("datetime >= '".$current_date." 00:00:00' and datetime <= '".$current_date." 23:59:59'");

    }

    public function url ($leads) {
        $url;
        foreach($leads as $key => $value) {
            $count = 0;

            if($key == 'minutes') {
                $url = 'minutes='.$this->minutes_raw;
            }else if($key == 'daily') {
                $url = 'date='.$this->date['from']->format('Y-m-d').','.$this->date['to']->format('Y-m-d');
            }else{
                $url = 'date='.$key;
            }

            $url .= '&pre_type='.$this->pre_type;
            $url .= $this->disqualified ? '&include[disqualified]=1' : '';
            $url .= $this->contacted ? '&include[contacted]=1' : '';

            $leads[$key]['url'] = route('leads.reports.not-talked.view', $url);

            foreach($value['attempts'] as $key2 => $attempt) {
                $count = ($count + $leads[$key]['attempts'][$key2]);
                $leads[$key]['total'] = $count;
                $leads[$key]['attempts'][$key2] = [
                    'value' => $leads[$key]['attempts'][$key2],
                    'url'   => route('leads.reports.not-talked.view', $url.'&attempts='.$key2)
                ];
            }   
        }

        return $leads;
    }
}