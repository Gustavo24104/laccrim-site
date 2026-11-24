<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\AtualizarFotoPerfilRequest;
use App\Services\Portal\AtualizarFotoPerfilService;
use Illuminate\Http\RedirectResponse;

class FotoPerfilController extends Controller
{
    public function update(AtualizarFotoPerfilRequest $request, AtualizarFotoPerfilService $atualizarFoto): RedirectResponse
    {
        $atualizarFoto->handle($request->user(), $request->file('foto'));

        return redirect()->route('portal.configuracoes')->with('status', 'foto-atualizada');
    }
}
