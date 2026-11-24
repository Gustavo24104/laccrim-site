<?php

namespace App\Enums;

/**
 * Permissões verificadas pelo código. Cada caso é registrado como Gate com o próprio valor.
 */
enum PermissaoPortal: string
{
    case AcessarPortal = 'portal.acessar';
    case CriarUsuarios = 'usuarios.criar';
    case AlterarCargos = 'usuarios.alterar_cargo';
    case EditarSite = 'cms.editar';
    case AprovarSite = 'cms.aprovar';
    case ConfigurarSistema = 'sistema.configurar';
    case EnviarAvisos = 'avisos.enviar';

    public function rotulo(): string
    {
        return match ($this) {
            self::AcessarPortal => 'Acessar o Portal da Liga',
            self::CriarUsuarios => 'Criar contas de usuários',
            self::AlterarCargos => 'Alterar cargos de usuários',
            self::EditarSite => 'Editar conteúdo do site (CMS)',
            self::AprovarSite => 'Aprovar alterações do site (CMS)',
            self::ConfigurarSistema => 'Configurações técnicas do sistema',
            self::EnviarAvisos => 'Enviar avisos gerais aos membros',
        };
    }
}
