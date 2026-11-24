<?php

namespace App\Notifications;

use App\Enums\TipoNotificacao;
use Illuminate\Notifications\Notification;

class ConteudoAguardandoAprovacaoNotification extends Notification
{
    public function __construct(
        public readonly string $area,
        public readonly string $solicitante,
        public readonly string $url,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array{tipo: string, titulo: string, mensagem: string, remetente: string, url: string}
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => TipoNotificacao::AprovacaoPendente->value,
            'titulo' => "Alteração no site: {$this->area}",
            'mensagem' => "{$this->solicitante} pediu aprovação para publicar esta alteração.",
            'remetente' => $this->solicitante,
            'url' => $this->url,
        ];
    }
}
