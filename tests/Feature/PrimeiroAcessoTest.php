<?php

use App\Enums\PerfilAcesso;
use App\Models\User;
use Database\Seeders\PerfilSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PerfilSeeder::class);
    $this->ligante = User::factory()->comPerfil(PerfilAcesso::Ligante)->create(['deve_trocar_senha' => true]);
});

test('com senha provisória, o portal leva à troca de senha', function () {
    $this->actingAs($this->ligante)
        ->get(route('portal.dashboard'))
        ->assertRedirect(route('portal.primeiro-acesso.edit'));

    $this->actingAs($this->ligante)->get(route('portal.primeiro-acesso.edit'))->assertOk();
});

test('definir uma senha válida libera o portal', function () {
    $this->actingAs($this->ligante)
        ->put(route('portal.primeiro-acesso.update'), [
            'password' => 'NovaSenha#123',
            'password_confirmation' => 'NovaSenha#123',
        ])
        ->assertRedirect(config('fortify.home'));

    $ligante = $this->ligante->fresh();

    expect($ligante->deve_trocar_senha)->toBeFalse()
        ->and(Hash::check('NovaSenha#123', $ligante->password))->toBeTrue();

    $this->actingAs($ligante)->get(route('portal.dashboard'))->assertOk();
});

test('senha fraca é recusada e a troca continua obrigatória', function () {
    $this->actingAs($this->ligante)
        ->from(route('portal.primeiro-acesso.edit'))
        ->put(route('portal.primeiro-acesso.update'), [
            'password' => 'senhafraca',
            'password_confirmation' => 'senhafraca',
        ])
        ->assertRedirect(route('portal.primeiro-acesso.edit'))
        ->assertSessionHasErrors('password');

    expect($this->ligante->fresh()->deve_trocar_senha)->toBeTrue();
});
