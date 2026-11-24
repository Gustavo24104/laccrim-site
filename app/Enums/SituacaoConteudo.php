<?php

namespace App\Enums;

/**
 * Situação de uma área editável do site no fluxo de aprovação do CMS.
 */
enum SituacaoConteudo: string
{
    case Publicado = 'publicado';
    case Rascunho = 'rascunho';
    case AguardandoAprovacao = 'aguardando_aprovacao';

    public function rotulo(): string
    {
        return match ($this) {
            self::Publicado => 'Publicado',
            self::Rascunho => 'Rascunho em edição',
            self::AguardandoAprovacao => 'Aguardando aprovação',
        };
    }

    public function classesSelo(): string
    {
        return match ($this) {
            self::Publicado => 'bg-emerald-100 text-emerald-900',
            self::Rascunho => 'bg-slate-200 text-slate-800',
            self::AguardandoAprovacao => 'bg-amber-100 text-amber-900',
        };
    }
}
