<?php

namespace App\Models\Portal;

use App\Models\User;
use Database\Factories\Portal\PerfilFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['slug', 'nome', 'descricao'])]
class Perfil extends Model
{
    /** @use HasFactory<PerfilFactory> */
    use HasFactory;

    protected $table = 'perfis';

    /**
     * @return BelongsToMany<Permissao, $this>
     */
    public function permissoes(): BelongsToMany
    {
        return $this->belongsToMany(Permissao::class, 'perfil_permissao');
    }

    /**
     * @return BelongsToMany<User, $this>
     */
    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'perfil_usuario')->withTimestamps();
    }
}
