<?php

namespace App\Models\Portal;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Versão publicada anteriormente de uma área editável, mantida para restauração.
 *
 * @property array<string, string> $conteudo
 */
#[Fillable(['conteudo_editavel_id', 'conteudo', 'publicado_por', 'publicado_em'])]
class VersaoConteudo extends Model
{
    protected $table = 'versoes_conteudo';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'conteudo' => 'array',
            'publicado_em' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<ConteudoEditavel, $this>
     */
    public function conteudoEditavel(): BelongsTo
    {
        return $this->belongsTo(ConteudoEditavel::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function publicador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'publicado_por');
    }
}
