<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->session()->has('auth_token')) {
            return redirect()
                ->route('auth.login.form')
                ->with('error', 'Debes iniciar sesión para acceder a esta sección.');
        }

        return $next($request);
    }
}
