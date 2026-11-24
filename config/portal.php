<?php

use App\Enums\PermissaoPortal;

return [

    /*
    |--------------------------------------------------------------------------
    | Menu lateral do Portal
    |--------------------------------------------------------------------------
    |
    | Cada item só aparece para quem possui a permissão indicada (null = todos
    | que acessam o portal). A mesma permissão protege a rota no servidor.
    |
    */

    'menu' => [
        ['rotulo' => 'Início', 'rota' => 'portal.dashboard', 'icone' => 'casa', 'permissao' => null],
        ['rotulo' => 'Usuários', 'rota' => 'portal.usuarios.index', 'icone' => 'pessoas', 'permissao' => PermissaoPortal::AlterarCargos->value],
        ['rotulo' => 'Novo usuário', 'rota' => 'portal.usuarios.create', 'icone' => 'usuario-mais', 'permissao' => PermissaoPortal::CriarUsuarios->value],
        ['rotulo' => 'Conteúdo do site', 'rota' => 'portal.conteudo.index', 'ativo_em' => 'portal.conteudo.*', 'icone' => 'documento', 'permissao' => PermissaoPortal::EditarSite->value],
        ['rotulo' => 'Enviar aviso', 'rota' => 'portal.avisos.create', 'icone' => 'envio', 'permissao' => PermissaoPortal::EnviarAvisos->value],
        ['rotulo' => 'Configurações', 'rota' => 'portal.configuracoes', 'icone' => 'engrenagem', 'permissao' => null],
    ],

    /*
    |--------------------------------------------------------------------------
    | Acesso livre em localhost
    |--------------------------------------------------------------------------
    |
    | Entra no portal sem login, com todas as permissões, para testes e
    | visualização de páginas. Só funciona com APP_ENV=local e requisições
    | vindas da própria máquina; em qualquer outro ambiente é ignorado.
    |
    */

    'acesso_livre_local' => [
        'habilitado' => (bool) env('PORTAL_ACESSO_LIVRE', false),
        'ips' => ['127.0.0.1', '::1'],
        'email' => 'desenvolvedor@localhost',
        'nome' => 'Desenvolvedor Local',
    ],

];
