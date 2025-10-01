<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DevocionalMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(401, 'Não autenticado');
        }

        $user = auth()->user();

        // Apenas admins e técnicos podem gerenciar devocionais
        if (!in_array($user->type_user, ['admin', 'tecnico'])) {
            abort(403, 'Você não tem permissão para acessar os devocionais');
        }

        return $next($request);
    }
}
