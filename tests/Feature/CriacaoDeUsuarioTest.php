<?php

use App\Enums\PerfilAcesso;
use App\Models\Portal\Perfil;
use App\Models\User;
use App\Notifications\SenhaInicialNotification;
use Database\Seeders\PerfilSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PerfilSeeder::class);
    Notification::fake();
});

function dadosNovoUsuario(array $sobrescrever = []): array
{
    return [
        'name' => 'Ana Liga',
        'email' => 'ana@example.com',
        'id_ufu' => '12345678',
        'data_nascimento' => '2000-01-01',
        'perfil_id' => Perfil::query()->where('slug', PerfilAcesso::Ligante->value)->value('id'),
        ...$sobrescrever,
    ];
}

test('o cadastro público não existe mais', function () {
    $this->get('/portal/criar-conta')->assertNotFound();
    $this->get(route('login'))->assertOk()->assertDontSee('Criar conta');
});

test('quem pode criar contas cria o usuário com cargo, senha aleatória por e-mail e troca obrigatória', function (PerfilAcesso $criador) {
    $autor = User::factory()->comPerfil($criador)->create();

    $this->actingAs($autor)->get(route('portal.usuarios.create'))->assertOk()->assertSee('Cargo');

    $this->actingAs($autor)
        ->post(route('portal.usuarios.store'), dadosNovoUsuario())
        ->assertRedirect(route('portal.usuarios.create'))
        ->assertSessionHasNoErrors();

    $usuario = User::query()->where('email', 'ana@example.com')->firstOrFail();

    expect($usuario->deve_trocar_senha)->toBeTrue()
        ->and($usuario->id_ufu)->toBe('12345678')
        ->and($usuario->possuiPerfil(PerfilAcesso::Ligante))->toBeTrue();

    Notification::assertSentTo($usuario, SenhaInicialNotification::class, function (SenhaInicialNotification $notificacao) use ($usuario) {
        return strlen($notificacao->senhaInicial) === 12
            && ctype_alnum($notificacao->senhaInicial)
            && Hash::check($notificacao->senhaInicial, $usuario->password);
    });
})->with([
    'administrador do sistema' => [PerfilAcesso::AdministradorSistema],
    'presidência' => [PerfilAcesso::Presidencia],
]);

test('perfis sem permissão não criam contas', function (PerfilAcesso $perfil) {
    $autor = User::factory()->comPerfil($perfil)->create();

    $this->actingAs($autor)->get(route('portal.usuarios.create'))->assertForbidden();
    $this->actingAs($autor)->post(route('portal.usuarios.store'), dadosNovoUsuario())->assertForbidden();

    expect(User::query()->where('email', 'ana@example.com')->exists())->toBeFalse();
    Notification::assertNothingSent();
})->with([
    'vice-presidência' => [PerfilAcesso::VicePresidencia],
    'diretor de comunicação' => [PerfilAcesso::DiretoriaComunicacao],
    'ligante' => [PerfilAcesso::Ligante],
]);

test('e-mail já cadastrado é recusado', function () {
    $autor = User::factory()->comPerfil(PerfilAcesso::Presidencia)->create();
    User::factory()->create(['email' => 'ana@example.com']);

    $this->actingAs($autor)
        ->post(route('portal.usuarios.store'), dadosNovoUsuario())
        ->assertSessionHasErrors('email');

    Notification::assertNothingSent();
});

test('presidência altera o cargo de outro usuário', function () {
    $presidente = User::factory()->comPerfil(PerfilAcesso::Presidencia)->create();
    $ligante = User::factory()->comPerfil(PerfilAcesso::Ligante)->create();
    $diretoria = Perfil::query()->where('slug', PerfilAcesso::DiretoriaPesquisa->value)->firstOrFail();

    $this->actingAs($presidente)->get(route('portal.usuarios.index'))->assertOk()->assertSee($ligante->name);

    $this->actingAs($presidente)
        ->put(route('portal.usuarios.cargo.update', $ligante), ['perfil_id' => $diretoria->id])
        ->assertRedirect(route('portal.usuarios.index'));

    expect($ligante->fresh()->perfis->pluck('slug')->all())->toBe([PerfilAcesso::DiretoriaPesquisa->value]);
});

test('administrador do sistema não altera cargos', function () {
    $administrador = User::factory()->comPerfil(PerfilAcesso::AdministradorSistema)->create();
    $ligante = User::factory()->comPerfil(PerfilAcesso::Ligante)->create();
    $presidencia = Perfil::query()->where('slug', PerfilAcesso::Presidencia->value)->firstOrFail();

    $this->actingAs($administrador)
        ->put(route('portal.usuarios.cargo.update', $ligante), ['perfil_id' => $presidencia->id])
        ->assertForbidden();

    expect($ligante->fresh()->possuiPerfil(PerfilAcesso::Ligante))->toBeTrue();
});

test('presidência não altera o próprio cargo', function () {
    $presidente = User::factory()->comPerfil(PerfilAcesso::Presidencia)->create();
    $ligante = Perfil::query()->where('slug', PerfilAcesso::Ligante->value)->firstOrFail();

    $this->actingAs($presidente)
        ->put(route('portal.usuarios.cargo.update', $presidente), ['perfil_id' => $ligante->id])
        ->assertSessionHasErrors('perfil_id');

    expect($presidente->fresh()->possuiPerfil(PerfilAcesso::Presidencia))->toBeTrue();
});
