<?php

namespace App\Http\Middleware;

use Closure;

class Facebook
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
        if($request->isMethod('get') && $request->get('hub_verify_token') === env('FACEBOOK_VERIFY_TOKEN')){
            // Verification request
            return response($request->get('hub_challenge'), 200)->header('Content-Type', 'text/plain');
        }

        if($request->header('X-Hub-Signature') === 'sha1=' . hash_hmac('sha1', $request->getContent(), env('FACEBOOK_APP_SECRET'))){
            // Event notification
            return $next($request);
        }

        abort(403);
    }
}
