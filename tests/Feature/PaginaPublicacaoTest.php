<?php

test('sem registros do CMS as listagens ficam vazias', function () {
    $this->get(route('noticias'))
        ->assertOk()
        ->assertSeeText('Nenhuma publicação disponível no momento.')
        ->assertDontSeeText('Visualizar');

    $this->get(route('editais'))
        ->assertOk()
        ->assertSeeText('Nenhuma publicação disponível no momento.');
});

test('a listagem de notícias mostra só notícias aprovadas e publicadas', function () {
    config(['laccrim.exemplos.publicacoes' => acervoPublicoDeTeste()]);

    $this->get(route('noticias'))
        ->assertOk()
        ->assertSeeText('Notícias')
        ->assertSeeText('Publicações aprovadas e publicadas pela Liga.')
        ->assertSeeTextInOrder(['Todas', 'Editais', 'Atas', 'Notícias'])
        ->assertSeeText('LACCRIM participa de seminário sobre política criminal')
        ->assertSeeText('Grupo de estudos de Criminologia retoma encontros')
        ->assertDontSeeText('Edital de seleção de novos membros 2027/1')
        ->assertDontSeeText('Ata da Assembleia Geral Ordinária de outubro')
        ->assertSee('href="'.route('noticias.show', 'laccrim-participa-de-seminario-sobre-politica-criminal').'"', false);
});

test('todas as categorias aparecem da mais recente para a mais antiga', function () {
    config(['laccrim.exemplos.publicacoes' => acervoPublicoDeTeste()]);

    $this->get(route('noticias', ['categoria' => 'todas']))
        ->assertOk()
        ->assertSeeTextInOrder([
            'Edital de seleção de novos membros 2027/1',
            'LACCRIM participa de seminário sobre política criminal',
            'Ata da Assembleia Geral Ordinária de outubro',
            'Grupo de estudos de Criminologia retoma encontros',
        ]);
});

test('editais e atas reúne as duas categorias e destaca os dois filtros', function () {
    config(['laccrim.exemplos.publicacoes' => acervoPublicoDeTeste()]);

    $html = $this->get(route('editais'))->assertOk()->getContent();

    expect($html)
        ->toContain('Editais e atas aprovados e publicados pela Liga.')
        ->toContain('Edital de seleção de novos membros 2027/1')
        ->toContain('Ata da Assembleia Geral Ordinária de outubro')
        ->not->toContain('LACCRIM participa de seminário sobre política criminal')
        ->toMatch('/categoria=edital"\s+aria-current="true"/')
        ->toMatch('/categoria=ata"\s+aria-current="true"/')
        ->not->toMatch('/categoria=noticia"\s+aria-current="true"/')
        ->not->toMatch('/categoria=todas"\s+aria-current="true"/');
});

test('a busca encontra título, palavra-chave e conteúdo', function (string $termo, string $titulo, string $ausente) {
    config(['laccrim.exemplos.publicacoes' => acervoPublicoDeTeste()]);

    $this->get(route('noticias', ['categoria' => 'todas', 'q' => $termo]))
        ->assertOk()
        ->assertSeeText($titulo)
        ->assertDontSeeText($ausente);
})->with([
    ['seleção', 'Edital de seleção de novos membros 2027/1', 'Ata da Assembleia Geral Ordinária de outubro'],
    ['processo-seletivo', 'Edital de seleção de novos membros 2027/1', 'Grupo de estudos de Criminologia retoma encontros'],
    ['plenario', 'Ata da Assembleia Geral Ordinária de outubro', 'Edital de seleção de novos membros 2027/1'],
]);

test('a busca não usa o autor quando ele não está no conteúdo', function () {
    config(['laccrim.exemplos.publicacoes' => acervoPublicoDeTeste()]);

    $this->get(route('noticias', ['categoria' => 'todas', 'q' => 'Secretaria-Geral']))
        ->assertOk()
        ->assertSeeText('Nenhuma publicação encontrada para essa busca.')
        ->assertDontSeeText('Ata da Assembleia Geral Ordinária de outubro');
});

