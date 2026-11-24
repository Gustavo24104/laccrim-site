<?php

namespace App\Models;

use App\Enums\PerfilAcesso;
use App\Enums\PermissaoPortal;
use App\Models\Portal\Perfil;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Fortify\TwoFactorAuthenticatable;

#[Fillable(['name', 'email', 'telefone', 'id_ufu', 'data_nascimento', 'password', 'deve_trocar_senha'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'data_nascimento' => 'date',
            'password' => 'hashed',
            'deve_trocar_senha' => 'boolean',
            'ausente' => 'boolean',
        ];
    }

    /**
     * @return BelongsToMany<Perfil, $this>
     */
    public function perfis(): BelongsToMany
    {
        return $this->belongsToMany(Perfil::class, 'perfil_usuario')->withTimestamps();
    }

    public function urlFotoPerfil(): ?string
    {
        return $this->foto_perfil ? Storage::disk('public')->url($this->foto_perfil) : null;
    }

    public function iniciais(): string
    {
        $partes = Str::of($this->name)->squish()->explode(' ');
        $ultima = $partes->count() > 1 ? Str::substr($partes->last(), 0, 1) : '';

        return Str::upper(Str::substr($partes->first(), 0, 1).$ultima);
    }

    public function possuiPerfil(PerfilAcesso $perfil): bool
    {
        return $this->loadMissing('perfis.permissoes')->perfis->contains('slug', $perfil->value);
    }

    public function possuiPermissao(PermissaoPortal $permissao): bool
    {
        return $this->loadMissing('perfis.permissoes')->perfis
            ->contains(fn (Perfil $perfil) => $perfil->permissoes->contains('slug', $permissao->value));
    }
}
