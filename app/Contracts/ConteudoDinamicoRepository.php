<?php

namespace App\Contracts;

/**
 * Conteúdo aprovado e publicado que alimenta as seções dinâmicas do site.
 * Datas são retornadas em UTC (ISO 8601).
 */
interface ConteudoDinamicoRepository
{
    /**
     * Notícias, editais e atas já aprovadas e publicadas, das mais recentes para as mais antigas.
     * Pendentes, rejeitadas e não publicadas não entram no site.
     *
     * @return list<array{categoria: string, titulo: string, resumo: string, autor: string, publicado_em: string, slug?: string}>
     */
    public function ultimasPublicacoes(int $limite): array;

    /**
     * Eventos que ainda não começaram, dos mais próximos para os mais distantes.
     *
     * @return list<array{categoria: string, titulo: string, resumo: string, inicio: string, local: string, inscricoes: string}>
     */
    public function proximosEventos(int $limite): array;

    /**
     * @return list<array{tipo: string, titulo: string, autor: string, publicado_em: string}>
     */
    public function publicacoesEmDestaque(int $limite): array;

    /**
     * Integrantes do Corpo Social já aprovados e com exibição pública autorizada pelo próprio membro.
     * membro_portal_id, quando existir, liga o integrante ao cadastro do portal e não é exibido no site.
     * hierarquia aceita: presidencia, vice_presidencia, diretoria, coordenacao, membro.
     * A foto, quando houver, usa 1080×1350 pixels (proporção 4:5).
     *
     * @return list<array{nome: string, cargo: string, descricao: string, foto: ?string, hierarquia: string, ordem: int, membro_portal_id?: ?int}>
     */
    public function corpoSocialPublico(): array;

    /**
     * Professores orientadores já aprovados para exibição pública.
     * A foto, quando houver, usa 1080×1350 pixels (proporção 4:5).
     *
     * @return list<array{nome: string, titulacao: string, area_atuacao: string, apresentacao: string, foto: ?string, ordem: int}>
     */
    public function professoresOrientadoresPublicos(): array;

    /**
     * Categorias da listagem, na ordem definida pelo CMS.
     * rotulo aparece no cartão; rotulo_filtro aparece no filtro. A chave é estável.
     *
     * @return list<array{chave: string, rotulo: string, rotulo_filtro?: string, ordem?: int}>
     */
    public function categoriasPublicacao(): array;

    /**
     * Acervo público: situacao aprovado, publicado e slug válido.
     * Datas em UTC (ISO 8601). Anexos trazem nome, url e, quando for PDF, mime.
     *
     * @return list<array<string, mixed>>
     */
    public function publicacoesPublicas(): array;

    /**
     * Chaves de categoria que compõem um agrupamento da listagem, como editais e atas.
     *
     * @return list<string>
     */
    public function chavesDoGrupo(string $grupo): array;

    /**
     * @return array{titulo?: string, texto?: string}
     */
    public function textosListagem(string $pagina): array;

    public function porPaginaPublicacoes(): int;
}
