<?php

namespace App\Models\Portal;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Str;


class Convite extends Model
{
    protected $fillable = ['email', 'token', 'usado_em', 'criado_por', 'expira_em'];
    

    // cria o convite no banco de dados e retorna o token
    public static function criaConvite(User $criador, string $email) {
        $token = Str::random(32);
        Convite::create([
            'email' => $email,
            'token' => hash('sha512', $token),
            'criado_por' => $criador->name,
            'expira_em' => now()->addHours(42),
        ]);

        return $token;
    }
}
