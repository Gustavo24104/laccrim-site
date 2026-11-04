<?php

namespace App\Repositories;

use App\Contracts\ConteudoDinamicoRepository;
use Illuminate\Support\Carbon;

class ConfigConteudoDinamicoRepository implements ConteudoDinamicoRepository
{
    public function ultimasPublicacoes(int $limite): array
    {
        return collect($this->acervo())
            ->filter(fn (array $publicacao): bool => $this->publicacaoVisivel($publicacao))
            ->sortBy([
                ['publicado_em', 'desc'],
                ['slug', 'asc'],
            ])
            ->take($limite)
            ->values()
            ->all();
    }

    public function proximosEventos(int $limite): array
    {
        return collect(config('laccrim.exemplos.eventos', []))
            ->filter(fn (array $evento): bool => Carbon::parse($evento['inicio'])->isFuture())
            ->sortBy('inicio')
            ->take($limite)
            ->values()
            ->all();
    }

    public function publicacoesEmDestaque(int $limite): array
    {
        return collect(config('laccrim.exemplos.destaques', []))
            ->sortByDesc('publicado_em')
            ->take($limite)
            ->values()
            ->all();
    }

    public function corpoSocialPublico(): array
    {
        return $this->publicados('corpo_social');
    }

    public function professoresOrientadoresPublicos(): array
    {
        return $this->publicados('professores');
    }

    public function categoriasPublicacao(): array
    {
        return config('laccrim.publicacoes.categorias', []);
    }

    public function publicacoesPublicas(): array
    {
        return collect($this->acervo())
            ->filter(fn (array $publicacao): bool => $this->publicacaoVisivel($publicacao) && $this->slugValido($publicacao))
            ->sortBy([
                ['publicado_em', 'desc'],
                ['slug', 'asc'],
            ])
            ->values()
            ->all();
    }

    public function chavesDoGrupo(string $grupo): array
    {
        return collect(config("laccrim.publicacoes.grupos.{$grupo}", []))
            ->filter(fn (mixed $chave): bool => is_string($chave) && $chave !== '')
            ->values()
            ->all();
    }

    public function textosListagem(string $pagina): array
    {
        $textos = config("laccrim.publicacoes.paginas.{$pagina}", []);

        return is_array($textos) ? $textos : [];
    }

    public function porPaginaPublicacoes(): int
    {
        $valor = config('laccrim.publicacoes.por_pagina', 9);

        return is_int($valor) && $valor > 0 ? $valor : 9;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function acervo(): array
    {
        return collect(config('laccrim.exemplos.publicacoes', []))
            ->filter(fn (mixed $publicacao): bool => is_array($publicacao))
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $publicacao
     */
    private function publicacaoVisivel(array $publicacao): bool
    {
        return ($publicacao['situacao'] ?? null) === 'aprovado'
            && ($publicacao['publicado'] ?? false) === true;
    }

    /**
     * @param  array<string, mixed>  $publicacao
     */
    private function slugValido(array $publicacao): bool
    {
        return is_string($publicacao['slug'] ?? null)
            && preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $publicacao['slug']) === 1;
    }

    /**
     * O CMS futuro consulta o banco com os mesmos critérios: aprovado e visível publicamente.
     *
     * @return list<array<string, mixed>>
     */
    private function publicados(string $chave): array
    {
        return collect(config("laccrim.exemplos.{$chave}", []))
            ->filter(fn (array $pessoa): bool => ($pessoa['aprovado'] ?? false) === true
                && ($pessoa['exibir_publicamente'] ?? false) === true)
            ->values()
            ->all();
    }
}
