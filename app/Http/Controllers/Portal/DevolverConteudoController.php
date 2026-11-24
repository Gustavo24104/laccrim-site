<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\DevolverConteudoRequest;
use App\Services\Portal\Cms\AreaConteudo;
use App\Services\Portal\Cms\FluxoAprovacaoConteudoService;
use Illuminate\Http\RedirectResponse;

class DevolverConteudoController extends Controller
{
    public function __invoke(DevolverConteudoRequest $request, AreaConteudo $area, FluxoAprovacaoConteudoService $fluxo): RedirectResponse
    {
        $fluxo->devolver($area, $request->user(), $request->validated('motivo'));

        return redirect()->route('portal.conteudo.edit', $area->chave)->with('status', 'Alteração devolvida para ajustes.');
    }
}