test('publicações pendentes, rejeitadas ou não publicadas ficam fora do site', function (array $estado) {
    config(['laccrim.exemplos.publicacoes' => [[
        'categoria' => 'noticia',
        'slug' => 'registro-interno',
        'titulo' => 'Registro interno',
        'resumo' => 'Resumo interno',
        'conteudo' => 'Conteúdo interno',
        'autor' => 'Diretoria',
        'publicado_em' => '2026-10-01T12:00:00Z',
        ...$estado,
    ]]]);

    $this->get(route('noticias', ['categoria' => 'todas']))
        ->assertOk()
        ->assertDontSeeText('Registro interno')
        ->assertSeeText('Nenhuma publicação disponível no momento.');

    $this->get(route('noticias.show', 'registro-interno'))
        ->assertNotFound()
        ->assertDontSeeText('Registro interno');

    $this->get(route('home'))
        ->assertOk()
        ->assertDontSeeText('Registro interno');
})->with([
    [['situacao' => 'pendente', 'publicado' => true]],
    [['situacao' => 'rejeitado', 'publicado' => true]],
    [['situacao' => 'aprovado', 'publicado' => false]],
    [['situacao' => 'aprovado', 'publicado' => 'true']],
]);

test('a paginação conserva a categoria e a busca', function () {
    $itens = [];

    for ($indice = 10; $indice >= 1; $indice--) {
        $itens[] = [
            'categoria' => 'noticia',
            'slug' => 'boletim-alfa-'.$indice,
            'titulo' => sprintf('Boletim alfa %02d', $indice),
            'resumo' => 'Resumo',
            'conteudo' => 'Conteúdo',
            'autor' => 'Diretoria',
            'situacao' => 'aprovado',
            'publicado' => true,
            'publicado_em' => sprintf('2026-10-%02dT12:00:00Z', $indice),
        ];
    }

    config(['laccrim.exemplos.publicacoes' => $itens]);

    $this->get(route('noticias', ['categoria' => 'todas', 'q' => 'boletim']))
        ->assertOk()
        ->assertSeeText('Boletim alfa 10')
        ->assertDontSeeText('Boletim alfa 01')
        ->assertSee('categoria=todas', false)
        ->assertSee('q=boletim', false)
        ->assertSee('page=2', false)
        ->assertSeeText('Anterior')
        ->assertSeeText('Próxima');

    $this->get(route('noticias', ['categoria' => 'todas', 'q' => 'boletim', 'page' => 2]))
        ->assertOk()
        ->assertSeeText('Boletim alfa 01')
        ->assertDontSeeText('Boletim alfa 10');
});

test('o detalhe exibe o conteúdo e o visualizador quando há PDF', function () {
    config(['laccrim.exemplos.publicacoes' => [[
        'categoria' => 'edital',
        'slug' => 'edital-com-anexos',
        'titulo' => 'Edital com anexos',
        'resumo' => 'Resumo do edital',
        'conteudo' => 'Texto completo do edital.',
        'autor' => 'Diretoria de Ensino',
        'situacao' => 'aprovado',
        'publicado' => true,
        'publicado_em' => '2026-10-28T13:00:00Z',
        'anexos' => [
            ['nome' => 'planilha.xlsx', 'url' => '/documentos/planilha.xlsx'],
            ['nome' => 'edital.pdf', 'url' => '/documentos/edital.pdf', 'mime' => 'application/pdf'],
            ['nome' => 'atalho', 'url' => 'javascript:alert(1)'],
        ],
    ]]]);

    $listagem = $this->get(route('editais'))->assertOk();

    $listagem
        ->assertSee('href="/documentos/edital.pdf"', false)
        ->assertSeeText('Baixar PDF')
        ->assertDontSee('javascript:alert', false)
        ->assertDontSee('planilha.xlsx', false);

    $this->get(route('noticias.show', 'edital-com-anexos'))
        ->assertOk()
        ->assertSeeText('Texto completo do edital.')
        ->assertSee('src="/documentos/edital.pdf"', false)
        ->assertSee('data-leitor-controles', false)
        ->assertSeeText('planilha.xlsx')
        ->assertSeeText('edital.pdf')
        ->assertDontSee('javascript:alert', false);

    expect($listagem->getContent())->toMatch('/href="'.preg_quote(route('editais'), '/').'"\s+aria-current="page"/');
});

