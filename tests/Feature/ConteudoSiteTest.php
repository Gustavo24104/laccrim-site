<?php

use App\Enums\PerfilAcesso;
use App\Enums\SituacaoConteudo;
use App\Models\Portal\ConteudoEditavel;
use App\Models\User;
use App\Notifications\ConteudoAguardandoAprovacaoNotification;
use App\Notifications\ConteudoRevisadoNotification;
use Database\Seeders\PerfilSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PerfilSeeder::class);
    Notification::fake();

    $this->diretor = User::factory()->comPerfil(PerfilAcesso::DiretoriaComunicacao)->create();
    $this->presidente = User::factory()->comPerfil(PerfilAcesso::Presidencia)->create();
    $this->vice = User::factory()->comPerfil(PerfilAcesso::VicePresidencia)->create();
});

/**
 * @return array{acao: string, campos: array{titulo: string, texto: string}}
 */
function envioDoBanner(string $titulo, string $acao = 'rascunho'): array
{
    return ['acao' => $acao, 'campos' => ['titulo' => $titulo, 'texto' => 'Texto revisado do banner.']];
}

test('o rascunho salvo não muda o site, mas aparece na pré-visualização de quem edita', function () {
    $this->actingAs($this->diretor)
        ->put(route('portal.conteudo.update', 'sobre-banner'), envioDoBanner('Título em revisão'))
        ->assertRedirect(route('portal.conteudo.edit', 'sobre-banner'))
        ->assertSessionHasNoErrors();

    $ligante = User::factory()->comPerfil(PerfilAcesso::Ligante)->create();

    $this->actingAs($this->diretor)->get(route('sobre'))->assertSee('Sobre a LACCRIM')->assertDontSee('Título em revisão');
    $this->actingAs($ligante)->get(route('sobre', ['previa' => 'sobre-banner']))
        ->assertDontSee('Título em revisão')
        ->assertDontSee('Pré-visualização do rascunho');

    $this->actingAs($this->diretor)
        ->get(route('sobre', ['previa' => 'sobre-banner']))
        ->assertSee('Título em revisão')
        ->assertSee('Pré-visualização do rascunho')
        ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
});

test('o diretor de comunicação solicita aprovação e só a presidência presente é notificada', function () {
    $this->actingAs($this->diretor)
        ->put(route('portal.conteudo.update', 'sobre-banner'), envioDoBanner('Novo título', 'solicitar'))
        ->assertSessionHas('status', 'Aprovação solicitada. 1 aprovador foi notificado.');

    expect(ConteudoEditavel::query()->where('chave', 'sobre-banner')->value('situacao'))
        ->toBe(SituacaoConteudo::AguardandoAprovacao);

    Notification::assertSentTo($this->presidente, ConteudoAguardandoAprovacaoNotification::class);
    Notification::assertNotSentTo([$this->vice, $this->diretor], ConteudoAguardandoAprovacaoNotification::class);
    $this->get(route('sobre'))->assertDontSee('Novo título');
});

test('a presidência aprova a solicitação, o site muda e o solicitante é avisado', function () {
    $this->actingAs($this->diretor)->put(route('portal.conteudo.update', 'sobre-banner'), envioDoBanner('Novo título', 'solicitar'));

    $this->actingAs($this->presidente)
        ->put(route('portal.conteudo.update', 'sobre-banner'), envioDoBanner('Novo título', 'publicar'))
        ->assertSessionHas('status', 'Alteração publicada no site.');

    $this->get(route('sobre'))->assertSee('Novo título')->assertSee('Texto revisado do banner.');

    Notification::assertSentTo($this->diretor, ConteudoRevisadoNotification::class, fn (ConteudoRevisadoNotification $aviso) => $aviso->aprovado);
});

test('quem só edita não publica diretamente', function () {
    $this->actingAs($this->diretor)
        ->put(route('portal.conteudo.update', 'sobre-banner'), envioDoBanner('Publicação indevida', 'publicar'))
        ->assertForbidden();

    $this->get(route('sobre'))->assertDontSee('Publicação indevida');
});

test('a vice-presidência só aprova quando não há presidência presente', function () {
    $this->actingAs($this->vice)
        ->put(route('portal.conteudo.update', 'sobre-banner'), envioDoBanner('Aprovado pela vice', 'publicar'))
        ->assertForbidden();

    $this->presidente->forceFill(['ausente' => true])->save();

    $this->actingAs($this->vice)
        ->put(route('portal.conteudo.update', 'sobre-banner'), envioDoBanner('Aprovado pela vice', 'publicar'))
        ->assertSessionHasNoErrors();

    $this->get(route('sobre'))->assertSee('Aprovado pela vice');
});

test('com a presidência ausente, a solicitação notifica a vice-presidência', function () {
    $this->presidente->forceFill(['ausente' => true])->save();

    $this->actingAs($this->diretor)->put(route('portal.conteudo.update', 'sobre-banner'), envioDoBanner('Novo título', 'solicitar'));

    Notification::assertSentTo([$this->vice, $this->presidente], ConteudoAguardandoAprovacaoNotification::class);
});

test('a vice-presidência aprova quando o cargo de presidência está vago', function () {
    $this->presidente->perfis()->detach();

    $this->actingAs($this->vice)
        ->put(route('portal.conteudo.update', 'sobre-banner'), envioDoBanner('Aprovado pela vice', 'publicar'))
        ->assertSessionHasNoErrors();

    $this->get(route('sobre'))->assertSee('Aprovado pela vice');
});

