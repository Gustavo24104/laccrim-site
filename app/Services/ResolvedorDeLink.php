<?php

namespace App\Services;

use Illuminate\Support\Facades\Route;

/**
 * Converte links configuráveis (nome de rota ou URL livre do CMS) em href.
 */
class ResolvedorDeLink
{
    /**
     * @param  array{rota?: string, url?: string, ancora?: string, consulta?: array<string, scalar>}  $link
     */
    public function href(array $link): string
    {
        $href = match (true) {
            isset($link['rota']) && Route::has($link['rota']) => route($link['rota']),
            isset($link['url']) => $link['url'],
            default => '#',
        };

        if (isset($link['consulta']) && is_array($link['consulta'])) {
            $parametros = [];

            foreach ($link['consulta'] as $chave => $valor) {
                if (is_string($chave) && is_scalar($valor) && $valor !== '') {
                    $parametros[$chave] = $valor;
                }
            }

            if ($parametros !== []) {
                $href .= (str_contains($href, '?') ? '&' : '?').http_build_query($parametros);
            }
        }

        return isset($link['ancora']) ? "{$href}#{$link['ancora']}" : $href;
    }

    /**
     * @param  array{rota?: string, url?: string}  $link
     */
    public function estaAtivo(array $link): bool
    {
        if (isset($link['rota'])) {
            return request()->routeIs($link['rota'], $link['rota'].'.show');
        }

        return isset($link['url']) && url()->current() === url($link['url']);
    }
}
