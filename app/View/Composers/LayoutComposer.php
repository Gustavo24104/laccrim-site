<?php

namespace App\View\Composers;

use App\Contracts\ConteudoSiteRepository;
use App\Services\ResolvedorDeLink;
use Illuminate\View\View;

/**
 * Entrega ao layout público a identidade, o menu, o rodapé e as cores configuráveis.
 */
class LayoutComposer
{
    public function __construct(
        private ConteudoSiteRepository $conteudoSite,
        private ResolvedorDeLink $resolvedorDeLink,
    ) {}

    public function compose(View $view): void
    {
        $rodape = $this->conteudoSite->rodape();
        $rodape['links_legais'] = array_map($this->resolverLink(...), $rodape['links_legais'] ?? []);

        $view->with([
            'identidade' => $this->conteudoSite->identidade(),
            'itensMenu' => array_map($this->resolverLink(...), $this->conteudoSite->itensMenu()),
            'itemMenuDestaque' => $this->resolverLink($this->conteudoSite->itemMenuDestaque()),
            'rodape' => $rodape,
            'coresPersonalizadas' => $this->coresValidas(),
        ]);
    }

    /**
     * @param  array{rotulo: string, rota?: string, url?: string}  $link
     * @return array{rotulo: string, href: string, ativo: bool}
     */
    private function resolverLink(array $link): array
    {
        return [
            'rotulo' => $link['rotulo'],
            'href' => $this->resolvedorDeLink->href($link),
            'ativo' => $this->resolvedorDeLink->estaAtivo($link),
        ];
    }

    /**
     * Mantém apenas nomes simples e cores hexadecimais, impedindo injeção de CSS.
     *
     * @return array<string, string>
     */
    private function coresValidas(): array
    {
        return collect($this->conteudoSite->cores())
            ->filter(fn (mixed $valor, mixed $nome): bool => is_string($nome)
                && preg_match('/^[a-z][a-z0-9-]*$/', $nome) === 1
                && is_string($valor)
                && preg_match('/^#(?:[0-9a-fA-F]{3}){1,2}$/', $valor) === 1)
            ->all();
    }
}
