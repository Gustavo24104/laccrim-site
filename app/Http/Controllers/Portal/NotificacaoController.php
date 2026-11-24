<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Services\Portal\CaixaNotificacoesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{
    public function marcarComoLida(Request $request, string $notificacao, CaixaNotificacoesService $caixa): RedirectResponse
    {
        $destino = $caixa->marcarComoLida($request->user(), $notificacao);

        return $destino ? redirect($destino) : back();
    }

    public function marcarTodasComoLidas(Request $request, CaixaNotificacoesService $caixa): RedirectResponse
    {
        $caixa->marcarTodasComoLidas($request->user());

        return back();
    }
}
