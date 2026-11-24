<?php

namespace App\Services\Portal;

use App\Contracts\UsuarioRepository;
use App\Models\User;

class TrocarSenhaService
{
    public function __construct(private UsuarioRepository $usuarios) {}

    public function handle(User $usuario, string $novaSenha): void
    {
        $this->usuarios->trocarSenha($usuario, $novaSenha);
    }
}
