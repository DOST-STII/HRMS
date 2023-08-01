<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class checkBudget
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
        if(Auth::user()->usertype == 'Marshal' || Auth::user()->usertype == 'q')
           return $next($request);
        return redirect('/');
    }
}
