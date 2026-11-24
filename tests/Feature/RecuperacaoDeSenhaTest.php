<?php

use App\Enums\PerfilAcesso;
use App\Http\Responses\RespostaLinkRedefinicaoSenha;
use App\Models\User;
use Database\Seeders\PerfilSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PerfilSeeder::class);
    Notification::fake();
});

test('o login oferece o link de esqueci a senha', function () {
    $this->get(route('login'))
        ->assertOk()
        ->assertSee('href="'.route('password.request').'"', false)
        ->assertSee('Esqueci a senha');

    $this->get(route('password.request'))->assertOk();
});

test('e-mail cadastrado recebe o link de redefinição em português', function () {
    $usuario = User::factory()->create(['email' => 'ana@example.com']);

    $this->from(route('password.request'))
        ->post(route('password.email'), ['email' => 'ana@example.com'])
        ->assertRedirect(route('password.request'))
        ->assertSessionHas('status', RespostaLinkRedefinicaoSenha::MENSAGEM);

    Notification::assertSentTo($usuario, ResetPassword::class, function (ResetPassword $notificacao) use ($usuario) {
        return str_contains($notificacao->toMail($usuario)->subject, 'Redefinição de senha');
    });
});

test('e-mail sem conta recebe a mesma resposta e nada é enviado', function () {
    $this->from(route('password.request'))
        ->post(route('password.email'), ['email' => 'ninguem@example.com'])
        ->assertRedirect(route('password.request'))
        ->assertSessionHasNoErrors()
        ->assertSessionHas('status', RespostaLinkRedefinicaoSenha::MENSAGEM);

    Notification::assertNothingSent();
});

test('o link do e-mail permite definir e confirmar a nova senha', function () {
    $usuario = User::factory()->create(['email' => 'ana@example.com', 'deve_trocar_senha' => true]);
    $token = Password::createToken($usuario);

    $this->get(route('password.reset', ['token' => $token, 'email' => 'ana@example.com']))
        ->assertOk()
        ->assertSee('ana@example.com');

    $this->post(route('password.update'), [
        'token' => $token,
        'email' => 'ana@example.com',
        'password' => 'NovaSenha#123',
        'password_confirmation' => 'NovaSenha#123',
    ])->assertRedirect(route('login'));

    $usuario->refresh();

    expect(Hash::check('NovaSenha#123', $usuario->password))->toBeTrue()
        ->and($usuario->deve_trocar_senha)->toBeFalse();
});

test('o usuário altera a senha nas configurações informando a senha atual', function () {
    $usuario = User::factory()->comPerfil(PerfilAcesso::Ligante)->create();

    $this->actingAs($usuario)->get(route('portal.configuracoes'))->assertOk();

    $this->actingAs($usuario)
        ->from(route('portal.configuracoes'))
        ->put(route('user-password.update'), [
            'current_password' => 'password',
            'password' => 'NovaSenha#123',
            'password_confirmation' => 'NovaSenha#123',
        ])
        ->assertRedirect(route('portal.configuracoes'))
        ->assertSessionHasNoErrors();

    expect(Hash::check('NovaSenha#123', $usuario->fresh()->password))->toBeTrue();
});

test('senha atual incorreta impede a alteração', function () {
    $usuario = User::factory()->comPerfil(PerfilAcesso::Ligante)->create();

    $this->actingAs($usuario)
        ->from(route('portal.configuracoes'))
        ->put(route('user-password.update'), [
            'current_password' => 'errada',
            'password' => 'NovaSenha#123',
            'password_confirmation' => 'NovaSenha#123',
        ])
        ->assertSessionHasErrorsIn('updatePassword', 'current_password');

    expect(Hash::check('password', $usuario->fresh()->password))->toBeTrue();
});
