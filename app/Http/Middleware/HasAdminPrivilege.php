<?php

namespace App\Http\Middleware;

use Closure;

class HasAdminPrivilege
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (auth()->guest()) return redirect()->to('login');

        if (auth()->user()->is_admin != 1) return redirect()->to('401');

        return $next($request);
    }
}
