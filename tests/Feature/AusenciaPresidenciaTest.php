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

test('a presidência marca ausência e a vice-presidência é avisada', function () {
    $presidente = User::factory()->comPerfil(PerfilAcesso::Presidencia)->create();
    $vice = User::factory()->comPerfil(PerfilAcesso::VicePresidencia)->create();
    $ligante = User::factory()->comPerfil(PerfilAcesso::Ligante)->create();

    $this->actingAs($presidente)->get(route('portal.configuracoes'))->assertSee('Estou ausente');

    $this->actingAs($presidente)
        ->put(route('portal.configuracoes.ausencia'), ['ausente' => true])
        ->assertRedirect(route('portal.configuracoes'));

    expect($presidente->fresh()->ausente)->toBeTrue();
    Notification::assertSentTo($vice, AvisoGeralNotification::class, fn (AvisoGeralNotification $aviso) => $aviso->titulo === 'Presidência ausente');
    Notification::assertNotSentTo($ligante, AvisoGeralNotification::class);

    $this->actingAs($presidente->fresh())->put(route('portal.configuracoes.ausencia'), ['ausente' => false]);

    expect($presidente->fresh()->ausente)->toBeFalse();
});

test('só a presidência marca ausência', function () {
    $vice = User::factory()->comPerfil(PerfilAcesso::VicePresidencia)->create();

    $this->actingAs($vice)->get(route('portal.configuracoes'))->assertDontSee('Estou ausente');
    $this->actingAs($vice)->put(route('portal.configuracoes.ausencia'), ['ausente' => true])->assertForbidden();

    expect($vice->fresh()->ausente)->toBeFalse();
});
