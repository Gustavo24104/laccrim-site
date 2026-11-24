<?php

use App\Enums\PerfilAcesso;
use App\Models\User;
use Database\Seeders\PerfilSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PerfilSeeder::class);
});

test('o botão Portal da Liga do site abre em nova aba', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('href="'.route('login').'"', false)
        ->assertSee('target="_blank" rel="noopener">Portal da Liga', false);
});

test('a página inicial do portal mostra nome e ID UFU do usuário', function () {
    $ligante = User::factory()->comPerfil(PerfilAcesso::Ligante)->create([
        'name' => 'Ana Beatriz Liga',
        'id_ufu' => '11921DIR001',
    ]);

    $this->actingAs($ligante)
        ->get(route('portal.dashboard'))
        ->assertOk()
        ->assertSeeInOrder(['Ana Beatriz Liga', 'ID UFU: 11921DIR001'])
        ->assertSee('AL');
});

test('o menu lateral mostra só o que o perfil pode acessar', function (PerfilAcesso $perfil, array $visiveis, array $ocultas) {
    $usuario = User::factory()->comPerfil($perfil)->create();

    $resposta = $this->actingAs($usuario)->get(route('portal.dashboard'))->assertOk();

    foreach ($visiveis as $rota) {
        $resposta->assertSee('href="'.route($rota).'"', false);
    }

    foreach ($ocultas as $rota) {
        $resposta->assertDontSee('href="'.route($rota).'"', false);
    }
})->with([
    'ligante' => [PerfilAcesso::Ligante, ['portal.dashboard', 'portal.configuracoes'], ['portal.usuarios.index', 'portal.usuarios.create']],
    'administrador do sistema' => [PerfilAcesso::AdministradorSistema, ['portal.usuarios.create'], ['portal.usuarios.index']],
    'presidência' => [PerfilAcesso::Presidencia, ['portal.usuarios.index', 'portal.usuarios.create'], []],
]);
