<?php

namespace App\Enums;

/**
 * Tipos de notificação do portal. O valor é gravado nos dados da notificação.
 */
enum TipoNotificacao: string
{
    case Aviso = 'aviso';
    case TarefaAtribuida = 'tarefa_atribuida';
    case PrazoProximo = 'prazo_proximo';
    case PrazoVencido = 'prazo_vencido';
    case AprovacaoPendente = 'aprovacao_pendente';
    case ConteudoPublicado = 'conteudo_publicado';
    case ConteudoDevolvido = 'conteudo_devolvido';

    public function rotulo(): string
    {
        return match ($this) {
            self::Aviso => 'Aviso',
            self::TarefaAtribuida => 'Nova tarefa',
            self::PrazoProximo => 'Prazo próximo',
            self::PrazoVencido => 'Prazo vencido',
            self::AprovacaoPendente => 'Aprovação pendente',
            self::ConteudoPublicado => 'Conteúdo publicado',
            self::ConteudoDevolvido => 'Conteúdo devolvido',
        };
    }

    public function classesSelo(): string
    {
        return match ($this) {
            self::Aviso => 'bg-primaria text-white dark:bg-primaria-claro',
            self::TarefaAtribuida, self::AprovacaoPendente => 'bg-destaque text-primaria',
            self::PrazoProximo, self::ConteudoDevolvido => 'bg-amber-100 text-amber-900',
            self::PrazoVencido => 'bg-red-600 text-white',
            self::ConteudoPublicado => 'bg-emerald-100 text-emerald-900',
        };
    }
}
