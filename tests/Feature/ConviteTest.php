<?php

use App\Enums\PerfilAcesso;
use App\Models\Portal\Convite;
use App\Models\Portal\Perfil;
use App\Models\User;
use Database\Seeders\PerfilSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PerfilSeeder::class);
    $this->presidente = User::factory()->comPerfil(PerfilAcesso::Presidencia)->create();
    $this->perfilLigante = Perfil::query()->where('slug', PerfilAcesso::Ligante->value)->firstOrFail();
});

function registrarPorConvite(string $token, array $sobrescrever = []): array
{
    return [
        'name' => 'Ana Liga',
        'email' => 'ana@example.com',
        'password' => 'Senha#12345',
        'password_confirmation' => 'Senha#12345',
        'token' => hash('sha512', $token),
        ...$sobrescrever,
    ];
}

test('convidado se registra com o cargo definido no convite e acessa o portal', function () {
    $token = Convite::criaConvite($this->presidente, 'ana@example.com', $this->perfilLigante);

    $this->post(route('register'), registrarPorConvite($token))->assertSessionHasNoErrors();

    $usuario = User::query()->where('email', 'ana@example.com')->firstOrFail();

    expect($usuario->possuiPerfil(PerfilAcesso::Ligante))->toBeTrue()
        ->and(Convite::query()->first()->usado_em)->not->toBeNull();

    $this->actingAs($usuario)->get(route('portal.dashboard'))->assertOk();
});

test('convite não pode ser reutilizado', function () {
    $token = Convite::criaConvite($this->presidente, 'ana@example.com', $this->perfilLigante);
    $this->post(route('register'), registrarPorConvite($token));
    auth()->logout();

    $this->post(route('register'), registrarPorConvite($token, ['email' => 'outra@example.com']))
        ->assertSessionHasErrors('token');

    expect(User::query()->where('email', 'outra@example.com')->exists())->toBeFalse();
});

test('e-mail diferente do convidado é recusado', function () {
    $token = Convite::criaConvite($this->presidente, 'ana@example.com', $this->perfilLigante);

    $this->post(route('register'), registrarPorConvite($token, ['email' => 'intruso@example.com']))
        ->assertSessionHasErrors('email');

    expect(User::query()->where('email', 'intruso@example.com')->exists())->toBeFalse();
});
