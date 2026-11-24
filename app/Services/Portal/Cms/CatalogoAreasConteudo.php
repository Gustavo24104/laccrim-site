<?php

namespace App\Services\Portal\Cms;

/**
 * Lista as áreas editáveis do site definidas em config/cms.php.
 */
class CatalogoAreasConteudo
{
    /**
     * @return array<string, AreaConteudo>
     */
    public function todas(): array
    {
        $areas = [];

        foreach (config('cms.areas', []) as $chave => $definicao) {
            $areas[$chave] = new AreaConteudo(
                chave: $chave,
                rotulo: $definicao['rotulo'],
                pagina: $definicao['pagina'],
                previa: $definicao['previa'],
                campos: $definicao['campos'],
            );
        }

        return $areas;
    }

    public function buscar(string $chave): ?AreaConteudo
    {
        return $this->todas()[$chave] ?? null;
    }

    /**
     * @return array<string, list<AreaConteudo>>
     */
    public function agrupadasPorPagina(): array
    {
        $grupos = [];

        foreach ($this->todas() as $area) {
            $grupos[$area->pagina][] = $area;
        }

        return $grupos;
    }

    public function versoesMantidas(): int
    {
        return max(1, (int) config('cms.versoes_mantidas', 3));
    }
}
