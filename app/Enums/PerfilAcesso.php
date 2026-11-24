<?php

namespace App\Enums;

/**
 * Slugs estáveis dos perfis de acesso (especificação, seção 3.2).
 * O vínculo entre perfis e permissões vive no banco; este enum só identifica os perfis padrão.
 */
enum PerfilAcesso: string
{
    case AdministradorSistema = 'administrador_sistema';
    case Presidencia = 'presidencia';
    case VicePresidencia = 'vice_presidencia';
    case DiretoriaComunicacao = 'diretoria_comunicacao';
    case DiretoriaPesquisa = 'diretoria_pesquisa';
    case DiretoriaExtensao = 'diretoria_extensao';
    case DiretoriaFinanceira = 'diretoria_financeira';
    case CoordenacaoPesquisa = 'coordenacao_pesquisa';
    case CoordenacaoEventos = 'coordenacao_eventos';
    case CoordenacaoSecretaria = 'coordenacao_secretaria';
    case CoordenacaoMidias = 'coordenacao_midias';
    case CoordenacaoIntegracao = 'coordenacao_integracao';
    case Ligante = 'ligante';
    case Consulta = 'consulta';
    case Visitante = 'visitante';

    public function rotulo(): string
    {
        return match ($this) {
            self::AdministradorSistema => 'Administrador do Site (TI)',
            self::Presidencia => 'Presidência',
            self::VicePresidencia => 'Vice-Presidência',
            self::DiretoriaComunicacao => 'Diretor de Comunicação',
            self::DiretoriaPesquisa => 'Diretor de Pesquisa',
            self::DiretoriaExtensao => 'Diretor de Extensão',
            self::DiretoriaFinanceira => 'Diretor Financeiro',
            self::CoordenacaoPesquisa => 'Coordenação de Pesquisa',
            self::CoordenacaoEventos => 'Coordenação de Eventos',
            self::CoordenacaoSecretaria => 'Coordenação de Secretaria',
            self::CoordenacaoMidias => 'Coordenação de Mídias',
            self::CoordenacaoIntegracao => 'Coordenação de Integração',
            self::Ligante => 'Ligante (membro efetivo)',
            self::Consulta => 'Consulta (professores e conselho consultivo)',
            self::Visitante => 'Visitante',
        };
    }
}
