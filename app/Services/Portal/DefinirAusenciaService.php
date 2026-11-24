<?php

namespace App\Services\Portal;

use App\Contracts\UsuarioRepository;
use App\Enums\PerfilAcesso;
use App\Enums\PermissaoPortal;
use App\Models\User;
use App\Notifications\AvisoGeralNotification;
use Illuminate\Support\Facades\Notification;

/**
 * A Presidência marca ou desmarca ausência. Enquanto ausente, quem também pode
 * aprovar o conteúdo do site (a Vice-Presidência, por padrão) passa a aprovar.
 */
class DefinirAusenciaService
{
    public function __construct(private UsuarioRepository $usuarios) {}

    public function handle(User $presidencia, bool $ausente): void
    {
        if ($presidencia->ausente === $ausente) {
            return;
        }

        $this->usuarios->definirAusencia($presidencia, $ausente);

        $substitutos = $this->usuarios
            ->comPermissaoExceto(PermissaoPortal::AprovarSite, $presidencia)
            ->reject(fn (User $usuario): bool => $usuario->possuiPerfil(PerfilAcesso::Presidencia));

        Notification::send($substitutos, new AvisoGeralNotification(
            $ausente ? 'Presidência ausente' : 'Presidência de volta',
            $ausente
                ? 'Enquanto durar a ausência, você pode aprovar as alterações do site.'
                : 'As alterações do site voltam a ser aprovadas pela Presidência.',
            $presidencia->name,
        ));
    }
}
