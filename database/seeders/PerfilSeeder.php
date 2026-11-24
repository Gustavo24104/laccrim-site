<?php

namespace Database\Seeders;

use App\Enums\PerfilAcesso;
use App\Enums\PermissaoPortal;
use App\Models\Portal\Perfil;
use App\Models\Portal\Permissao;
use Illuminate\Database\Seeder;

class PerfilSeeder extends Seeder
{
    /**
     * Cria (ou atualiza) os perfis padrão e suas permissões iniciais.
     */
    public function run(): void
    {
        $permissoes = collect(PermissaoPortal::cases())->mapWithKeys(fn (PermissaoPortal $permissao) => [
            $permissao->value => Permissao::query()->updateOrCreate(
                ['slug' => $permissao->value],
                ['nome' => $permissao->rotulo()],
            )->id,
        ]);

        foreach (PerfilAcesso::cases() as $perfilAcesso) {
            $perfil = Perfil::query()->updateOrCreate(
                ['slug' => $perfilAcesso->value],
                ['nome' => $perfilAcesso->rotulo()],
            );

            $perfil->permissoes()->sync(
                collect($this->permissoesPadrao($perfilAcesso))
                    ->map(fn (PermissaoPortal $permissao) => $permissoes[$permissao->value])
                    ->all()
            );
        }
    }

    /**
     * @return list<PermissaoPortal>
     */
    private function permissoesPadrao(PerfilAcesso $perfil): array
    {
        return match ($perfil) {
            PerfilAcesso::AdministradorSistema => [
                PermissaoPortal::AcessarPortal,
                PermissaoPortal::CriarUsuarios,
                PermissaoPortal::ConfigurarSistema,
            ],
            PerfilAcesso::Presidencia => [
                PermissaoPortal::AcessarPortal,
                PermissaoPortal::CriarUsuarios,
                PermissaoPortal::AlterarCargos,
                PermissaoPortal::EditarSite,
                PermissaoPortal::AprovarSite,
                PermissaoPortal::EnviarAvisos,
            ],
            PerfilAcesso::VicePresidencia => [
                PermissaoPortal::AcessarPortal,
                PermissaoPortal::EditarSite,
                PermissaoPortal::AprovarSite,
                PermissaoPortal::EnviarAvisos,
            ],
            PerfilAcesso::DiretoriaComunicacao => [
                PermissaoPortal::AcessarPortal,
                PermissaoPortal::EditarSite,
            ],
            PerfilAcesso::Visitante => [],
            default => [PermissaoPortal::AcessarPortal],
        };
    }
}
