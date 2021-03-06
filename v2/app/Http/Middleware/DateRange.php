<?php

namespace App\Http\Middleware;

use Closure;

class DateRange
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
        if($request->filled('date_range')){
            list($from_date, $to_date) = explode(' - ', $request->get('date_range'));
            $request->merge(['from_date' => $from_date, 'to_date' => $to_date]);
        }

        return $next($request);
    }
}
