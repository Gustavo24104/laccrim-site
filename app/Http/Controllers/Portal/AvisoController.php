<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\EnviarAvisoRequest;
use App\Services\Portal\EnviarAvisoGeralService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AvisoController extends Controller
{
    public function create(): View
    {
        return view('paginas.portal.avisos.criar', ['titulo' => 'Enviar aviso']);
    }

    public function store(EnviarAvisoRequest $request, EnviarAvisoGeralService $enviarAviso): RedirectResponse
    {
        $notificados = $enviarAviso->handle(
            $request->user(),
            $request->validated('titulo'),
            $request->validated('mensagem'),
        );

        return redirect()->route('portal.avisos.create')
            ->with('status', "Aviso enviado para {$notificados} ".($notificados === 1 ? 'membro.' : 'membros.'));
    }
}
