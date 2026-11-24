<?php

namespace App\Contracts;

use App\Enums\SituacaoConteudo;
use App\Models\Portal\ConteudoEditavel;
use App\Models\Portal\VersaoConteudo;
use App\Models\User;

/**
 * Persistência das áreas editáveis do site (rascunho, publicação e versões).
 */
interface ConteudoEditavelRepository
{
    public function buscarPorChave(string $chave): ?ConteudoEditavel;

    /**
     * @return array<string, ConteudoEditavel>
     */
    public function todosIndexadosPorChave(): array;

    /**
     * Valores publicados de todas as áreas, indexados pela chave da área.
     *
     * @return array<string, array<string, string>>
     */
    public function publicados(): array;

    /**
     * Grava o rascunho sem alterar uma solicitação de aprovação já feita.
     *
     * @param  array<string, string>  $valores
     */
    public function salvarRascunho(string $chave, array $valores, User $editor, SituacaoConteudo $situacao): ConteudoEditavel;

    public function marcarAguardandoAprovacao(ConteudoEditavel $conteudo, User $solicitante): void;

    public function devolverParaRascunho(ConteudoEditavel $conteudo): void;

    /**
     * Publica o rascunho e guarda o conteúdo anterior como versão, mantendo só as mais recentes.
     *
     * @param  array<string, string>  $anterior
     */
    public function publicarRascunho(ConteudoEditavel $conteudo, array $anterior, User $publicador, int $versoesMantidas): void;

    public function buscarVersao(ConteudoEditavel $conteudo, int $versaoId): ?VersaoConteudo;
}
