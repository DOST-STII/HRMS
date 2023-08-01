<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class checkStaff
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
       if(Auth::user()->usertype == 'Staff')
           return $next($request);
        return redirect('/');
    }
}
