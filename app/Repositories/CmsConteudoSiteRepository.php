<?php

namespace App\Repositories;

use App\Contracts\ConteudoSiteRepository;
use App\Services\Portal\Cms\SobreposicaoConteudoService;

/**
 * Conteúdo institucional com as alterações publicadas pelo CMS do portal
 * aplicadas sobre a fonte original.
 */
class CmsConteudoSiteRepository implements ConteudoSiteRepository
{
    public function __construct(
        private ConteudoSiteRepository $original,
        private SobreposicaoConteudoService $sobreposicao,
    ) {}

    public function identidade(): array
    {
        return $this->sobreposicao->aplicar('laccrim.identidade', $this->original->identidade());
    }

    public function cores(): array
    {
        return $this->original->cores();
    }

    public function itensMenu(): array
    {
        return $this->original->itensMenu();
    }

    public function itemMenuDestaque(): array
    {
        return $this->original->itemMenuDestaque();
    }

    public function rodape(): array
    {
        return $this->sobreposicao->aplicar('laccrim.rodape', $this->original->rodape());
    }

    public function secoesHome(): array
    {
        return $this->original->secoesHome();
    }

    public function conteudoSecaoHome(string $chave): array
    {
        return $this->sobreposicao->aplicar("laccrim.home.{$chave}", $this->original->conteudoSecaoHome($chave));
    }

    public function paginaSobre(): array
    {
        return $this->sobreposicao->aplicar('laccrim.sobre', $this->original->paginaSobre());
    }
}
