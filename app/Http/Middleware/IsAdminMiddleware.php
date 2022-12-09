<?php

namespace App\Http\Middleware;

use Closure;

class IsAdminMiddleware
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
      // dd(auth()->user()->is_admin);
        if (!auth()->user()->is_admin) {
          return redirect()->route('projects.index');
        }

        return $next($request);
    }
}
