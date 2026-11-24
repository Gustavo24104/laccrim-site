<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SenhaInicialNotification extends Notification
{
    public function __construct(public readonly string $senhaInicial) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Seu acesso ao Portal da Liga - LACCRIM')
            ->greeting("Olá, {$notifiable->name}!")
            ->line('Sua conta no Portal da Liga foi criada.')
            ->line("Sua senha provisória é: **{$this->senhaInicial}**")
            ->line('No primeiro acesso, você deverá definir uma nova senha.')
            ->action('Acessar o Portal', route('login'));
    }
}
