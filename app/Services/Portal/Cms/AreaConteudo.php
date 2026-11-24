<?php

namespace App\Services\Portal\Cms;

/**
 * Definição de uma área editável do site, lida de config/cms.php.
 */
final readonly class AreaConteudo
{
    /**
     * @param  array{rota: string, ancora?: string}  $previa
     * @param  list<array{nome: string, rotulo: string, caminho: string, tipo: string, obrigatorio?: bool, max?: int}>  $campos
     */
    public function __construct(
        public string $chave,
        public string $rotulo,
        public string $pagina,
        public array $previa,
        public array $campos,
    ) {}

    /**
     * @return list<string>
     */
    public function nomesDosCampos(): array
    {
        return array_column($this->campos, 'nome');
    }

    /**
     * @return list<string>
     */
    public function camposHtml(): array
    {
        return array_values(array_column(
            array_filter($this->campos, fn (array $campo): bool => $campo['tipo'] === 'html'),
            'nome',
        ));
    }
}
