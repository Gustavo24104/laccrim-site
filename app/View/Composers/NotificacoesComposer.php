<?php

namespace App\View\Composers;

use App\Services\Portal\CaixaNotificacoesService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Entrega ao sino do portal as notificações recentes e a contagem de não lidas.
 */
class NotificacoesComposer
{
    public function __construct(
        private CaixaNotificacoesService $caixa,
        private Request $request,
    ) {}

    public function compose(View $view): void
    {
        $usuario = $this->request->user();

        $view->with('painelNotificacoes', $usuario
            ? $this->caixa->painel($usuario)
            : ['nao_lidas' => 0, 'itens' => []]);
    }
}
