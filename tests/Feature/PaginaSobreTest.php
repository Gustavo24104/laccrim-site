<?php

test('a página sobre nós exibe o banner, as seções vazias e os cartões da hierarquia', function () {
    $html = $this->get(route('sobre'))->assertOk()->getContent();

    expect($html)
        ->toContain('Sobre a LACCRIM')
        ->toContain('entidade estudantil vinculada')
        ->toContain('FADIR')
        ->toContain('id="corpo-social"')
        ->toContain('text-[#2d3748]')
        ->toContain('data-foto-vazia')
        ->toContain('background-color: #007bff')
        ->toContain('background-color: #bf2d00')
        ->toContain('background-color: #0bd900')
        ->toContain('background-color: #68728f')
        ->not->toContain('data-leitor-controles')
        ->not->toContain('Ampliar');

    expect($html)->toMatch('/<h1[^>]*id="sobre-titulo"[^>]*class="[^"]*font-serif[^"]*"/');

    $this->get(route('sobre'))
        ->assertSeeTextInOrder([
            'Quem somos',
            'História',
            'Missão',
            'Objetivos',
            'Princípios',
            'Estatuto Social',
            'Corpo Social',
            'Presidência',
            'Vice-Presidência',
            'Diretorias',
            'Coordenações',
            'Membros',
            'Professores Orientadores',
            'Apresentação',
        ])
        ->assertSee('Baixar estatuto', false)
        ->assertSee('<button', false)
        ->assertSee('disabled', false);
});

test('o corpo social público segue a hierarquia e omite quem não autorizou a exibição', function () {
    config(['laccrim.exemplos.corpo_social' => [
        [
            'nome' => 'Lia Campos',
            'cargo' => 'Membro discente',
            'descricao' => 'texto livre do editor',
            'foto' => null,
            'hierarquia' => 'membro',
            'ordem' => 1,
            'aprovado' => true,
            'exibir_publicamente' => true,
            'membro_portal_id' => 987654,
        ],
        [
            'nome' => 'Caio Mendes',
            'cargo' => 'Diretor de Ensino',
            'descricao' => 'Pesquisa aplicada',
            'foto' => 'https://exemplo.test/caio.jpg',
            'hierarquia' => 'diretoria',
            'ordem' => 2,
            'aprovado' => true,
            'exibir_publicamente' => true,
            'cor' => '#ffffff',
        ],
        [
            'nome' => 'Bia Nunes',
            'cargo' => 'Diretora de Pesquisa',
            'descricao' => 'Extensão',
            'foto' => null,
            'hierarquia' => 'diretoria',
            'ordem' => 1,
            'aprovado' => true,
            'exibir_publicamente' => true,
        ],
        [
            'nome' => 'Oculto Ramos',
            'cargo' => 'Presidente',
            'descricao' => 'Não publicar',
            'foto' => null,
            'hierarquia' => 'presidencia',
            'ordem' => 1,
            'aprovado' => true,
            'exibir_publicamente' => false,
            'membro_portal_id' => 3,
        ],
        [
            'nome' => 'Pendente Luz',
            'cargo' => 'Vice',
            'descricao' => 'Aguardando aprovação',
            'foto' => null,
            'hierarquia' => 'vice_presidencia',
            'ordem' => 1,
            'aprovado' => false,
            'exibir_publicamente' => true,
        ],
        [
            'nome' => 'Invalido Costa',
            'cargo' => 'Cargo inventado',
            'descricao' => 'Fora da hierarquia',
            'foto' => 'javascript:alert(1)',
            'hierarquia' => 'invasor',
            'ordem' => 1,
            'aprovado' => true,
            'exibir_publicamente' => true,
        ],
    ]]);

    $resposta = $this->get(route('sobre'));
    $html = $resposta->assertOk()->getContent();

    $resposta
        ->assertSeeTextInOrder(['Bia Nunes', 'Caio Mendes', 'Lia Campos'])
        ->assertSeeText('Diretor de Ensino')
        ->assertSeeText('texto livre do editor')
        ->assertDontSeeText('Oculto Ramos')
        ->assertDontSeeText('Pendente Luz')
        ->assertDontSeeText('Invalido Costa')
        ->assertDontSeeText('Vice-Presidência');

    expect($html)
        ->toContain('src="https://exemplo.test/caio.jpg"')
        ->toContain('width="1080"')
        ->toContain('height="1350"')
        ->toContain('background-color: #bf2d00')
        ->not->toContain('#ffffff')
        ->not->toContain('javascript:')
        ->not->toContain('987654');

    expect(substr_count($html, 'data-cartao-vazio'))->toBe(1);
});