test('anexo que não é PDF fica só como download', function () {
    config(['laccrim.exemplos.publicacoes' => [[
        'categoria' => 'noticia',
        'slug' => 'noticia-com-planilha',
        'titulo' => 'Notícia com planilha',
        'resumo' => 'Resumo',
        'conteudo' => 'Conteúdo da notícia.',
        'autor' => 'Diretoria',
        'situacao' => 'aprovado',
        'publicado' => true,
        'publicado_em' => '2026-10-20T12:00:00Z',
        'anexos' => [
            ['nome' => 'planilha.xlsx', 'url' => '/documentos/planilha.xlsx'],
        ],
    ]]]);

    $this->get(route('noticias'))
        ->assertOk()
        ->assertSeeText('Baixar anexo')
        ->assertSee('href="/documentos/planilha.xlsx"', false)
        ->assertDontSee('data-leitor-pdf', false);

    $this->get(route('noticias.show', 'noticia-com-planilha'))
        ->assertOk()
        ->assertSee('href="/documentos/planilha.xlsx"', false)
        ->assertDontSee('data-leitor-pdf', false)
        ->assertDontSee('<iframe', false);
});

test('publicação sem anexo não mostra visualizador', function () {
    config(['laccrim.exemplos.publicacoes' => acervoPublicoDeTeste()]);

    $this->get(route('noticias.show', 'laccrim-participa-de-seminario-sobre-politica-criminal'))
        ->assertOk()
        ->assertSeeText('A delegação da Liga apresentou pesquisas')
        ->assertDontSee('data-leitor-pdf', false)
        ->assertDontSeeText('Arquivos anexos');
});

test('uma categoria nova do CMS aparece no filtro sem mudança de código', function () {
    $categorias = config('laccrim.publicacoes.categorias');
    $categorias[] = ['chave' => 'boletim', 'rotulo' => 'Boletim', 'rotulo_filtro' => 'Boletins', 'ordem' => 4];

    config([
        'laccrim.publicacoes.categorias' => $categorias,
        'laccrim.exemplos.publicacoes' => [[
            'categoria' => 'boletim',
            'slug' => 'boletim-de-outubro',
            'titulo' => 'Boletim de outubro',
            'resumo' => 'Resumo do boletim',
            'conteudo' => 'Conteúdo do boletim.',
            'autor' => 'Diretoria de Comunicação',
            'situacao' => 'aprovado',
            'publicado' => true,
            'publicado_em' => '2026-10-15T12:00:00Z',
        ]],
    ]);

    $this->get(route('noticias'))
        ->assertOk()
        ->assertSeeText('Boletins')
        ->assertDontSeeText('Boletim de outubro');

    $this->get(route('noticias', ['categoria' => 'boletim']))
        ->assertOk()
        ->assertSeeText('Boletim de outubro')
        ->assertSeeText('Boletim');
});

test('título, conteúdo e busca são exibidos escapados', function () {
    config(['laccrim.exemplos.publicacoes' => [[
        'categoria' => 'noticia',
        'slug' => 'publicacao-com-marcacao',
        'titulo' => '<script>alert(1)</script>',
        'resumo' => 'Resumo',
        'conteudo' => '<img src=x onerror=alert(1)>',
        'autor' => 'Diretoria',
        'situacao' => 'aprovado',
        'publicado' => true,
        'publicado_em' => '2026-10-20T12:00:00Z',
    ]]]);

    $this->get(route('noticias', ['q' => '<script>alert(1)</script>']))
        ->assertOk()
        ->assertDontSee('<script>alert(1)</script>', false)
        ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);

    $this->get(route('noticias.show', 'publicacao-com-marcacao'))
        ->assertOk()
        ->assertDontSee('<img src=x onerror=alert(1)>', false)
        ->assertSee('&lt;img src=x onerror=alert(1)&gt;', false);
});

