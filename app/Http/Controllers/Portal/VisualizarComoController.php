<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\VisualizarComoRequest;
use App\Services\Portal\AcessoLivreLocalService;
use Illuminate\Http\RedirectResponse;

class VisualizarComoController extends Controller
{
    public function __invoke(VisualizarComoRequest $request, AcessoLivreLocalService $acessoLivre): RedirectResponse
    {
        $acessoLivre->simularCargo($request->session(), $request->validated('perfil') ?: null);

        return back();
    }
}
