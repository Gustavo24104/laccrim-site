<?php

use App\Enums\PerfilAcesso;
use App\Enums\PermissaoPortal;
use App\Models\User;
use Database\Seeders\PerfilSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PerfilSeeder::class);
});

test('visitante não autenticado é levado ao login ao tentar acessar o portal', function () {
    $this->get('/portal/dashboard')->assertRedirect(route('login'));
});

test('ligante acessa o portal', function () {
    $ligante = User::factory()->comPerfil(PerfilAcesso::Ligante)->create();

    $this->actingAs($ligante)->get('/portal/dashboard')->assertOk();
});

test('perfil sem permissão de portal é bloqueado', function (?PerfilAcesso $perfil) {
    $usuario = $perfil
        ? User::factory()->comPerfil($perfil)->create()
        : User::factory()->create();

    $this->actingAs($usuario)->get('/portal/dashboard')->assertForbidden();
})->with([
    'visitante' => [PerfilAcesso::Visitante],
    'sem perfil' => [null],
]);

test('cada perfil recebe apenas as permissões previstas', function (PerfilAcesso $perfil, array $permitidas) {
    $usuario = User::factory()->comPerfil($perfil)->create();

    foreach (PermissaoPortal::cases() as $permissao) {
        expect($usuario->can($permissao->value))
            ->toBe(in_array($permissao, $permitidas, true), "{$perfil->value} → {$permissao->value}");
    }
})->with([
    'administrador do sistema não edita nem aprova o site' => [PerfilAcesso::AdministradorSistema, [
        PermissaoPortal::AcessarPortal, PermissaoPortal::CriarUsuarios, PermissaoPortal::ConfigurarSistema,
    ]],
    'presidência cria contas, altera cargos, edita, aprova e envia avisos' => [PerfilAcesso::Presidencia, [
        PermissaoPortal::AcessarPortal, PermissaoPortal::CriarUsuarios, PermissaoPortal::AlterarCargos,
        PermissaoPortal::EditarSite, PermissaoPortal::AprovarSite, PermissaoPortal::EnviarAvisos,
    ]],
    'vice-presidência edita, aprova e envia avisos' => [PerfilAcesso::VicePresidencia, [
        PermissaoPortal::AcessarPortal, PermissaoPortal::EditarSite, PermissaoPortal::AprovarSite,
        PermissaoPortal::EnviarAvisos,
    ]],
    'diretor de comunicação só edita' => [PerfilAcesso::DiretoriaComunicacao, [
        PermissaoPortal::AcessarPortal, PermissaoPortal::EditarSite,
    ]],
    'ligante só acessa o portal' => [PerfilAcesso::Ligante, [PermissaoPortal::AcessarPortal]],
    'consulta só acessa o portal' => [PerfilAcesso::Consulta, [PermissaoPortal::AcessarPortal]],
]);

test('usuário com mais de um perfil soma as permissões', function () {
    $usuario = User::factory()
        ->comPerfil(PerfilAcesso::Ligante)
        ->comPerfil(PerfilAcesso::DiretoriaComunicacao)
        ->create();

    expect($usuario->can(PermissaoPortal::EditarSite->value))->toBeTrue()
        ->and($usuario->can(PermissaoPortal::AprovarSite->value))->toBeFalse();
});
