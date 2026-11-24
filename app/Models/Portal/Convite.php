<?php

namespace App\Models\Portal;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Convite extends Model
{
    protected $fillable = ['email', 'perfil_id', 'token', 'usado_em', 'criado_por', 'expira_em'];

    // cria o convite no banco de dados e retorna o token
    public static function criaConvite(User $criador, string $email, Perfil $perfil): string
    {
        $token = Str::random(32);
        Convite::create([
            'email' => $email,
            'perfil_id' => $perfil->id,
            'token' => hash('sha512', $token),
            'criado_por' => $criador->name,
            'expira_em' => now()->addHours(42),
        ]);

        return $token;
    }

    /**
     * @return BelongsTo<Perfil, $this>
     */
    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class);
    }
}
