<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Token de exemplo - você pode configurar no .env
        $validTokens = [
            config('app.api_token', 'your-default-api-token'),
            // Adicione mais tokens se necessário
        ];

        $token = $request->header('Authorization');

        // Remove 'Bearer ' se presente
        if (strpos($token, 'Bearer ') === 0) {
            $token = substr($token, 7);
        }

        // Também aceita token como parâmetro de query
        if (!$token) {
            $token = $request->query('token');
        }

        if (!$token || !in_array($token, $validTokens)) {
            return response()->json([
                'success' => false,
                'message' => 'Token de acesso inválido ou ausente',
                'error' => 'Unauthorized'
            ], 401);
        }

        return $next($request);
    }
}
