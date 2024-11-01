<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserIsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if(auth()->user()->isAdmin()) {
            return $next($request);
        }

        return redirect()->route('home.index')->with('error', 'Você não tem permissão para acessar essa página');
    }
}
