<?php

namespace App\Http\Middleware;

use App\Models\Utils\Usuario\Login;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthHashMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $acesso = new Login();

        // Verifica se a hash é válida
        $receivedHash = $request->header('X-Hash');
        $data = $acesso->getLoginByHash($request->header('X-Hash') ?? '');
        $validHash = $data->first()->hash_login ?? '';

        if($receivedHash !== $validHash){
            return response('Acesso não autorizado', 401);
        }

        return $next($request);
    }
}
