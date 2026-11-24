<?php

namespace App\Repositories;

use App\Contracts\ConteudoEditavelRepository;
use App\Enums\SituacaoConteudo;
use App\Models\Portal\ConteudoEditavel;
use App\Models\Portal\VersaoConteudo;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EloquentConteudoEditavelRepository implements ConteudoEditavelRepository
{
    public function buscarPorChave(string $chave): ?ConteudoEditavel
    {
        return ConteudoEditavel::query()->where('chave', $chave)->first();
    }

    public function todosIndexadosPorChave(): array
    {
        return ConteudoEditavel::query()->with('publicador')->get()->keyBy('chave')->all();
    }

    public function publicados(): array
    {
        return ConteudoEditavel::query()
            ->whereNotNull('publicado')
            ->pluck('publicado', 'chave')
            ->map(fn (mixed $valores): array => is_array($valores) ? $valores : json_decode((string) $valores, true) ?? [])
            ->all();
    }

    public function salvarRascunho(string $chave, array $valores, User $editor, SituacaoConteudo $situacao): ConteudoEditavel
    {
        return ConteudoEditavel::query()->updateOrCreate(['chave' => $chave], [
            'situacao' => $situacao,
            'rascunho' => $valores,
            'rascunho_editado_por' => $editor->getKey(),
        ]);
    }

    public function marcarAguardandoAprovacao(ConteudoEditavel $conteudo, User $solicitante): void
    {
        $conteudo->update([
            'situacao' => SituacaoConteudo::AguardandoAprovacao,
            'aprovacao_solicitada_por' => $solicitante->getKey(),
            'aprovacao_solicitada_em' => now(),
        ]);
    }

    public function devolverParaRascunho(ConteudoEditavel $conteudo): void
    {
        $conteudo->update([
            'situacao' => SituacaoConteudo::Rascunho,
            'aprovacao_solicitada_por' => null,
            'aprovacao_solicitada_em' => null,
        ]);
    }

    public function publicarRascunho(ConteudoEditavel $conteudo, array $anterior, User $publicador, int $versoesMantidas): void
    {
        DB::transaction(function () use ($conteudo, $anterior, $publicador, $versoesMantidas): void {
            $conteudo->versoes()->create([
                'conteudo' => $anterior,
                'publicado_por' => $conteudo->publicado_por,
                'publicado_em' => $conteudo->publicado_em,
            ]);

            $conteudo->update([
                'situacao' => SituacaoConteudo::Publicado,
                'publicado' => $conteudo->rascunho,
                'rascunho' => null,
                'rascunho_editado_por' => null,
                'aprovacao_solicitada_por' => null,
                'aprovacao_solicitada_em' => null,
                'publicado_por' => $publicador->getKey(),
                'publicado_em' => now(),
            ]);

            $mantidas = $conteudo->versoes()->limit($versoesMantidas)->pluck('id');

            VersaoConteudo::query()
                ->where('conteudo_editavel_id', $conteudo->getKey())
                ->whereNotIn('id', $mantidas)
                ->delete();
        });
    }

    public function buscarVersao(ConteudoEditavel $conteudo, int $versaoId): ?VersaoConteudo
    {
        return $conteudo->versoes()->whereKey($versaoId)->first();
    }
}
