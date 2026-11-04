<?php

namespace App\Contracts;

/**
 * Conteúdo institucional editável do site público (identidade, menu, rodapé e home).
 */
interface ConteudoSiteRepository
{
    /**
     * @return array{sigla: string, descricao: string, logotipo: ?string}
     */
    public function identidade(): array;

    /**
     * @return array<string, string>
     */
    public function cores(): array;

    /**
     * @return list<array{rotulo: string, rota?: string, url?: string}>
     */
    public function itensMenu(): array;

    /**
     * @return array{rotulo: string, rota?: string, url?: string}
     */
    public function itemMenuDestaque(): array;

    /**
     * @return array<string, mixed>
     */
    public function rodape(): array;

    /**
     * @return list<array{chave: string, visivel: bool, ordem: int}>
     */
    public function secoesHome(): array;

    /**
     * @return array<string, mixed>
     */
    public function conteudoSecaoHome(string $chave): array;

    /**
     * Conteúdo institucional da página Sobre Nós.
     * A implementação do CMS do portal substitui a fonte sem alterar a página.
     *
     * @return array{
     *     banner: array{titulo: string, texto: string},
     *     quem_somos: array{titulo: string, blocos: list<array{chave: string, titulo: string, texto: string}>},
     *     estatuto: array{titulo: string, arquivo: ?string},
     *     corpo_social: array{titulo: string},
     *     professores: array{titulo: string}
     * }
     */
    public function paginaSobre(): array;
}
