<?php

namespace App\Services\Portal;

use App\Contracts\PerfilRepository;
use App\Contracts\UsuarioRepository;
use App\Models\User;

class AlterarCargoService
{
    public function __construct(
        private UsuarioRepository $usuarios,
        private PerfilRepository $perfis,
    ) {}

    public function handle(User $usuario, int $perfilId): void
    {
        $this->usuarios->definirPerfil($usuario, $this->perfis->buscarPorId($perfilId));
    }
}
