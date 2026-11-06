<?php

/*
|--------------------------------------------------------------------------
| Conteúdo do site público da LACCRIM/UFU
|--------------------------------------------------------------------------
|
| Fonte provisória do conteúdo editável. Será substituída pelo CMS através
| das implementações de App\Contracts\ConteudoSiteRepository e
| App\Contracts\ConteudoDinamicoRepository. Datas em UTC (ISO 8601).
|
*/

return [

    'fuso_exibicao' => 'America/Sao_Paulo',

    'identidade' => [
        'sigla' => 'LACCRIM/UFU',
        'descricao' => 'Liga acadêmica dedicada ao estudo crítico das Ciências Criminais na Faculdade de Direito da Universidade Federal de Uberlândia.',
        'logotipo' => '/images/logo_laccrim.svg',
    ],

    /*
     | Sobrescreve as cores padrão do tema (resources/css/app.css).
     | Apenas valores hexadecimais são aplicados. Ex.: 'primaria' => '#0A192F'.
     */
    'cores' => [],

    'menu' => [
        ['rotulo' => 'Página Inicial', 'rota' => 'home'], 
        ['rotulo' => 'Sobre Nós', 'rota' => 'sobre'],
        ['rotulo' => 'Notícias', 'rota' => 'noticias'],
        ['rotulo' => 'Editais e Atas', 'rota' => 'editais'],
        ['rotulo' => 'Eventos', 'rota' => 'eventos'],
        ['rotulo' => 'Protocolo Virtual', 'rota' => 'protocolo'],
    ],

    'menu_destaque' => ['rotulo' => 'Portal da Liga', 'rota' => 'portal'],

    'rodape' => [
        'instituicao' => 'Faculdade de Direito "Prof. Jacy de Assis" – FADIR/UFU',
        'endereco' => 'Av. João Naves de Ávila, 2121, Campus Santa Mônica, Bloco 3D – Uberlândia/MG',
        'email' => 'laccrim@fadir.ufu.br',
        'instagram' => ['rotulo' => 'Instagram', 'url' => null],
        'parceiros' => [
            ['nome' => 'NUAVIDAS', 'logotipo' => null],
            ['nome' => 'Projeto Livre', 'logotipo' => null],
            ['nome' => 'PROEXC', 'logotipo' => null],
            ['nome' => 'UFMT', 'logotipo' => null],
            ['nome' => 'UFMA', 'logotipo' => null],
            ['nome' => 'FBDG', 'logotipo' => null],
        ],
        'links_legais' => [
            ['rotulo' => 'Política de Privacidade', 'rota' => 'privacidade'],
            ['rotulo' => 'Termos de Uso', 'rota' => 'termos'],
        ],
    ],

    'home' => [

        'secoes' => [
            ['chave' => 'hero', 'visivel' => true, 'ordem' => 1],
            ['chave' => 'pilares', 'visivel' => true, 'ordem' => 2],
            ['chave' => 'noticias', 'visivel' => true, 'ordem' => 3],
            ['chave' => 'eventos', 'visivel' => true, 'ordem' => 4],
            ['chave' => 'publicacoes', 'visivel' => true, 'ordem' => 5],
            ['chave' => 'protocolo', 'visivel' => true, 'ordem' => 6],
            ['chave' => 'integrantes', 'visivel' => true, 'ordem' => 7],
            ['chave' => 'cta', 'visivel' => true, 'ordem' => 8],
        ],

        'hero' => [
            'identificacao' => 'Universidade Federal de Uberlândia',
            'titulo' => 'O estudo crítico das Ciências Criminais a serviço da justiça.',
            'destaque' => 'Ciências Criminais',
            'texto' => 'Reunimos estudantes e professores da FADIR/UFU para aprofundar, com rigor acadêmico e compromisso social, o Direito Penal, o Direito Processual Penal e a Criminologia.',
            'botoes' => [
                ['rotulo' => 'Conheça nossa Liga', 'rota' => 'sobre', 'variante' => 'primario'],
                ['rotulo' => 'Protocolo Virtual', 'rota' => 'protocolo', 'variante' => 'contorno-claro'],
            ],
        ],

        'pilares' => [
            'titulo' => 'Três pilares, um propósito',
            'subtitulo' => 'Nossa atuação integra ensino, pesquisa e extensão para formar juristas críticos e comprometidos com a sociedade.',
            'itens' => [
                [
                    'icone' => 'livro',
                    'titulo' => 'Ensino',
                    'descricao' => 'Grupos de estudo, cursos e minicursos que aprofundam a formação em Ciências Criminais.',
                ],
                [
                    'icone' => 'lupa',
                    'titulo' => 'Pesquisa',
                    'descricao' => 'Produção científica orientada, com análise crítica da dogmática penal e da política criminal.',
                ],
                [
                    'icone' => 'pessoas',
                    'titulo' => 'Extensão',
                    'descricao' => 'Ações junto à comunidade que aproximam o conhecimento acadêmico da realidade social.',
                ],
            ],
        ],

        'noticias' => [
            'titulo' => 'Últimas notícias e editais',
            'subtitulo' => 'Acompanhe as publicações aprovadas pela Liga.',
            'limite' => 3,
            'botao' => ['rotulo' => 'Ver todas as publicações', 'rota' => 'noticias', 'consulta' => ['categoria' => 'todas']],
            'vazio' => 'Nenhuma publicação disponível no momento.',
        ],

        'eventos' => [
            'titulo' => 'Próximos eventos',
            'subtitulo' => 'Palestras, seminários e encontros abertos à comunidade.',
            'limite' => 3,
            'botao' => ['rotulo' => 'Ver todos os eventos', 'rota' => 'eventos'],
            'vazio' => 'Nenhum evento agendado no momento.',
        ],

        'publicacoes' => [
            'titulo' => 'Publicações em destaque',
            'subtitulo' => 'Artigos, resumos e produções dos integrantes da Liga.',
            'limite' => 3,
            'vazio' => 'Nenhuma publicação em destaque no momento.',
        ],

        'protocolo' => [
            'titulo' => 'Protocolo Virtual',
            'texto' => 'Envie requerimentos, solicitações e documentos à LACCRIM de forma simples e acompanhe o andamento online.',
            'botao' => ['rotulo' => 'Acessar o Protocolo Virtual', 'rota' => 'protocolo'],
        ],

        'integrantes' => [
            'titulo' => 'Quem faz a LACCRIM',
            'texto' => 'Conheça o Corpo Social e os professores orientadores que conduzem as atividades da Liga.',
            'botao' => ['rotulo' => 'Conheça os integrantes', 'rota' => 'sobre', 'ancora' => 'corpo-social'],
        ],

        'cta' => [
            'titulo' => 'Participe da LACCRIM',
            'texto' => 'Acompanhe nossos editais de seleção e faça parte de uma liga comprometida com o estudo crítico das Ciências Criminais.',
            'botao' => ['rotulo' => 'Ver editais abertos', 'rota' => 'editais'],
        ],
    ],

    'sobre' => [
        'banner' => [
            'titulo' => 'Sobre a LACCRIM',
            'texto' => 'A LACCRIM é uma liga acadêmica e entidade estudantil vinculada à Faculdade de Direito "Prof. Jacy de Assis" (FADIR), da Universidade Federal de Uberlândia. Reúne estudantes e professores para o estudo crítico das Ciências Criminais.',
        ],
        'quem_somos' => [
            'titulo' => 'Quem somos',
            'blocos' => [
                ['chave' => 'historia', 'titulo' => 'História', 'texto' => ''],
                ['chave' => 'missao', 'titulo' => 'Missão', 'texto' => ''],
                ['chave' => 'objetivos', 'titulo' => 'Objetivos', 'texto' => ''],
                ['chave' => 'principios', 'titulo' => 'Princípios', 'texto' => ''],
            ],
        ],
        'estatuto' => [
            'titulo' => 'Estatuto Social',
            'arquivo' => null,
        ],
        'corpo_social' => [
            'titulo' => 'Corpo Social',
        ],
        'professores' => [
            'titulo' => 'Professores Orientadores',
        ],
    ],

    /*
     | Categorias e textos da listagem pública. O CMS do portal substitui esta
     | fonte; o site só lê o que o repositório devolver. A chave é estável.
     | rotulo aparece no cartão e rotulo_filtro no filtro.
     */
    'publicacoes' => [
        'por_pagina' => 9,
        'categorias' => [
            ['chave' => 'edital', 'rotulo' => 'Edital', 'rotulo_filtro' => 'Editais', 'ordem' => 1],
            ['chave' => 'ata', 'rotulo' => 'Ata', 'rotulo_filtro' => 'Atas', 'ordem' => 2],
            ['chave' => 'noticia', 'rotulo' => 'Notícia', 'rotulo_filtro' => 'Notícias', 'ordem' => 3],
        ],
        'grupos' => [
            'editais-e-atas' => ['edital', 'ata'],
        ],
        'paginas' => [
            'noticias' => [
                'titulo' => 'Notícias',
                'texto' => 'Publicações aprovadas e publicadas pela Liga.',
            ],
            'editais' => [
                'titulo' => 'Editais e Atas',
                'texto' => 'Editais e atas aprovados e publicados pela Liga.',
            ],
        ],
    ],

    /*
     | Registros do CMS. Notícias, editais, atas, eventos e publicações em
     | destaque começam vazios: o portal grava os itens depois da aprovação.
     | Uma publicação só aparece com situacao aprovado e publicado verdadeiro.
     | Eventos usam inicio em UTC e inscricoes abertas, encerradas ou esgotadas.
     | Destaques usam tipo, titulo, autor e publicado_em em UTC.
     */
    'exemplos' => [

        'publicacoes' => [],
        'eventos' => [],
        'destaques' => [],

        /*
         | Pessoas publicadas na página Sobre Nós. O CMS do portal grava estes
         | registros depois da aprovação. membro_portal_id liga o integrante ao
         | cadastro do portal; exibir_publicamente é a escolha do próprio membro.
         | Fotos em 1080×1350 px (proporção 4:5). hierarquia: presidencia,
         | vice_presidencia, diretoria, coordenacao ou membro.
         */
        'corpo_social' => [],
        'professores' => [],
    ],
];
