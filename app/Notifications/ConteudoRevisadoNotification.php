<?php

namespace App\Notifications;

use App\Enums\TipoNotificacao;
use Illuminate\Notifications\Notification;

class ConteudoRevisadoNotification extends Notification
{
    public function __construct(
        public readonly string $area,
        public readonly bool $aprovado,
        public readonly string $revisor,
        public readonly ?string $motivo,
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
        $mensagem = $this->aprovado
            ? "{$this->revisor} aprovou e publicou a alteração."
            : "{$this->revisor} devolveu a alteração para ajustes.";

        if (! $this->aprovado && filled($this->motivo)) {
            $mensagem .= " Motivo: {$this->motivo}";
        }

        return [
            'tipo' => ($this->aprovado ? TipoNotificacao::ConteudoPublicado : TipoNotificacao::ConteudoDevolvido)->value,
            'titulo' => $this->area,
            'mensagem' => $mensagem,
            'remetente' => $this->revisor,
            'url' => $this->url,
        ];
    }
}
