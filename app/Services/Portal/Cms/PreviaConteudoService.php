<?php

namespace App\Services\Portal\Cms;

use App\Enums\PermissaoPortal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * Identifica quando uma página pública é aberta como pré-visualização de um
 * rascunho (?previa=chave-da-area). Só quem edita o site vê o rascunho.
 */
class PreviaConteudoService
{
    public const string PARAMETRO = 'previa';

    public function __construct(
        private Request $request,
        private CatalogoAreasConteudo $catalogo,
    ) {}

    public function areaEmPrevia(): ?AreaConteudo
    {
        $chave = $this->request->query(self::PARAMETRO);

        if (! is_string($chave) || $this->request->user() === null) {
            return null;
        }

        if (Gate::forUser($this->request->user())->denies(PermissaoPortal::EditarSite->value)) {
            return null;
        }

        return $this->catalogo->buscar($chave);
    }

    public function urlPrevia(AreaConteudo $area): string
    {
        $url = route($area->previa['rota'], [self::PARAMETRO => $area->chave]);

        return isset($area->previa['ancora']) ? "{$url}#{$area->previa['ancora']}" : $url;
    }
}
