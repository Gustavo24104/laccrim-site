<?php

namespace App\Http\Controllers\Portal;

use App\Enums\PerfilAcesso;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConfiguracoesController extends Controller
{
    public function __invoke(Request $request): View
    {
        return view('paginas.portal.configuracoes', [
            'titulo' => 'Configurações do perfil',
            'exibirAusencia' => $request->user()->possuiPerfil(PerfilAcesso::Presidencia),
        ]);
    }
}
