<?php

namespace App\Repositories;

use App\Contracts\UsuarioRepository;
use App\Enums\PerfilAcesso;
use App\Enums\PermissaoPortal;
use App\Models\Portal\Perfil;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EloquentUsuarioRepository implements UsuarioRepository
{
    public function criarComPerfil(array $dados, Perfil $perfil): User
    {
        return DB::transaction(function () use ($dados, $perfil): User {
            $usuario = User::query()->create($dados);
            $usuario->perfis()->attach($perfil);

            return $usuario;
        });
    }

    public function definirPerfil(User $usuario, Perfil $perfil): void
    {
        $usuario->perfis()->sync([$perfil->id]);
        $usuario->unsetRelation('perfis');
    }

    public function trocarSenha(User $usuario, string $novaSenha): void
    {
        $usuario->forceFill([
            'password' => $novaSenha,
            'deve_trocar_senha' => false,
        ])->save();
    }

    public function definirFotoPerfil(User $usuario, string $caminho): void
    {
        $usuario->forceFill(['foto_perfil' => $caminho])->save();
    }

    public function encontrarOuCriarUsuarioDeTestes(string $email, string $nome): User
    {
        return User::query()->firstOrCreate(['email' => $email], [
            'name' => $nome,
            'password' => Str::password(32),
            'deve_trocar_senha' => false,
        ]);
    }

    public function listarComPerfis(): Collection
    {
        return User::query()->with('perfis')->orderBy('name')->get();
    }

    public function comPermissaoExceto(PermissaoPortal $permissao, User $excluido): Collection
    {
        return User::query()
            ->whereKeyNot($excluido->getKey())
            ->whereHas('perfis.permissoes', fn (Builder $consulta) => $consulta->where('slug', $permissao->value))
            ->get();
    }

    public function definirAusencia(User $usuario, bool $ausente): void
    {
        $usuario->forceFill(['ausente' => $ausente])->save();
    }

    public function existePresidenciaPresente(): bool
    {
        return User::query()
            ->where('ausente', false)
            ->whereHas('perfis', fn (Builder $consulta) => $consulta->where('slug', PerfilAcesso::Presidencia->value))
            ->exists();
    }

    public function urlsFotoPerfil(array $ids): array
    {
        if ($ids === []) {
            return [];
        }

        return User::query()
            ->whereKey($ids)
            ->whereNotNull('foto_perfil')
            ->get()
            ->mapWithKeys(fn (User $usuario): array => [$usuario->getKey() => $usuario->urlFotoPerfil()])
            ->all();
    }
}
