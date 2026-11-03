<?php

test('todas as páginas públicas respondem com sucesso', function (string $rota, string $titulo) {
    $this->get(route($rota))
        ->assertOk()
        ->assertSeeText($titulo);
})->with([
    ['sobre', 'Sobre Nós'],
    ['noticias', 'Notícias'],
    ['editais', 'Editais e Atas'],
    ['eventos', 'Eventos'],
    ['protocolo', 'Protocolo Virtual'],
    ['portal', 'Portal da Liga'],
    ['privacidade', 'Política de Privacidade'],
    ['termos', 'Termos de Uso'],
]);

test('o cabeçalho exibe os itens do menu e o botão Portal da Liga', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSeeTextInOrder(['Página Inicial', 'Sobre Nós', 'Notícias', 'Editais e Atas', 'Eventos', 'Protocolo Virtual', 'Portal da Liga'])
        ->assertSee('href="'.route('portal').'"', false);
});

test('o menu é montado a partir da fonte de conteúdo', function () {
    config(['laccrim.menu' => [
        ['rotulo' => 'Página Inicial', 'rota' => 'home'],
        ['rotulo' => 'Biblioteca', 'url' => '/biblioteca'],
    ]]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSeeText('Biblioteca')
        ->assertSee('href="/biblioteca"', false)
        ->assertDontSeeText('Editais e Atas');
});

test('o item da página atual é marcado como ativo', function () {
    $html = $this->get(route('eventos'))->assertOk()->getContent();

    expect($html)->toMatch('/href="'.preg_quote(route('eventos'), '/').'"\s+aria-current="page"/');
});

test('o rodapé exibe contato, parceiros e links legais', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('href="mailto:laccrim@fadir.ufu.br"', false)
        ->assertSeeTextInOrder(['NUAVIDAS', 'Projeto Livre', 'PROEXC', 'UFMT', 'UFMA', 'FBDG'])
        ->assertSee('href="'.route('privacidade').'"', false)
        ->assertSee('href="'.route('termos').'"', false);
});

test('cores personalizadas válidas sobrescrevem o tema e valores inválidos são descartados', function () {
    config(['laccrim.cores' => [
        'primaria' => '#112233',
        'destaque' => 'red; } body { display: none',
    ]]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('--color-primaria: #112233;', false)
        ->assertDontSee('display: none', false);
});

test('o documento é servido em pt-BR com link para pular ao conteúdo', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<html lang="pt-BR">', false)
        ->assertSee('href="#conteudo"', false);
});
