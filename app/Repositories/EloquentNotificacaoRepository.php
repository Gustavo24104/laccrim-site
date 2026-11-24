<?php

namespace App\Repositories;

use App\Contracts\NotificacaoRepository;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;

class EloquentNotificacaoRepository implements NotificacaoRepository
{
    public function recentes(User $usuario, int $limite): Collection
    {
        return $usuario->notifications()->limit($limite)->get();
    }

    public function totalNaoLidas(User $usuario): int
    {
        return $usuario->unreadNotifications()->count();
    }

    public function buscarDoUsuario(User $usuario, string $id): DatabaseNotification
    {
        return $usuario->notifications()->whereKey($id)->firstOrFail();
    }

    public function marcarComoLida(DatabaseNotification $notificacao): void
    {
        $notificacao->markAsRead();
    }

    public function marcarTodasComoLidas(User $usuario): void
    {
        $usuario->unreadNotifications()->update(['read_at' => now()]);
    }
}
