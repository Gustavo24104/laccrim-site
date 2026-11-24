<?php

namespace App\Http\Requests\Portal;

use App\Services\Portal\AcessoLivreLocalService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisualizarComoRequest extends FormRequest
{
    /**
     * Fora do acesso livre em localhost, a rota se comporta como inexistente.
     */
    public function authorize(AcessoLivreLocalService $acessoLivre): bool
    {
        abort_unless($acessoLivre->habilitado($this) && $acessoLivre->ehUsuarioDeTestes($this->user()), 404);

        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'perfil' => [
                'nullable',
                'string',
                Rule::in(app(AcessoLivreLocalService::class)->cargosSimulaveis()->pluck('slug')),
            ],
        ];
    }
}
