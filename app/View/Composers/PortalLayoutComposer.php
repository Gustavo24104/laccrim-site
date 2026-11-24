<?php

namespace App\View\Composers;

use App\Services\Portal\MenuPortalService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Entrega ao layout do portal o usuário autenticado e o menu lateral filtrado pelas permissões dele.
 */
class PortalLayoutComposer
{
    public function __construct(
        private MenuPortalService $menuPortal,
        private Request $request,
    ) {}

    public function compose(View $view): void
    {
        $usuario = $this->request->user();

        $view->with([
            'usuario' => $usuario,
            'itensMenuPortal' => $usuario ? $this->menuPortal->itensPara($usuario) : [],
        ]);
    }
}