test('os professores orientadores públicos aparecem na ordem definida e sem o cartão vazio', function () {
    config(['laccrim.exemplos.professores' => [
        [
            'nome' => 'Prof. Helena Prado',
            'titulacao' => 'Doutora',
            'area_atuacao' => 'Direito Penal',
            'apresentacao' => 'Apresentação em frase',
            'foto' => null,
            'ordem' => 2,
            'aprovado' => true,
            'exibir_publicamente' => true,
        ],
        [
            'nome' => 'Prof. Ivo Lima',
            'titulacao' => 'Mestre',
            'area_atuacao' => 'Criminologia',
            'apresentacao' => 'Atua na orientação',
            'foto' => 'https://exemplo.test/ivo.jpg',
            'ordem' => 1,
            'aprovado' => true,
            'exibir_publicamente' => true,
        ],
        [
            'nome' => 'Prof. Oculto',
            'titulacao' => 'Doutor',
            'area_atuacao' => 'Processo Penal',
            'apresentacao' => 'Não exibir',
            'foto' => null,
            'ordem' => 1,
            'aprovado' => true,
            'exibir_publicamente' => false,
        ],
    ]]);

    $html = $this->get(route('sobre'))->assertOk()
        ->assertSeeTextInOrder(['Prof. Ivo Lima', 'Prof. Helena Prado'])
        ->assertSeeText('Doutora')
        ->assertSeeText('Direito Penal')
        ->assertSeeText('Apresentação em frase')
        ->assertDontSeeText('Prof. Oculto')
        ->getContent();

    expect($html)->not->toContain('Professor orientador');
});

test('quem somos mantém os subtítulos e só exibe o texto quando ele existe', function () {
    config(['laccrim.sobre.quem_somos.blocos' => [
        ['chave' => 'historia', 'titulo' => 'História', 'texto' => "Linha um\nLinha dois"],
        ['chave' => 'missao', 'titulo' => 'Missão', 'texto' => ''],
        ['chave' => 'objetivos', 'titulo' => 'Objetivos', 'texto' => 'Formar juristas críticos'],
        ['chave' => 'principios', 'titulo' => 'Princípios', 'texto' => ''],
    ]]);

    $this->get(route('sobre'))
        ->assertOk()
        ->assertSeeTextInOrder(['História', 'Linha um', 'Linha dois', 'Missão', 'Objetivos', 'Formar juristas críticos', 'Princípios']);
});

test('o estatuto embute apenas um arquivo seguro e esconde o zoom até a visualização avançada', function () {
    config(['laccrim.sobre.estatuto.arquivo' => '/documentos/estatuto.pdf']);

    $html = $this->get(route('sobre'))->assertOk()
        ->assertSee('src="/documentos/estatuto.pdf"', false)
        ->assertSee('href="/documentos/estatuto.pdf"', false)
        ->assertSee('download="estatuto-social.pdf"', false)
        ->assertSee('data-leitor-controles', false)
        ->assertSee('hidden', false)
        ->assertSee('aria-label="Ampliar"', false)
        ->assertSee('aria-label="Reduzir"', false)
        ->getContent();

    expect($html)->toMatch('/data-leitor-controles hidden/');

    config(['laccrim.sobre.estatuto.arquivo' => 'javascript:alert(1)']);

    $this->get(route('sobre'))
        ->assertOk()
        ->assertDontSee('javascript:', false)
        ->assertDontSee('data-leitor-controles', false)
        ->assertSee('disabled', false);
});

test('a página sobre nós escapa o texto vindo do conteúdo editável', function () {
    $payload = '<script>alert(1)</script>';

    config([
        'laccrim.sobre.banner.texto' => $payload,
        'laccrim.sobre.quem_somos.blocos' => [
            ['chave' => 'historia', 'titulo' => $payload, 'texto' => $payload],
        ],
        'laccrim.exemplos.corpo_social' => [[
            'nome' => $payload,
            'cargo' => $payload,
            'descricao' => $payload,
            'foto' => null,
            'hierarquia' => 'presidencia',
            'ordem' => 1,
            'aprovado' => true,
            'exibir_publicamente' => true,
        ]],
        'laccrim.exemplos.professores' => [[
            'nome' => $payload,
            'titulacao' => $payload,
            'area_atuacao' => $payload,
            'apresentacao' => $payload,
            'foto' => null,
            'ordem' => 1,
            'aprovado' => true,
            'exibir_publicamente' => true,
        ]],
    ]);

    $html = $this->get(route('sobre'))->assertOk()->getContent();

    expect($html)
        ->toContain('&lt;script&gt;')
        ->not->toContain('<script>alert(1)</script>');
});
