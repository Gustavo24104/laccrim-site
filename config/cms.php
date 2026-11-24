<?php

/*
|--------------------------------------------------------------------------
| Áreas editáveis do site pelo CMS do portal
|--------------------------------------------------------------------------
|
| Cada área agrupa campos aprovados juntos. caminho aponta para o valor
| original em config/laccrim.php, usado enquanto nada for publicado.
| Tipos: texto (uma linha), texto_longo (parágrafo sem formatação),
| html (editor visual, sanitizado no servidor), email e url (https).
| Campos sem obrigatorio => false precisam ser preenchidos.
| A chave da área é usada na URL: apenas letras minúsculas e hífens.
| previa indica a rota pública (e a âncora opcional) que mostra a área.
|
*/

return [

    'versoes_mantidas' => 3,

    'areas' => [

        'home-hero' => [
            'rotulo' => 'Destaque principal',
            'pagina' => 'Página inicial',
            'previa' => ['rota' => 'home'],
            'campos' => [
                ['nome' => 'identificacao', 'rotulo' => 'Identificação acima do título', 'caminho' => 'laccrim.home.hero.identificacao', 'tipo' => 'texto'],
                ['nome' => 'titulo', 'rotulo' => 'Título', 'caminho' => 'laccrim.home.hero.titulo', 'tipo' => 'texto'],
                ['nome' => 'destaque', 'rotulo' => 'Trecho do título em destaque (precisa aparecer no título)', 'caminho' => 'laccrim.home.hero.destaque', 'tipo' => 'texto', 'obrigatorio' => false],
                ['nome' => 'texto', 'rotulo' => 'Texto de apresentação', 'caminho' => 'laccrim.home.hero.texto', 'tipo' => 'texto_longo'],
                ['nome' => 'botao_primario', 'rotulo' => 'Rótulo do primeiro botão', 'caminho' => 'laccrim.home.hero.botoes.0.rotulo', 'tipo' => 'texto', 'max' => 40],
                ['nome' => 'botao_secundario', 'rotulo' => 'Rótulo do segundo botão', 'caminho' => 'laccrim.home.hero.botoes.1.rotulo', 'tipo' => 'texto', 'max' => 40],
            ],
        ],

        'home-pilares' => [
            'rotulo' => 'Pilares',
            'pagina' => 'Página inicial',
            'previa' => ['rota' => 'home', 'ancora' => 'pilares-titulo'],
            'campos' => [
                ['nome' => 'titulo', 'rotulo' => 'Título da seção', 'caminho' => 'laccrim.home.pilares.titulo', 'tipo' => 'texto'],
                ['nome' => 'subtitulo', 'rotulo' => 'Subtítulo da seção', 'caminho' => 'laccrim.home.pilares.subtitulo', 'tipo' => 'texto_longo'],
                ['nome' => 'pilar_1_titulo', 'rotulo' => '1º pilar: título', 'caminho' => 'laccrim.home.pilares.itens.0.titulo', 'tipo' => 'texto', 'max' => 60],
                ['nome' => 'pilar_1_descricao', 'rotulo' => '1º pilar: descrição', 'caminho' => 'laccrim.home.pilares.itens.0.descricao', 'tipo' => 'texto_longo'],
                ['nome' => 'pilar_2_titulo', 'rotulo' => '2º pilar: título', 'caminho' => 'laccrim.home.pilares.itens.1.titulo', 'tipo' => 'texto', 'max' => 60],
                ['nome' => 'pilar_2_descricao', 'rotulo' => '2º pilar: descrição', 'caminho' => 'laccrim.home.pilares.itens.1.descricao', 'tipo' => 'texto_longo'],
                ['nome' => 'pilar_3_titulo', 'rotulo' => '3º pilar: título', 'caminho' => 'laccrim.home.pilares.itens.2.titulo', 'tipo' => 'texto', 'max' => 60],
                ['nome' => 'pilar_3_descricao', 'rotulo' => '3º pilar: descrição', 'caminho' => 'laccrim.home.pilares.itens.2.descricao', 'tipo' => 'texto_longo'],
            ],
        ],

        'home-listagens' => [
            'rotulo' => 'Notícias, eventos e publicações',
            'pagina' => 'Página inicial',
            'previa' => ['rota' => 'home', 'ancora' => 'noticias-titulo'],
            'campos' => [
                ['nome' => 'noticias_titulo', 'rotulo' => 'Notícias: título', 'caminho' => 'laccrim.home.noticias.titulo', 'tipo' => 'texto'],
                ['nome' => 'noticias_subtitulo', 'rotulo' => 'Notícias: subtítulo', 'caminho' => 'laccrim.home.noticias.subtitulo', 'tipo' => 'texto_longo'],
                ['nome' => 'noticias_botao', 'rotulo' => 'Notícias: rótulo do botão', 'caminho' => 'laccrim.home.noticias.botao.rotulo', 'tipo' => 'texto', 'max' => 40],
                ['nome' => 'noticias_vazio', 'rotulo' => 'Notícias: mensagem sem itens', 'caminho' => 'laccrim.home.noticias.vazio', 'tipo' => 'texto'],
                ['nome' => 'eventos_titulo', 'rotulo' => 'Eventos: título', 'caminho' => 'laccrim.home.eventos.titulo', 'tipo' => 'texto'],
                ['nome' => 'eventos_subtitulo', 'rotulo' => 'Eventos: subtítulo', 'caminho' => 'laccrim.home.eventos.subtitulo', 'tipo' => 'texto_longo'],
                ['nome' => 'eventos_botao', 'rotulo' => 'Eventos: rótulo do botão', 'caminho' => 'laccrim.home.eventos.botao.rotulo', 'tipo' => 'texto', 'max' => 40],
                ['nome' => 'eventos_vazio', 'rotulo' => 'Eventos: mensagem sem itens', 'caminho' => 'laccrim.home.eventos.vazio', 'tipo' => 'texto'],
                ['nome' => 'publicacoes_titulo', 'rotulo' => 'Publicações: título', 'caminho' => 'laccrim.home.publicacoes.titulo', 'tipo' => 'texto'],
                ['nome' => 'publicacoes_subtitulo', 'rotulo' => 'Publicações: subtítulo', 'caminho' => 'laccrim.home.publicacoes.subtitulo', 'tipo' => 'texto_longo'],
                ['nome' => 'publicacoes_vazio', 'rotulo' => 'Publicações: mensagem sem itens', 'caminho' => 'laccrim.home.publicacoes.vazio', 'tipo' => 'texto'],
            ],
        ],

        'home-chamadas' => [
            'rotulo' => 'Protocolo, integrantes e chamada final',
            'pagina' => 'Página inicial',
            'previa' => ['rota' => 'home', 'ancora' => 'protocolo-titulo'],
            'campos' => [
                ['nome' => 'protocolo_titulo', 'rotulo' => 'Protocolo Virtual: título', 'caminho' => 'laccrim.home.protocolo.titulo', 'tipo' => 'texto'],
                ['nome' => 'protocolo_texto', 'rotulo' => 'Protocolo Virtual: texto', 'caminho' => 'laccrim.home.protocolo.texto', 'tipo' => 'texto_longo'],
                ['nome' => 'protocolo_botao', 'rotulo' => 'Protocolo Virtual: rótulo do botão', 'caminho' => 'laccrim.home.protocolo.botao.rotulo', 'tipo' => 'texto', 'max' => 40],
                ['nome' => 'integrantes_titulo', 'rotulo' => 'Integrantes: título', 'caminho' => 'laccrim.home.integrantes.titulo', 'tipo' => 'texto'],
                ['nome' => 'integrantes_texto', 'rotulo' => 'Integrantes: texto', 'caminho' => 'laccrim.home.integrantes.texto', 'tipo' => 'texto_longo'],
                ['nome' => 'integrantes_botao', 'rotulo' => 'Integrantes: rótulo do botão', 'caminho' => 'laccrim.home.integrantes.botao.rotulo', 'tipo' => 'texto', 'max' => 40],
                ['nome' => 'cta_titulo', 'rotulo' => 'Chamada final: título', 'caminho' => 'laccrim.home.cta.titulo', 'tipo' => 'texto'],
                ['nome' => 'cta_texto', 'rotulo' => 'Chamada final: texto', 'caminho' => 'laccrim.home.cta.texto', 'tipo' => 'texto_longo'],
                ['nome' => 'cta_botao', 'rotulo' => 'Chamada final: rótulo do botão', 'caminho' => 'laccrim.home.cta.botao.rotulo', 'tipo' => 'texto', 'max' => 40],
            ],
        ],

        'sobre-banner' => [
            'rotulo' => 'Banner',
            'pagina' => 'Sobre Nós',
            'previa' => ['rota' => 'sobre'],
            'campos' => [
                ['nome' => 'titulo', 'rotulo' => 'Título', 'caminho' => 'laccrim.sobre.banner.titulo', 'tipo' => 'texto'],
                ['nome' => 'texto', 'rotulo' => 'Texto de apresentação', 'caminho' => 'laccrim.sobre.banner.texto', 'tipo' => 'texto_longo'],
            ],
        ],

        'sobre-quem-somos' => [
            'rotulo' => 'Quem somos',
            'pagina' => 'Sobre Nós',
            'previa' => ['rota' => 'sobre', 'ancora' => 'quem-somos-titulo'],
            'campos' => [
                ['nome' => 'titulo', 'rotulo' => 'Título da seção', 'caminho' => 'laccrim.sobre.quem_somos.titulo', 'tipo' => 'texto'],
                ['nome' => 'historia_titulo', 'rotulo' => 'História: título', 'caminho' => 'laccrim.sobre.quem_somos.blocos.0.titulo', 'tipo' => 'texto', 'max' => 60],
                ['nome' => 'historia_texto', 'rotulo' => 'História: texto', 'caminho' => 'laccrim.sobre.quem_somos.blocos.0.texto', 'tipo' => 'html', 'obrigatorio' => false],
                ['nome' => 'missao_titulo', 'rotulo' => 'Missão: título', 'caminho' => 'laccrim.sobre.quem_somos.blocos.1.titulo', 'tipo' => 'texto', 'max' => 60],
                ['nome' => 'missao_texto', 'rotulo' => 'Missão: texto', 'caminho' => 'laccrim.sobre.quem_somos.blocos.1.texto', 'tipo' => 'html', 'obrigatorio' => false],
                ['nome' => 'objetivos_titulo', 'rotulo' => 'Objetivos: título', 'caminho' => 'laccrim.sobre.quem_somos.blocos.2.titulo', 'tipo' => 'texto', 'max' => 60],
                ['nome' => 'objetivos_texto', 'rotulo' => 'Objetivos: texto', 'caminho' => 'laccrim.sobre.quem_somos.blocos.2.texto', 'tipo' => 'html', 'obrigatorio' => false],
                ['nome' => 'principios_titulo', 'rotulo' => 'Princípios: título', 'caminho' => 'laccrim.sobre.quem_somos.blocos.3.titulo', 'tipo' => 'texto', 'max' => 60],
                ['nome' => 'principios_texto', 'rotulo' => 'Princípios: texto', 'caminho' => 'laccrim.sobre.quem_somos.blocos.3.texto', 'tipo' => 'html', 'obrigatorio' => false],
            ],
        ],

        'sobre-secoes' => [
            'rotulo' => 'Títulos das demais seções',
            'pagina' => 'Sobre Nós',
            'previa' => ['rota' => 'sobre', 'ancora' => 'corpo-social'],
            'campos' => [
                ['nome' => 'estatuto_titulo', 'rotulo' => 'Estatuto Social: título', 'caminho' => 'laccrim.sobre.estatuto.titulo', 'tipo' => 'texto'],
                ['nome' => 'corpo_social_titulo', 'rotulo' => 'Corpo Social: título', 'caminho' => 'laccrim.sobre.corpo_social.titulo', 'tipo' => 'texto'],
                ['nome' => 'professores_titulo', 'rotulo' => 'Professores Orientadores: título', 'caminho' => 'laccrim.sobre.professores.titulo', 'tipo' => 'texto'],
            ],
        ],

        'publicacoes-cabecalhos' => [
            'rotulo' => 'Cabeçalhos das listagens',
            'pagina' => 'Notícias e Editais e Atas',
            'previa' => ['rota' => 'noticias'],
            'campos' => [
                ['nome' => 'noticias_titulo', 'rotulo' => 'Notícias: título', 'caminho' => 'laccrim.publicacoes.paginas.noticias.titulo', 'tipo' => 'texto'],
                ['nome' => 'noticias_texto', 'rotulo' => 'Notícias: texto', 'caminho' => 'laccrim.publicacoes.paginas.noticias.texto', 'tipo' => 'texto_longo'],
                ['nome' => 'editais_titulo', 'rotulo' => 'Editais e Atas: título', 'caminho' => 'laccrim.publicacoes.paginas.editais.titulo', 'tipo' => 'texto'],
                ['nome' => 'editais_texto', 'rotulo' => 'Editais e Atas: texto', 'caminho' => 'laccrim.publicacoes.paginas.editais.texto', 'tipo' => 'texto_longo'],
            ],
        ],

        'site-rodape' => [
            'rotulo' => 'Identidade e rodapé',
            'pagina' => 'Todas as páginas',
            'previa' => ['rota' => 'home', 'ancora' => 'rodape'],
            'campos' => [
                ['nome' => 'descricao', 'rotulo' => 'Descrição da Liga (rodapé e buscadores)', 'caminho' => 'laccrim.identidade.descricao', 'tipo' => 'texto_longo'],
                ['nome' => 'instituicao', 'rotulo' => 'Instituição', 'caminho' => 'laccrim.rodape.instituicao', 'tipo' => 'texto'],
                ['nome' => 'endereco', 'rotulo' => 'Endereço', 'caminho' => 'laccrim.rodape.endereco', 'tipo' => 'texto', 'max' => 200],
                ['nome' => 'email', 'rotulo' => 'E-mail de contato', 'caminho' => 'laccrim.rodape.email', 'tipo' => 'email'],
                ['nome' => 'instagram', 'rotulo' => 'Endereço do Instagram (https://…)', 'caminho' => 'laccrim.rodape.instagram.url', 'tipo' => 'url', 'obrigatorio' => false],
            ],
        ],
    ],
];