test('a presidência devolve a alteração com motivo e o solicitante é avisado', function () {
    $this->actingAs($this->diretor)->put(route('portal.conteudo.update', 'sobre-banner'), envioDoBanner('Novo título', 'solicitar'));

    $this->actingAs($this->diretor)
        ->post(route('portal.conteudo.devolver', 'sobre-banner'), ['motivo' => 'Revise o texto.'])
        ->assertForbidden();

    $this->actingAs($this->presidente)
        ->post(route('portal.conteudo.devolver', 'sobre-banner'), ['motivo' => 'Revise o texto.'])
        ->assertRedirect(route('portal.conteudo.edit', 'sobre-banner'));

    expect(ConteudoEditavel::query()->where('chave', 'sobre-banner')->value('situacao'))->toBe(SituacaoConteudo::Rascunho);

    Notification::assertSentTo($this->diretor, ConteudoRevisadoNotification::class, function (ConteudoRevisadoNotification $aviso) {
        return ! $aviso->aprovado && $aviso->motivo === 'Revise o texto.';
    });
    $this->get(route('sobre'))->assertDontSee('Novo título');
});

test('o html do editor é sanitizado antes de chegar ao site', function () {
    $campos = [
        'titulo' => 'Quem somos',
        'historia_titulo' => 'História',
        'historia_texto' => '<p onclick="alert(1)">Fundada em <strong>2019</strong>.</p><script>alert("xss")</script><a href="javascript:alert(1)">link</a>',
        'missao_titulo' => 'Missão',
        'objetivos_titulo' => 'Objetivos',
        'principios_titulo' => 'Princípios',
    ];

    $this->actingAs($this->presidente)
        ->put(route('portal.conteudo.update', 'sobre-quem-somos'), ['acao' => 'publicar', 'campos' => $campos])
        ->assertSessionHasNoErrors();

    $this->get(route('sobre'))
        ->assertSee('<p>Fundada em <strong>2019</strong>.</p>', false)
        ->assertDontSee('alert(', false)
        ->assertDontSee('javascript:', false);
});

test('ficam guardadas só as 3 últimas versões e restaurar copia a versão para o rascunho', function () {
    foreach (['Primeiro', 'Segundo', 'Terceiro', 'Quarto'] as $titulo) {
        $this->actingAs($this->presidente)->put(route('portal.conteudo.update', 'sobre-banner'), envioDoBanner($titulo, 'publicar'));
    }

    $conteudo = ConteudoEditavel::query()->where('chave', 'sobre-banner')->firstOrFail();

    expect($conteudo->versoes()->get()->pluck('conteudo.titulo')->all())->toBe(['Terceiro', 'Segundo', 'Primeiro']);

    $this->actingAs($this->diretor)
        ->post(route('portal.conteudo.versoes.restaurar', ['sobre-banner', $conteudo->versoes()->get()->last()->id]))
        ->assertRedirect(route('portal.conteudo.edit', 'sobre-banner'));

    expect($conteudo->fresh()->rascunho['titulo'])->toBe('Primeiro');
    $this->get(route('sobre'))->assertSee('Quarto')->assertDontSee('Primeiro');
});

test('campos obrigatórios, e-mail e endereço https são validados', function () {
    $this->actingAs($this->diretor)
        ->put(route('portal.conteudo.update', 'site-rodape'), [
            'acao' => 'rascunho',
            'campos' => [
                'descricao' => '',
                'instituicao' => 'FADIR/UFU',
                'endereco' => 'Uberlândia/MG',
                'email' => 'nao-e-email',
                'instagram' => 'http://instagram.com/laccrim',
            ],
        ])
        ->assertSessionHasErrors(['campos.descricao', 'campos.email', 'campos.instagram']);

    expect(ConteudoEditavel::query()->count())->toBe(0);
});

test('quem não edita o site não acessa o CMS e áreas inexistentes não existem', function () {
    $ligante = User::factory()->comPerfil(PerfilAcesso::Ligante)->create();

    $this->actingAs($ligante)->get(route('portal.conteudo.index'))->assertForbidden();
    $this->actingAs($ligante)->put(route('portal.conteudo.update', 'sobre-banner'), envioDoBanner('Invasão'))->assertForbidden();
    $this->actingAs($this->diretor)->get(route('portal.conteudo.edit', 'area-inexistente'))->assertNotFound();
});

test('as telas do CMS mostram as áreas e os botões conforme o cargo', function () {
    $this->actingAs($this->diretor)->get(route('portal.conteudo.index'))
        ->assertOk()
        ->assertSee('Sobre Nós')
        ->assertSee('Identidade e rodapé');

    $this->actingAs($this->diretor)->get(route('portal.conteudo.edit', 'sobre-banner'))
        ->assertOk()
        ->assertSee('Salvar e solicitar aprovação')
        ->assertDontSee('Salvar e publicar');

    $this->actingAs($this->presidente)->get(route('portal.conteudo.edit', 'sobre-banner'))
        ->assertOk()
        ->assertSee('Salvar e publicar');
});

test('a foto de perfil do portal aparece no corpo social do integrante ligado à conta', function () {
    Storage::fake('public');
    $this->presidente->forceFill(['foto_perfil' => 'fotos-perfil/presidente.png'])->save();

    config(['laccrim.exemplos.corpo_social' => [[
        'nome' => 'Maria Souza',
        'cargo' => 'Presidente',
        'descricao' => 'Coordena a Liga.',
        'foto' => null,
        'hierarquia' => 'presidencia',
        'ordem' => 1,
        'aprovado' => true,
        'exibir_publicamente' => true,
        'membro_portal_id' => $this->presidente->id,
    ]]]);

    $this->get(route('sobre'))
        ->assertSee('Maria Souza')
        ->assertSee(Storage::disk('public')->url('fotos-perfil/presidente.png'), false);
});
