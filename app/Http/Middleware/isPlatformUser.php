<?php

namespace App\Http\Middleware;

use Closure;

class isPlatformUser
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
        if ($user->isPlatform())
            return $next($request);
        else
            return response("Forbidden action.", 403);
    }
}
