<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;

/**
 * Leitura e marcação das notificações do portal de um usuário.
 */
interface NotificacaoRepository
{
    /**
     * @return Collection<int, DatabaseNotification>
     */
    public function recentes(User $usuario, int $limite): Collection;

    public function totalNaoLidas(User $usuario): int;

    public function buscarDoUsuario(User $usuario, string $id): DatabaseNotification;

    public function marcarComoLida(DatabaseNotification $notificacao): void;

    public function marcarTodasComoLidas(User $usuario): void;
}
