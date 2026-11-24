<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Enquanto a senha provisória não for trocada, o usuário só alcança a tela de primeiro acesso.
 */
class ExigirTrocaDeSenha
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->deve_trocar_senha && ! $request->routeIs('portal.primeiro-acesso.*')) {
            return redirect()->route('portal.primeiro-acesso.edit');
        }

        return $next($request);
    }
}
