<?php
// filepath: /Applications/XAMPP/xamppfiles/htdocs/laravel/louvor/app/Http/Middleware/RedirectOn419.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;

class RedirectOn419
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            return $next($request);
        } catch (TokenMismatchException $e) {
            // Se for uma requisição AJAX, retorna JSON com status 419
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expired'], 419);
            }

            // Se estiver tentando acessar logout via GET, redireciona para login
            if ($request->is('logout') && $request->method() === 'GET') {
                return redirect()->route('login');
            }

            // Para outras requisições normais, redireciona para login
            return redirect()->route('login');
        }
    }
}
