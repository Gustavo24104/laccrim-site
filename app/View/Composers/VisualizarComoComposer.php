<?php

namespace App\View\Composers;

use App\Services\Portal\AcessoLivreLocalService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Entrega ao seletor "Visualizar como" os cargos simuláveis e o cargo atual (somente em localhost).
 */
class VisualizarComoComposer
{
    public function __construct(
        private AcessoLivreLocalService $acessoLivre,
        private Request $request,
    ) {}

    public function compose(View $view): void
    {
        $habilitado = $this->acessoLivre->habilitado($this->request)
            && $this->acessoLivre->ehUsuarioDeTestes($this->request->user());

        $view->with([
            'visualizarComoHabilitado' => $habilitado,
            'cargosSimulaveis' => $habilitado ? $this->acessoLivre->cargosSimulaveis() : collect(),
            'cargoSimulado' => $habilitado ? $this->acessoLivre->cargoSimulado($this->request->session()) : null,
        ]);
    }
}
