<?php

namespace App\Services\Portal\Cms;

use App\Contracts\ConteudoEditavelRepository;

/**
 * Aplica sobre o conteúdo original (config/laccrim.php) os valores publicados
 * pelo CMS — ou o rascunho da área em pré-visualização.
 */
class SobreposicaoConteudoService
{
    /**
     * @var array<string, array<string, string>>|null
     */
    private ?array $publicados = null;

    private ?AreaConteudo $areaEmPrevia = null;

    private bool $previaResolvida = false;

    public function __construct(
        private CatalogoAreasConteudo $catalogo,
        private ConteudoEditavelRepository $conteudos,
        private PreviaConteudoService $previa,
    ) {}

    /**
     * @param  string  $origem  Caminho do bloco em config, ex.: laccrim.home.hero
     * @param  array<string, mixed>  $dados  Bloco original devolvido pelo repositório de config
     * @return array<string, mixed>
     */
    public function aplicar(string $origem, array $dados): array
    {
        $prefixo = "{$origem}.";

        foreach ($this->catalogo->todas() as $area) {
            $valores = $this->valoresSobrepostos($area);

            if ($valores === []) {
                continue;
            }

            foreach ($area->campos as $campo) {
                if (str_starts_with($campo['caminho'], $prefixo) && array_key_exists($campo['nome'], $valores)) {
                    data_set($dados, substr($campo['caminho'], strlen($prefixo)), $valores[$campo['nome']]);
                }
            }
        }

        return $dados;
    }

    /**
     * Valores em vigor no site: o que foi publicado ou, na falta, o original do config.
     *
     * @return array<string, string>
     */
    public function valoresVigentes(AreaConteudo $area): array
    {
        $publicados = $this->publicados()[$area->chave] ?? [];
        $valores = [];

        foreach ($area->campos as $campo) {
            $valores[$campo['nome']] = (string) ($publicados[$campo['nome']] ?? config($campo['caminho']) ?? '');
        }

        return $valores;
    }

    /**
     * @return array<string, string>
     */
    private function valoresSobrepostos(AreaConteudo $area): array
    {
        if ($this->areaEmPrevia()?->chave === $area->chave) {
            $rascunho = $this->conteudos->buscarPorChave($area->chave)?->rascunho;

            if (is_array($rascunho)) {
                return $rascunho;
            }
        }

        return $this->publicados()[$area->chave] ?? [];
    }

    private function areaEmPrevia(): ?AreaConteudo
    {
        if (! $this->previaResolvida) {
            $this->areaEmPrevia = $this->previa->areaEmPrevia();
            $this->previaResolvida = true;
        }

        return $this->areaEmPrevia;
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function publicados(): array
    {
        return $this->publicados ??= $this->conteudos->publicados();
    }
}
