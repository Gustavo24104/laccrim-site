<?php

namespace App\Repositories;

use App\Contracts\PerfilRepository;
use App\Enums\PermissaoPortal;
use App\Models\Portal\Perfil;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class EloquentPerfilRepository implements PerfilRepository
{
    public function todos(): Collection
    {
        return Perfil::query()->orderBy('id')->get();
    }

    public function buscarPorId(int $id): Perfil
    {
        return Perfil::query()->findOrFail($id);
    }

    public function buscarPorSlugComPermissoes(string $slug): Collection
    {
        return Perfil::query()->with('permissoes')->where('slug', $slug)->get();
    }

    public function comPermissao(PermissaoPortal $permissao): Collection
    {
        return Perfil::query()
            ->whereHas('permissoes', fn (Builder $consulta) => $consulta->where('slug', $permissao->value))
            ->orderBy('id')
            ->get();
    }
}
