<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next, $role = null)
    {
        if (Auth::check() && Auth::user()->role->name === $role) {
            return $next($request);
        }

        return redirect('/')->with('error', 'Accès réservé aux administrateurs.');
    }
}