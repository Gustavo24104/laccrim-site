<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\DefinirAusenciaRequest;
use App\Services\Portal\DefinirAusenciaService;
use Illuminate\Http\RedirectResponse;

class AusenciaController extends Controller
{
    public function __invoke(DefinirAusenciaRequest $request, DefinirAusenciaService $definirAusencia): RedirectResponse
    {
        $definirAusencia->handle($request->user(), $request->boolean('ausente'));

        return redirect()->route('portal.configuracoes')->with('status', 'ausencia-atualizada');
    }
}
