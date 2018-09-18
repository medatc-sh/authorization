<?php

namespace Authorization;

use Closure;

class PermissionAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        app()['authorization']->connect();
        if (app()['authorization']->forbidden(
            $request->header('token'),
            $request->getRequestUri(),
            $request->method()
        )) {
            return response('Forbidden', 403);
        }

        return $next($request);
    }
}