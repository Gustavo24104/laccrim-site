<?php

namespace App\Http\Middleware;

use App\Services\Portal\AcessoLivreLocalService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AcessoLivreLocal
{
    public function __construct(private AcessoLivreLocalService $acessoLivre) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->acessoLivre->habilitado($request)) {
            return $next($request);
        }

        if (! Auth::check()) {
            Auth::login($this->acessoLivre->usuarioDeTestes());
        }

        if ($this->acessoLivre->ehUsuarioDeTestes($request->user())) {
            $this->acessoLivre->aplicarCargoSimulado($request->user(), $request->session());
        }

        return $next($request);
    }
}
