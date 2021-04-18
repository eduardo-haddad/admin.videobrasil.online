<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\XmlIntegration\IntegrationClient;
use Auth;

class XmlManagers
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
        $user = User::find(Auth::user()['id']);

        if(!$user->hasRole('xml-manager')){
            return redirect()->route('home');
        }

        return $next($request);
    }
}
