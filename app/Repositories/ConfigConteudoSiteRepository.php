<?php

namespace App\Repositories;

use App\Contracts\ConteudoSiteRepository;

class ConfigConteudoSiteRepository implements ConteudoSiteRepository
{
    public function identidade(): array
    {
        return config('laccrim.identidade');
    }

    public function cores(): array
    {
        return config('laccrim.cores', []);
    }

    public function itensMenu(): array
    {
        return config('laccrim.menu', []);
    }

    public function itemMenuDestaque(): array
    {
        return config('laccrim.menu_destaque');
    }

    public function rodape(): array
    {
        return config('laccrim.rodape');
    }

    public function secoesHome(): array
    {
        return config('laccrim.home.secoes', []);
    }

    public function conteudoSecaoHome(string $chave): array
    {
        return config("laccrim.home.{$chave}", []);
    }

    public function paginaSobre(): array
    {
        return config('laccrim.sobre', []);
    }
}
