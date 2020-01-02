<?php

namespace App\Http\Middleware;

use Closure;

class isOperator
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
        if ($user->isOperator())
            return $next($request);
        else
            return response("Forbidden action.", 403);
    }
}
