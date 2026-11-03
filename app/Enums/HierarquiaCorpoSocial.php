<?php

namespace App\Enums;

/**
 * Ordem e identificação visual do Corpo Social, conforme o Estatuto.
 * A chave é o valor estável que o CMS do portal deve gravar.
 */
enum HierarquiaCorpoSocial: string
{
    case Presidencia = 'presidencia';
    case VicePresidencia = 'vice_presidencia';
    case DiretoriaMasculino = 'diretoria_masculino';
    case DiretoriaFeminino = 'diretoria_feminino';
    case CoordenacaoMasculino = 'coordenacao_masculino';
    case CoordenacaoFeminino = 'coordenacao_feminino';
    case Membro = 'membro';

    public function rotulo(): string
    {
        return match ($this) {
            self::Presidencia => 'Presidente',
            self::VicePresidencia => 'Vice-Presidente',
            self::DiretoriaMasculino => 'Diretor',
            self::DiretoriaFeminino => 'Diretora',
            self::CoordenacaoMasculino => 'Coordenador',
            self::CoordenacaoFeminino => 'Coordenadora',
            self::Membro => 'Membro',
        };
    }

    public function cor(): string
    {
        return match ($this) {
            self::Presidencia, self::VicePresidencia => '#007bff',
            self::DiretoriaMasculino, self::DiretoriaFeminino => '#bf2d00',
            self::CoordenacaoMasculino, self::CoordenacaoFeminino => '#0bd900',
            self::Membro => '#68728f',
        };
    }

    public function ordem(): int
    {
        return match ($this) {
            self::Presidencia => 1,
            self::VicePresidencia => 2,
            self::DiretoriaMasculino, self::DiretoriaFeminino => 3,
            self::CoordenacaoMasculino, self::CoordenacaoFeminino => 4,
            self::Membro => 5,
        };
    }
}
