<?php

namespace App\Http\Middleware;

use Closure;

class RedirectInsecure
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->prodIsInsecure($request)) {
            return redirect()->secure($request->path(), 301);
        }

        return $next($request);
    }

    /**
     * @param $request
     *
     * @return bool
     */
    private function prodIsInsecure($request)
    {
        return app()->environment('production') && !$request->secure();
    }
}
