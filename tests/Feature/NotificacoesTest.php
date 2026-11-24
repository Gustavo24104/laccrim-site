<?php

use App\Enums\PerfilAcesso;
use App\Enums\TipoNotificacao;
use App\Models\User;
use App\Notifications\AvisoGeralNotification;
use Database\Seeders\PerfilSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PerfilSeeder::class);
    $this->ligante = User::factory()->comPerfil(PerfilAcesso::Ligante)->create();
});

test('o sino mostra as notificações e a contagem de não lidas', function () {
    $this->ligante->notify(new AvisoGeralNotification('Reunião geral', 'Sexta, às 18h.', 'Presidência'));

    $this->actingAs($this->ligante)
        ->get(route('portal.dashboard'))
        ->assertOk()
        ->assertSee('Notificações (1 não lidas)')
        ->assertSeeInOrder(['Aviso', 'agora mesmo', 'Reunião geral', 'Sexta, às 18h.']);
});

test('o tempo da notificação é relativo até 4 semanas e depois vira data', function (string $intervalo, string $esperado) {
    $this->travelTo(Carbon::parse('2026-01-10 12:00:00', 'UTC'));
    $this->ligante->notify(new AvisoGeralNotification('Aviso antigo', 'Texto', 'Presidência'));

    $this->travelTo(Carbon::parse('2026-01-10 12:00:00', 'UTC')->add($intervalo));

    $this->actingAs($this->ligante)->get(route('portal.dashboard'))->assertSee($esperado);
})->with([
    '30 minutos' => ['30 minutes', 'há 30 minutos'],
    '1 hora' => ['1 hour', 'há 1 hora'],
    '1 dia' => ['1 day', 'há 1 dia'],
    '3 dias' => ['3 days', 'há 3 dias'],
    '1 semana' => ['1 week', 'há 1 semana'],
    '4 semanas' => ['4 weeks', 'há 4 semanas'],
    'depois de 4 semanas' => ['5 weeks', '10/01/2026'],
]);

test('notificações de prazo aparecem com o selo do tipo', function (TipoNotificacao $tipo) {
    $this->ligante->notifications()->create([
        'id' => (string) Str::uuid(),
        'type' => 'tarefa',
        'data' => ['tipo' => $tipo->value, 'titulo' => 'Relatório semestral', 'mensagem' => 'Entrega do relatório.', 'url' => null],
    ]);

    $this->actingAs($this->ligante)->get(route('portal.dashboard'))->assertSeeInOrder([$tipo->rotulo(), 'Relatório semestral']);
})->with([
    'nova tarefa' => [TipoNotificacao::TarefaAtribuida],
    'prazo próximo' => [TipoNotificacao::PrazoProximo],
    'prazo vencido' => [TipoNotificacao::PrazoVencido],
]);

test('abrir uma notificação a marca como lida', function () {
    $this->ligante->notify(new AvisoGeralNotification('Reunião geral', 'Texto', 'Presidência'));
    $notificacao = $this->ligante->notifications()->first();

    $this->actingAs($this->ligante)
        ->from(route('portal.dashboard'))
        ->post(route('portal.notificacoes.lida', $notificacao->id))
        ->assertRedirect(route('portal.dashboard'));

    expect($notificacao->fresh()->read_at)->not->toBeNull();

    $this->actingAs($this->ligante)->get(route('portal.dashboard'))->assertDontSee('não lidas)');
});

test('marcar todas como lidas zera a contagem', function () {
    $this->ligante->notify(new AvisoGeralNotification('Primeiro', 'Texto', 'Presidência'));
    $this->ligante->notify(new AvisoGeralNotification('Segundo', 'Texto', 'Presidência'));

    $this->actingAs($this->ligante)
        ->from(route('portal.dashboard'))
        ->post(route('portal.notificacoes.lidas'))
        ->assertRedirect(route('portal.dashboard'));

    expect($this->ligante->unreadNotifications()->count())->toBe(0);
});

test('ninguém marca a notificação de outro usuário', function () {
    $outro = User::factory()->comPerfil(PerfilAcesso::Ligante)->create();
    $outro->notify(new AvisoGeralNotification('Particular', 'Texto', 'Presidência'));
    $notificacao = $outro->notifications()->first();

    $this->actingAs($this->ligante)
        ->post(route('portal.notificacoes.lida', $notificacao->id))
        ->assertNotFound();

    expect($notificacao->fresh()->read_at)->toBeNull();
});
