<?php

namespace App\Contracts;

use App\Enums\PermissaoPortal;
use App\Models\Portal\Perfil;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * Persistência das contas do Portal da Liga.
 */
interface UsuarioRepository
{
    /**
     * @param  array{name: string, email: string, password: string, id_ufu?: string, data_nascimento?: string, deve_trocar_senha?: bool}  $dados
     */
    public function criarComPerfil(array $dados, Perfil $perfil): User;

    public function definirPerfil(User $usuario, Perfil $perfil): void;

    public function trocarSenha(User $usuario, string $novaSenha): void;

    public function definirFotoPerfil(User $usuario, string $caminho): void;

    public function encontrarOuCriarUsuarioDeTestes(string $email, string $nome): User;

    /**
     * @return Collection<int, User>
     */
    public function listarComPerfis(): Collection;

    /**
     * @return Collection<int, User>
     */
    public function comPermissaoExceto(PermissaoPortal $permissao, User $excluido): Collection;

    public function definirAusencia(User $usuario, bool $ausente): void;

    /**
     * Há ao menos uma conta da Presidência que não marcou ausência.
     */
    public function existePresidenciaPresente(): bool;

    /**
     * @param  list<int>  $ids
     * @return array<int, string> URL pública da foto de perfil, indexada pelo id do usuário.
     */
    public function urlsFotoPerfil(array $ids): array;
}