test('consulta em lista não quebra a listagem', function () {
    $this->get('/noticias?q[]=x')->assertOk();
});

test('o detalhe destaca o item de menu da seção', function () {
    config(['laccrim.exemplos.publicacoes' => acervoPublicoDeTeste()]);

    $html = $this->get(route('noticias.show', 'laccrim-participa-de-seminario-sobre-politica-criminal'))
        ->assertOk()
        ->getContent();

    expect($html)->toMatch('/href="'.preg_quote(route('noticias'), '/').'"\s+aria-current="page"/');
});

test('a página inicial leva cada publicação ao seu detalhe e o botão geral a todas', function () {
    config(['laccrim.exemplos.publicacoes' => acervoPublicoDeTeste()]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('href="'.route('noticias', ['categoria' => 'todas']).'"', false)
        ->assertSee('href="'.route('noticias.show', 'laccrim-participa-de-seminario-sobre-politica-criminal').'"', false)
        ->assertSee('href="'.route('editais.show', 'edital-selecao-novos-membros-2027-1').'"', false);
});

test('slug desconhecido responde como página não encontrada', function () {
    $this->get(route('noticias.show', 'publicacao-inexistente'))->assertNotFound();
});

/**
 * @return list<array<string, mixed>>
 */
function acervoPublicoDeTeste(): array
{
    return [
        [
            'categoria' => 'edital',
            'slug' => 'edital-selecao-novos-membros-2027-1',
            'titulo' => 'Edital de seleção de novos membros 2027/1',
            'resumo' => 'Estão abertas as inscrições para o processo seletivo de novos integrantes da Liga.',
            'conteudo' => 'O processo seletivo recebe inscrições de estudantes da FADIR. O edital descreve as etapas, os prazos e os documentos exigidos.',
            'autor' => 'Diretoria de Ensino',
            'palavras_chave' => ['processo-seletivo'],
            'anexos' => [],
            'situacao' => 'aprovado',
            'publicado' => true,
            'publicado_em' => '2026-10-28T13:00:00Z',
        ],
        [
            'categoria' => 'noticia',
            'slug' => 'laccrim-participa-de-seminario-sobre-politica-criminal',
            'titulo' => 'LACCRIM participa de seminário sobre política criminal',
            'resumo' => 'Integrantes apresentaram pesquisas sobre encarceramento e alternativas penais.',
            'conteudo' => 'A delegação da Liga apresentou pesquisas sobre encarceramento e alternativas penais durante o seminário.',
            'autor' => 'Diretoria de Comunicação',
            'palavras_chave' => ['politica-criminal'],
            'anexos' => [],
            'situacao' => 'aprovado',
            'publicado' => true,
            'publicado_em' => '2026-10-20T18:30:00Z',
        ],
        [
            'categoria' => 'ata',
            'slug' => 'ata-assembleia-geral-ordinaria-de-outubro',
            'titulo' => 'Ata da Assembleia Geral Ordinária de outubro',
            'resumo' => 'Registro das deliberações da assembleia, incluindo o calendário do semestre.',
            'conteudo' => 'O plenário aprovou o calendário do semestre e registrou as deliberações da assembleia.',
            'autor' => 'Secretaria-Geral',
            'palavras_chave' => ['assembleia'],
            'anexos' => [],
            'situacao' => 'aprovado',
            'publicado' => true,
            'publicado_em' => '2026-10-10T22:00:00Z',
        ],
        [
            'categoria' => 'noticia',
            'slug' => 'grupo-de-estudos-de-criminologia-retoma-encontros',
            'titulo' => 'Grupo de estudos de Criminologia retoma encontros',
            'resumo' => 'Os encontros quinzenais voltam a acontecer na FADIR.',
            'conteudo' => 'Os encontros quinzenais voltam a acontecer na FADIR, com leitura dirigida de textos de criminologia.',
            'autor' => 'Diretoria de Pesquisa',
            'palavras_chave' => ['criminologia'],
            'anexos' => [],
            'situacao' => 'aprovado',
            'publicado' => true,
            'publicado_em' => '2026-09-30T15:00:00Z',
        ],
    ];
}
