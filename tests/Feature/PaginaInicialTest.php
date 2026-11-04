<?php

test('a página inicial exibe o hero com o título e os botões de ação', function () {
    $response = $this->get(route('home'));

    $response->assertOk()
        ->assertSee('Universidade Federal de Uberlândia')
        ->assertSee('<span class="text-destaque">Ciências Criminais</span>', false)
        ->assertSeeText('Conheça nossa Liga')
        ->assertSee('href="'.route('sobre').'"', false)
        ->assertSee('href="'.route('protocolo').'"', false);
});

test('a página inicial exibe os três pilares', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeText('Três pilares, um propósito')
        ->assertSeeTextInOrder(['Ensino', 'Pesquisa', 'Extensão']);
});

test('as seções seguem a ordem configurada e seções ocultas não são renderizadas', function () {
    config(['laccrim.home.secoes' => [
        ['chave' => 'cta', 'visivel' => true, 'ordem' => 1],
        ['chave' => 'pilares', 'visivel' => true, 'ordem' => 2],
        ['chave' => 'hero', 'visivel' => false, 'ordem' => 3],
    ]]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeTextInOrder(['Participe da LACCRIM', 'Três pilares, um propósito'])
        ->assertDontSee('hero-titulo');
});

test('as datas são exibidas em dd/mm/aaaa no fuso de São Paulo', function () {
    config(['laccrim.exemplos.publicacoes' => [[
        'categoria' => 'Edital',
        'titulo' => 'Edital de teste',
        'resumo' => 'Resumo',
        'autor' => 'Diretoria',
        'situacao' => 'aprovado',
        'publicado' => true,
        'publicado_em' => '2026-11-01T01:30:00Z',
    ]]]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeText('31/10/2026')
        ->assertDontSeeText('01/11/2026');
});

test('próximos eventos exibe apenas eventos futuros, do mais próximo ao mais distante', function () {
    $this->travelTo('2026-11-01T12:00:00Z');

    config(['laccrim.exemplos.eventos' => [
        ['categoria' => 'Palestra', 'titulo' => 'Evento distante', 'resumo' => 'R', 'inicio' => '2026-12-10T22:00:00Z', 'local' => 'Online', 'inscricoes' => 'abertas'],
        ['categoria' => 'Palestra', 'titulo' => 'Evento passado', 'resumo' => 'R', 'inicio' => '2026-10-01T22:00:00Z', 'local' => 'Online', 'inscricoes' => 'encerradas'],
        ['categoria' => 'Palestra', 'titulo' => 'Evento próximo', 'resumo' => 'R', 'inicio' => '2026-11-05T22:00:00Z', 'local' => 'Online', 'inscricoes' => 'esgotadas'],
    ]]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeTextInOrder(['Evento próximo', 'Evento distante'])
        ->assertDontSeeText('Evento passado')
        ->assertSeeText('05/11/2026 às 19h00')
        ->assertSeeText('Vagas esgotadas');
});

test('seções dinâmicas sem conteúdo exibem um aviso amigável', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeText('Nenhuma publicação disponível no momento.')
        ->assertSeeText('Nenhum evento agendado no momento.')
        ->assertSeeText('Nenhuma publicação em destaque no momento.')
        ->assertDontSeeText('Edital de seleção de novos membros 2027/1')
        ->assertDontSeeText('Seminário de Processo Penal e Garantias Fundamentais')
        ->assertDontSeeText('A cadeia de custódia da prova no processo penal brasileiro');
});
