<?php

namespace App\Activity;

use DB;
use Spatie\Activitylog\Models\Activity as ActivityBase;

class Lead extends ActivityBase
{
    /**
     * Scope a query to load subject relationships
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithRelationships($query)
    {
        return $query->with([
            'subject:id,first_talk_at',
            'subject.lead:lead_id,datetime,fromname,fromemail,fromphone1,listing_id,listing_coming_source',
            'subject.lead.listing:listing_id,listing_user_id',
            'subject.lead.listing.client:user_id,user_name',
            'subject.lead.listing.newconst:listing_id,listing_title_pt'
        ]);
    }

    /**
     * Scope a query to get the last inserted App\Lead\Qa\Attempt for each App\Lead\Qa
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \Illuminate\Support\Collection $qas
     * @param bool $callbacks
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLastAttempts($query, $qas, $callbacks = false)
    {
        $subject_type = [addslashes('App\Lead\Qa\Attempt')];

        if($callbacks){
            array_push($subject_type, addslashes('App\Lead\Qa\Callback'));
        }

        $raw = '(SELECT
                    MAX(subject_id) AS attempt_id
                FROM
                    `activity_log`
                WHERE
                    subject_type IN ("' . implode('","', $subject_type) . '")
                    AND description = "created"
                    AND JSON_UNQUOTE(properties->"$.attributes.lead_qa_id") IN ("' . implode('","', $qas->toArray()) . '")
                GROUP BY JSON_UNQUOTE(properties->"$.attributes.lead_qa_id")
                ) AS a1';

        return $query->select('att.id', 'att.lead_qa_id', 'att.channel')
                     ->from(DB::raw($raw))
                     ->join('lead_qa_contact_attempts AS att', 'att.id', '=', 'a1.attempt_id');
    }

    /**
     * Scope a query to get first_talk_at entry updates.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFirstTalks($query)
    {
        return $query->distinct()
                     ->whereNotNull(DB::raw('JSON_UNQUOTE(properties->"$.attributes.first_talk_at")'))
                     ->where(DB::raw('JSON_UNQUOTE(properties->"$.attributes.first_talk_at")'), '<>', 'null');
    }

    /**
     * Scope a query to get delete activies.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelectDelitions($query)
    {
        return $query->select(DB::raw(
                              'COUNT(*) AS `total`,
                               SUM(CASE WHEN JSON_UNQUOTE(properties->"$.attributes.channel") = "t" THEN 1 ELSE 0 END) AS attempts_t,
                               SUM(CASE WHEN JSON_UNQUOTE(properties->"$.attributes.channel") = "w" THEN 1 ELSE 0 END) AS attempts_w'
                             ))
                             ->where('a1.description', 'deleted')
                             ->where('a1.subject_type', 'App\Lead\Qa\Attempt')
                             ->groupBy('a1.subject_type');
    }

    /**
     * Scope a query to filter lead's acitivy by group owner
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $groups
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereGroupIn($query, $groups)
    {
        return $query->join('lead_qas AS qa', 'subject_id', '=', 'qa.id')
                     ->join('db_res_utf8.res_lead_management AS l', 'qa.lead_id', '=', 'l.lead_id')
                     ->join('db_res_utf8.res_users AS u', 'l.listing_user_id', '=', 'u.user_id')
                     ->whereIn('u.group_id', $groups);
    }

    /**
     * Scope a query to filter lead's acitivy by client owner
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $clients
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWhereClientIn($query, $clients)
    {
        return $query->join('lead_qas AS qa', 'subject_id', '=', 'qa.id')
                     ->join('db_res_utf8.res_lead_management AS l', 'qa.lead_id', '=', 'l.lead_id')
                     ->whereIn('l.listing_user_id', $clients);
    }
}
