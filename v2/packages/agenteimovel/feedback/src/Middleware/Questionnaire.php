<?php

namespace Feedback\Middleware;

use Feedback\Lead\Access;
use Closure;

class Questionnaire
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        $lead_id = $request->route('lead');

        if($user->can('manage', 'App\Lead\Lead') || $user->accesses()->notExpired($lead_id)->exists()){
            return $next($request);
        }

        return response(view('feedback::leads.questionnaire', ['lead_id' => $lead_id]));
    }
}
