<?php

namespace App\Contracts;

use App\Enums\PermissaoPortal;
use App\Models\Portal\Perfil;
use Illuminate\Database\Eloquent\Collection;

/**
 * Leitura dos perfis de acesso cadastrados no banco.
 */
interface PerfilRepository
{
    /**
     * @return Collection<int, Perfil>
     */
    public function todos(): Collection;

    public function buscarPorId(int $id): Perfil;

    /**
     * @return Collection<int, Perfil>
     */
    public function buscarPorSlugComPermissoes(string $slug): Collection;

    /**
     * @return Collection<int, Perfil>
     */
    public function comPermissao(PermissaoPortal $permissao): Collection;
}
