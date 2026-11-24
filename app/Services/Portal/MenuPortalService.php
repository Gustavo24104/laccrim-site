<?php

namespace App\Services\Portal;

use App\Models\User;

/**
 * Monta o menu lateral do portal com apenas os itens que o usuário pode acessar.
 */
class MenuPortalService
{
    /**
     * @return list<array{rotulo: string, href: string, icone: string, ativo: bool}>
     */
    public function itensPara(User $usuario): array
    {
        return collect(config('portal.menu', []))
            ->filter(fn (array $item): bool => $item['permissao'] === null || $usuario->can($item['permissao']))
            ->map(fn (array $item): array => [
                'rotulo' => $item['rotulo'],
                'href' => route($item['rota']),
                'icone' => $item['icone'],
                'ativo' => request()->routeIs($item['ativo_em'] ?? $item['rota']),
            ])
            ->values()
            ->all();
    }
}
