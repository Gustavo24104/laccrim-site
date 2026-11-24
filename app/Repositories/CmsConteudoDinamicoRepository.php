<?php

namespace App\Repositories;

use App\Contracts\ConteudoDinamicoRepository;
use App\Contracts\UsuarioRepository;
use App\Services\Portal\Cms\SobreposicaoConteudoService;

/**
 * Conteúdo dinâmico com os textos publicados pelo CMS e a foto de perfil do
 * portal nos integrantes ligados a uma conta (membro_portal_id).
 */
class CmsConteudoDinamicoRepository implements ConteudoDinamicoRepository
{
    public function __construct(
        private ConteudoDinamicoRepository $original,
        private SobreposicaoConteudoService $sobreposicao,
        private UsuarioRepository $usuarios,
    ) {}

    public function ultimasPublicacoes(int $limite): array
    {
        return $this->original->ultimasPublicacoes($limite);
    }

    public function proximosEventos(int $limite): array
    {
        return $this->original->proximosEventos($limite);
    }

    public function publicacoesEmDestaque(int $limite): array
    {
        return $this->original->publicacoesEmDestaque($limite);
    }

    public function corpoSocialPublico(): array
    {
        $integrantes = $this->original->corpoSocialPublico();
        $ids = array_values(array_unique(array_filter(
            array_map(fn (array $pessoa): ?int => isset($pessoa['membro_portal_id']) ? (int) $pessoa['membro_portal_id'] : null, $integrantes),
        )));
        $fotos = $this->usuarios->urlsFotoPerfil($ids);

        return array_map(function (array $pessoa) use ($fotos): array {
            $id = isset($pessoa['membro_portal_id']) ? (int) $pessoa['membro_portal_id'] : null;

            if ($id !== null && isset($fotos[$id])) {
                $pessoa['foto'] = $fotos[$id];
            }

            return $pessoa;
        }, $integrantes);
    }

    public function professoresOrientadoresPublicos(): array
    {
        return $this->original->professoresOrientadoresPublicos();
    }

    public function categoriasPublicacao(): array
    {
        return $this->original->categoriasPublicacao();
    }

    public function publicacoesPublicas(): array
    {
        return $this->original->publicacoesPublicas();
    }

    public function chavesDoGrupo(string $grupo): array
    {
        return $this->original->chavesDoGrupo($grupo);
    }

    public function textosListagem(string $pagina): array
    {
        return $this->sobreposicao->aplicar("laccrim.publicacoes.paginas.{$pagina}", $this->original->textosListagem($pagina));
    }

    public function porPaginaPublicacoes(): int
    {
        return $this->original->porPaginaPublicacoes();
    }
}
