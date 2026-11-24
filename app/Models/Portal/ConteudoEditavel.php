<?php

namespace App\Models\Portal;

use App\Enums\SituacaoConteudo;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Área editável do site. publicado e rascunho guardam apenas os campos
 * definidos em config/cms.php, indexados pelo nome do campo.
 *
 * @property array<string, string>|null $publicado
 * @property array<string, string>|null $rascunho
 */
#[Fillable([
    'chave',
    'situacao',
    'publicado',
    'rascunho',
    'rascunho_editado_por',
    'aprovacao_solicitada_por',
    'aprovacao_solicitada_em',
    'publicado_por',
    'publicado_em',
])]
class ConteudoEditavel extends Model
{
    protected $table = 'conteudos_editaveis';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'situacao' => SituacaoConteudo::class,
            'publicado' => 'array',
            'rascunho' => 'array',
            'aprovacao_solicitada_em' => 'datetime',
            'publicado_em' => 'datetime',
        ];
    }

    /**
     * @return HasMany<VersaoConteudo, $this>
     */
    public function versoes(): HasMany
    {
        return $this->hasMany(VersaoConteudo::class)->latest('publicado_em')->latest('id');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprovacao_solicitada_por');
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function publicador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'publicado_por');
    }
}
