<?php

namespace App\Notifications;

use App\Enums\TipoNotificacao;
use Illuminate\Notifications\Notification;

class AvisoGeralNotification extends Notification
{
    public function __construct(
        public readonly string $titulo,
        public readonly string $mensagem,
        public readonly string $remetente,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array{tipo: string, titulo: string, mensagem: string, remetente: string, url: null}
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => TipoNotificacao::Aviso->value,
            'titulo' => $this->titulo,
            'mensagem' => $this->mensagem,
            'remetente' => $this->remetente,
            'url' => null,
        ];
    }
}
