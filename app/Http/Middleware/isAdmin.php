<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class isAdmin
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
        if(\Auth::user()->role_id == User::USER_ID){
            return abort(404);
        }

        return $next($request);
    }
}
