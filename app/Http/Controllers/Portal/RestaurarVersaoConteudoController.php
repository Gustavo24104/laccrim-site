<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\Portal\Cms\AreaConteudo;
use App\Services\Portal\Cms\EdicaoConteudoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RestaurarVersaoConteudoController extends Controller
{
    public function __invoke(Request $request, AreaConteudo $area, int $versao, EdicaoConteudoService $edicao): RedirectResponse
    {
        $edicao->restaurarVersao($area, $versao, $request->user());

        return redirect()->route('portal.conteudo.edit', $area->chave)
            ->with('status', 'Versão restaurada como rascunho. Revise e envie para aprovação.');
    }
}
