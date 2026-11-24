<?php

namespace App\Services\Portal\Cms;

use App\Contracts\UsuarioRepository;
use App\Enums\PerfilAcesso;
use App\Enums\PermissaoPortal;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

/**
 * A Presidência aprova o conteúdo do site. Os demais cargos com a permissão de
 * aprovar (a Vice-Presidência, por padrão) só aprovam enquanto não houver
 * Presidência presente — ausência ativada nas Configurações ou cargo vago.
 */
class RegraAprovacaoConteudoService
{
    public function __construct(private UsuarioRepository $usuarios) {}

    public function podeAprovar(User $usuario): bool
    {
        if (! $usuario->possuiPermissao(PermissaoPortal::AprovarSite)) {
            return false;
        }

        return $usuario->possuiPerfil(PerfilAcesso::Presidencia) || ! $this->usuarios->existePresidenciaPresente();
    }

    /**
     * @return Collection<int, User>
     */
    public function aprovadoresExceto(User $solicitante): Collection
    {
        return $this->usuarios
            ->comPermissaoExceto(PermissaoPortal::AprovarSite, $solicitante)
            ->filter(fn (User $usuario): bool => $this->podeAprovar($usuario))
            ->values();
    }
}
