<?php

namespace App\Http\Services\Results\Leads;

use DB;
use Carbon\Carbon;
use App\Activity\Lead as Activity;
use App\Http\Services\Results\Result;
use App\Http\Services\Contracts\Result as ResultInterface;
use Illuminate\Database\Eloquent\Collection;

class Qa extends Result implements ResultInterface
{
    /**
     * Gather data to build the "Leads Qualification" report
     */
    public function get()
    {
        $request = $this->request;
        $from_date = Carbon::createFromFormat('d/m/Y', $request->get('from_date'))->startOfDay();
        $to_date = Carbon::createFromFormat('d/m/Y', $request->get('to_date'))->endOfDay();

        /*
        |--------------------------------------------------------------------------
        | Prepare the base queries.
        |--------------------------------------------------------------------------
        */

        // Create first base query
        $base_query = Activity::from('activity_log AS a1')
                         ->whereBetween('a1.created_at', [$from_date, $to_date]);

        if(($request->filled('by_agent') && $by_agent = $request->get('by_agent', 'all')) && !in_array('all', $by_agent)){
            $base_query->whereIn('a1.causer_id', $by_agent)
                        ->where('a1.causer_type', 'App\User');
        }

        /*if($request->filled('by_client') && $by_client = $request->get('by_client')){
            //
        } elseif($request->filled('by_group') && $by_group = $request->get('by_group')){
            //
        }*/

        // Get everytime an App\Lead\Qa model was updated
        $query1 = (clone $base_query)->where('a1.subject_type', 'App\Lead\Qa')
                                     ->where('a1.description', 'updated')
                                     ->whereNull(DB::raw('JSON_UNQUOTE(properties->"$.attributes.user_id")'));

        // Create a variation of the $query1 to return subject relationships
        $query1_rel = (clone $query1)->withRelationships();

        // Create a variation of $base_query to work with Attempts and Callbacks
        $query2 = (clone $base_query)->where('a1.description', 'created');


        /*
        |--------------------------------------------------------------------------
        | Get delete activities.
        |--------------------------------------------------------------------------
        */

        $delitions = (clone $base_query)->selectDelitions()->first();

        /*
        |--------------------------------------------------------------------------
        | Unique Leads.
        |--------------------------------------------------------------------------
        */

        // Get the uniques App\Lead\Qa's from $query1
        $unique_leads1 = (clone $query1)->distinct('subject_id')
                                        ->get()->pluck('subject_id');

        // Get the uniques App\Lead\Qa's that were associated when App\Lead\Qa\Attempt's or App\Lead\Qa\Callback's were created
        $unique_leads2 = (clone $query2)->addSelect(DB::raw('DISTINCT JSON_UNQUOTE(properties->"$.attributes.lead_qa_id") AS `lead_qa_id`'))
                                        ->where(function($query){
                                            $query->where('a1.subject_type', 'App\Lead\Qa\Attempt')
                                                  ->orWhere('a1.subject_type', 'App\Lead\Qa\Callback');
                                        })->get()->pluck('lead_qa_id');

        if($delitions){
            $unique_leads2 = $unique_leads2->slice($delitions->total);
        }

        // Combine previous result sets
        $unique_leads = $unique_leads1->concat($unique_leads2)->unique()->count();

        /*
        |--------------------------------------------------------------------------
        | Conversations (First 4 attempts only).
        | A "conversation" is when an attempt is made and the client answered.
        |--------------------------------------------------------------------------
        */

        // Get all entries when "first_talk_at" was updated
        $conversations = (clone $query1)->addSelect('subject_id AS lead_qa_id')
                                        ->firstTalks()
                                        ->get();

        // Get the last attempt for each lead_qa_id resulting from the previous query ($conversations).
        // Not filtering by date range here, because we need the last attempt regardless when the attempt was created.
        $last_attempts = Activity::lastAttempts($conversations->pluck('lead_qa_id'))->get();

        // Combine both result sets to be able to tell from where the answer came from (Phone or WhatsApp)
        $conversations = $this->combine($conversations, $last_attempts);

        /*
        |--------------------------------------------------------------------------
        | Answers. (First 4 Attempts only)
        | An "answer" is when the question "talked_to_broker" was answered by the client.
        | To make sure that this answers were the resulting of an "attempt" and not a "callback",
        | we match the records against all "first_talk_at" entry updates.
        |--------------------------------------------------------------------------
        */

        $attempts_answers = (clone $query1)->addSelect('subject_id AS lead_qa_id')
                                  ->whereIn(DB::raw('JSON_UNQUOTE(properties->"$.attributes.talked_to_broker")'), ['s', 'n'])
                                  ->whereIn('subject_id', $conversations->pluck('lead_qa_id'))->get();

        // Get the last attempt for each lead_qa_id resulting from the previous query ($attempts_answers).
        // Not filtering by date range here, because we need the last attempt regardless when the attempt was created.
        $last_attempts = Activity::lastAttempts($attempts_answers->pluck('lead_qa_id'))->get();

        // Combine both result sets to be able to tell from where the answer came from (Phone or WhatsApp)
        $attempts_answers = self::combine($attempts_answers, $last_attempts);

        /*
        |--------------------------------------------------------------------------
        | Phone. (First 4 Attempts only)
        |--------------------------------------------------------------------------
        */

        // Get everytime someone logged a phone that doesn't work
        $phone_doesnt_work = (clone $query1)->where('properties->attributes->phone_works', 'n')->count();

        // Get all attempts made by phone
        $phone_attempts = (clone $query2)->where('a1.subject_type', 'App\Lead\Qa\Attempt')
                                         ->where('properties->attributes->channel', 't')
                                         ->count();

        if($delitions){
            $phone_attempts -= $delitions->attempts_t;
        }

        // Get all attempts made by phone that were answered by the client
        $phone_attempts_conversations = $conversations->filter(function($conv){
            return $conv->channel == 't';
        })->count();

        // Count all attempts made by phone that weren't answered by the client
        $phone_attempts_no_answers = $phone_attempts - $phone_attempts_conversations;

        // Get how many times the client answered "s" or "n" to "talked_to_broker"
        $phone_attempts_answers = $attempts_answers->filter(function($conv){
            return $conv->channel == 't';
        })->count();

        /*
        |--------------------------------------------------------------------------
        | WhatsApp. (First 4 Attempts only)
        |--------------------------------------------------------------------------
        */

        // Get all attempts made by whatsapp
        $wpp_attempts = (clone $query2)->where('a1.subject_type', 'App\Lead\Qa\Attempt')
                                       ->where('properties->attributes->channel', 'w')
                                       ->count();

        if($delitions){
            $wpp_attempts -= $delitions->attempts_w;
        }

        // Get all attempts made by phone that were answered by the client
        $wpp_attempts_conversations = $conversations->filter(function($conv){
            return $conv->channel == 'w';
        })->count();

        // Get how many times the client answered "s" or "n" to "talked_to_broker"
        $wpp_attempts_answers = $attempts_answers->filter(function($conv){
            return $conv->channel == 'w';
        })->count();

        /*
        |--------------------------------------------------------------------------
        | Answers.
        |--------------------------------------------------------------------------
        */

        $answers = (clone $query1)->addSelect('subject_id AS lead_qa_id', 'properties')
                                  ->whereIn(DB::raw('JSON_UNQUOTE(properties->"$.attributes.talked_to_broker")'), ['s', 'n'])
                                  ->get();

        // Get the last attempt/callback for each lead_qa_id resulting from the previous query ($attempts_answers).
        // Not filtering by date range here, because we need the last attempt/callback regardless when the attempt/callback was created.
        $last_contact_attempts = Activity::lastAttempts($answers->pluck('lead_qa_id'), true)->get();

        // Combine both result sets to be able to tell from where the answer came from (Phone or WhatsApp)
        $answers = self::combine($answers, $last_contact_attempts);

        //
        $answers_phone = $answers->filter(function($conv){
            return $conv->channel == 't';
        })->count();

        //
        $answers_wpp = $answers->filter(function($conv){
            return $conv->channel == 'w';
        })->count();

        // Get everytime an App\Lead\Lead had the talked_to_broker attribute updated with "s"
        $answers_s = $answers->filter(function($conv){
            return $conv->properties['attributes']['talked_to_broker'] == 's';
        })->count();

        // Get everytime an App\Lead\Lead had the talked_to_broker attribute updated with "n"
        $answers_n = $answers->filter(function($conv){
            return $conv->properties['attributes']['talked_to_broker'] == 'n';
        });

        // Get everytime an App\Lead\Qa had the hotlead attribute updated
        $hotleads = (clone $query1_rel)->whereNotNull(DB::raw('JSON_UNQUOTE(properties->"$.attributes.hotlead")'))
                                       ->where(DB::raw('JSON_UNQUOTE(properties->"$.attributes.hotlead")'), '<>', 'null')
                                       ->get();

        // Get everytime an App\Lead\Qa had the "searching_immobile" updated with "s"
        $searching_immobile = (clone $query1_rel)->where('properties->attributes->searching_immobile', 's')->get()->keyBy('subject_id');

        // Get only the entries that are both in $answers_n and $searching_immobile
        $no_contact = $searching_immobile->intersectByKeys($answers_n->keyBy('lead_qa_id'));

        // Get only the entries that are not in $hotleads
        $no_contact = $no_contact->diffKeys($hotleads->keyBy('subject_id'));

        /*
        |--------------------------------------------------------------------------
        | Gather data with subjects (QA) relationships
        |--------------------------------------------------------------------------
        */

        // Get everytime the client booked a visit
        $booked_visit = (clone $query1_rel)->where('properties->attributes->booked_visit', 's')->get();
        // Get everytime the client intends to book a visit
        $intend_to_visit = (clone $query1_rel)->where('properties->attributes->booked_visit', 'p')->get();
        // Get everytime the client visited
        $visited = (clone $query1_rel)->where('properties->attributes->visited', 's')->get();
        // Get everytime the client started the purchase
        $purchase_started = (clone $query1_rel)->where('properties->attributes->purchase_started', 's')->get();
        // Get everytime the client complete the purchase
        $purchased = (clone $query1_rel)->where('properties->attributes->purchased', 's')->get();

        /*
        |--------------------------------------------------------------------------
        | Return the data
        |--------------------------------------------------------------------------
        */

        return [
            // Total
            'unique_leads_count' => $unique_leads,
            // Telefone
            'phone_doesnt_work' => $phone_doesnt_work,
            'phone_attempts' => $phone_attempts,
            'phone_attempts_no_answers' => $phone_attempts_no_answers,
            'phone_attempts_conversations' => $phone_attempts_conversations,
            'phone_attempts_answers' => $phone_attempts_answers,
            // WhatsApp
            'wpp_attempts' => $wpp_attempts,
            'wpp_attempts_conversations' => $wpp_attempts_conversations,
            'wpp_attempts_answers' => $wpp_attempts_answers,
            // Respostas
            'answers_phone' => $answers_phone,
            'answers_wpp' => $answers_wpp,
            'answers_s' => $answers_s,
            'answers_n' => $answers_n->count(),
            'hotleads' => $hotleads->count(),
            'no_contact' => $no_contact->count(),
            'tabs' => [
                'booked_visit' => $booked_visit,
                'intend_to_visit' => $intend_to_visit,
                'visited' => $visited,
                'purchase_started' => $purchase_started,
                'purchased' => $purchased,
                'hotleads' => $hotleads,
                'no_contact' => $no_contact
            ]
        ];
    }

    /**
     * ...
     *
     * @param Illuminate\Database\Eloquent\Collection $a
     * @param Illuminate\Database\Eloquent\Collection $b
     * @return boolean
     */
    private function combine(Collection $a, Collection $b)
    {
        $new = $a->map(function($item_a) use($b){
            $key = $b->search(function($item_b) use($item_a){
                return $item_b->lead_qa_id == $item_a->lead_qa_id;
            });

            if($key !== false){
                $item_a->channel = $b[$key]->channel;
                return $item_a;
            }
        });

        return $new->filter(function($item){
            return isset($item->channel);
        });
    }
}
