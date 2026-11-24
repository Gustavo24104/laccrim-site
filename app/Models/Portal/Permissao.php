<?php

namespace App\Models\Portal;

use Database\Factories\Portal\PermissaoFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['slug', 'nome'])]
class Permissao extends Model
{
    /** @use HasFactory<PermissaoFactory> */
    use HasFactory;

    protected $table = 'permissoes';

    /**
     * @return BelongsToMany<Perfil, $this>
     */
    public function perfis(): BelongsToMany
    {
        return $this->belongsToMany(Perfil::class, 'perfil_permissao');
    }
}
