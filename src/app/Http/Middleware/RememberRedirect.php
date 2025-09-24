<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RememberRedirect
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('get') && $request->is('login')){
            $redirect = $request->query('redirect');

            if(is_string($redirect) && str_starts_with($redirect, '/')){
                $request->session()->put('url.intended', $redirect);
            }
        }

        return $next($request);
    }
}
