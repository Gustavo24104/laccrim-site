<?php

use App\Enums\PerfilAcesso;
use App\Models\User;
use App\Notifications\AvisoGeralNotification;
use Database\Seeders\PerfilSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PerfilSeeder::class);
    Notification::fake();
});

test('a presidência envia um aviso geral a todos os membros do portal, menos a si mesma', function () {
    $presidente = User::factory()->comPerfil(PerfilAcesso::Presidencia)->create();
    $ligante = User::factory()->comPerfil(PerfilAcesso::Ligante)->create();
    $diretor = User::factory()->comPerfil(PerfilAcesso::DiretoriaPesquisa)->create();
    $visitante = User::factory()->comPerfil(PerfilAcesso::Visitante)->create();

    $this->actingAs($presidente)->get(route('portal.avisos.create'))->assertOk();

    $this->actingAs($presidente)
        ->post(route('portal.avisos.store'), ['titulo' => 'Reunião geral', 'mensagem' => 'Sexta, às 18h, na sala 3D.'])
        ->assertRedirect(route('portal.avisos.create'))
        ->assertSessionHas('status', 'Aviso enviado para 2 membros.');

    Notification::assertSentTo([$ligante, $diretor], AvisoGeralNotification::class, function (AvisoGeralNotification $aviso) use ($presidente) {
        return $aviso->titulo === 'Reunião geral' && $aviso->remetente === $presidente->name;
    });
    Notification::assertNotSentTo([$presidente, $visitante], AvisoGeralNotification::class);
});

test('quem não tem permissão não envia avisos', function (PerfilAcesso $perfil) {
    $usuario = User::factory()->comPerfil($perfil)->create();

    $this->actingAs($usuario)->get(route('portal.avisos.create'))->assertForbidden();
    $this->actingAs($usuario)
        ->post(route('portal.avisos.store'), ['titulo' => 'Aviso', 'mensagem' => 'Texto'])
        ->assertForbidden();

    Notification::assertNothingSent();
})->with([
    'ligante' => [PerfilAcesso::Ligante],
    'diretor de comunicação' => [PerfilAcesso::DiretoriaComunicacao],
    'administrador do sistema' => [PerfilAcesso::AdministradorSistema],
]);

test('aviso sem título ou mensagem é recusado', function () {
    $vice = User::factory()->comPerfil(PerfilAcesso::VicePresidencia)->create();

    $this->actingAs($vice)
        ->post(route('portal.avisos.store'), ['titulo' => '', 'mensagem' => ''])
        ->assertSessionHasErrors(['titulo', 'mensagem']);

    Notification::assertNothingSent();
});
