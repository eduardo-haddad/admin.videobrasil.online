<?php

namespace Feedback\Middleware;

use App\Client\ClientAccess;
use App\Campaign;
use Closure;

class UserAccess
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
        $user = \Auth::user();
        $client = $request->route('client');

        if($request->route('campaign')) {
            $campaign = Campaign::find($request->route('campaign'));
            $client = $campaign->listings->first()->client;
        }

        //Staff users
        $autorizedRoles = [
            'root',
            'campaign-manager',
            'lead-manager',
        ];

        if($user->hasRole($autorizedRoles)) {
            return $next($request);
        }

        if(isset($client) && !empty($client) && ClientAccess::where('user_id', $user->id)->where('client_id', $client->user_id)->count() !== 0){
            return $next($request);
        }

        return response('', 403);
    }
}
