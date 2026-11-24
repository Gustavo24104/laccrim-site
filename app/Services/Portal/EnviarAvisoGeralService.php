<?php

namespace App\Services\Portal;

use App\Contracts\UsuarioRepository;
use App\Enums\PermissaoPortal;
use App\Models\User;
use App\Notifications\AvisoGeralNotification;
use Illuminate\Support\Facades\Notification;

/**
 * Envia um aviso geral a todos os membros com acesso ao portal (exceto quem envia).
 */
class EnviarAvisoGeralService
{
    public function __construct(private UsuarioRepository $usuarios) {}

    /**
     * @return int Quantidade de membros notificados.
     */
    public function handle(User $remetente, string $titulo, string $mensagem): int
    {
        $destinatarios = $this->usuarios->comPermissaoExceto(PermissaoPortal::AcessarPortal, $remetente);

        Notification::send($destinatarios, new AvisoGeralNotification($titulo, $mensagem, $remetente->name));

        return $destinatarios->count();
    }
}
