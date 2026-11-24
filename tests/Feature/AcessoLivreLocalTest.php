<?php

use App\Enums\PerfilAcesso;
use App\Enums\PermissaoPortal;
use Database\Seeders\PerfilSeeder;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PerfilSeeder::class);
    $this->app['env'] = 'local';
    config(['portal.acesso_livre_local.habilitado' => true]);
    $this->withoutMiddleware(PreventRequestForgery::class);
});

test('em localhost com a chave ligada, entra no portal sem login e com todas as permissões', function () {
    $this->get(route('portal.dashboard'))
        ->assertOk()
        ->assertSee('Visualizar como (localhost)');

    $this->assertAuthenticated();

    foreach (PermissaoPortal::cases() as $permissao) {
        expect(auth()->user()->can($permissao->value))->toBeTrue();
    }

    $this->get(route('portal.usuarios.create'))->assertOk();
});

test('o acesso livre fica desligado se faltar qualquer condição', function (bool $chaveLigada, string $ambiente, string $ip) {
    config(['portal.acesso_livre_local.habilitado' => $chaveLigada]);
    $this->app['env'] = $ambiente;

    $this->withServerVariables(['REMOTE_ADDR' => $ip])
        ->get(route('portal.dashboard'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
})->with([
    'chave desligada' => [false, 'local', '127.0.0.1'],
    'ambiente de produção' => [true, 'production', '127.0.0.1'],
    'requisição de outra máquina' => [true, 'local', '10.0.0.5'],
]);

test('visualizar como um cargo restringe às permissões dele e pode ser desfeito', function () {
    $this->get(route('portal.dashboard'));

    $this->from(route('portal.dashboard'))
        ->post(route('portal.visualizar-como'), ['perfil' => PerfilAcesso::Ligante->value])
        ->assertRedirect(route('portal.dashboard'));

    $this->get(route('portal.dashboard'))->assertOk();
    $this->get(route('portal.usuarios.create'))->assertForbidden();

    $this->post(route('portal.visualizar-como'), ['perfil' => '']);

    $this->get(route('portal.usuarios.create'))->assertOk();
});

test('não é possível simular um cargo sem acesso ao portal', function () {
    $this->get(route('portal.dashboard'));

    $this->post(route('portal.visualizar-como'), ['perfil' => PerfilAcesso::Visitante->value])
        ->assertSessionHasErrors('perfil');

    $this->get(route('portal.usuarios.create'))->assertOk();
});

test('fora do acesso livre, o seletor não existe', function () {
    config(['portal.acesso_livre_local.habilitado' => false]);

    $this->post(route('portal.visualizar-como'), ['perfil' => PerfilAcesso::Ligante->value])->assertNotFound();
});
