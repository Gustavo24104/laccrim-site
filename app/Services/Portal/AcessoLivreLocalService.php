<?php

namespace App\Services\Portal;

use App\Contracts\PerfilRepository;
use App\Contracts\UsuarioRepository;
use App\Enums\PermissaoPortal;
use App\Models\Portal\Perfil;
use App\Models\User;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

/**
 * Acesso sem login em localhost: usuário de testes com todas as permissões,
 * opcionalmente simulando um cargo ("Visualizar como").
 */
class AcessoLivreLocalService
{
    private const CHAVE_SESSAO = 'portal.visualizar_como';

    public function __construct(
        private UsuarioRepository $usuarios,
        private PerfilRepository $perfis,
    ) {}

    public function habilitado(Request $request): bool
    {
        return app()->environment('local')
            && config('portal.acesso_livre_local.habilitado') === true
            && in_array($request->ip(), config('portal.acesso_livre_local.ips'), true);
    }

    public function usuarioDeTestes(): User
    {
        return $this->usuarios->encontrarOuCriarUsuarioDeTestes(
            config('portal.acesso_livre_local.email'),
            config('portal.acesso_livre_local.nome'),
        );
    }

    public function ehUsuarioDeTestes(?User $usuario): bool
    {
        return $usuario !== null && $usuario->email === config('portal.acesso_livre_local.email');
    }

    public function concedeTodasPermissoes(User $usuario, Request $request): bool
    {
        return $this->ehUsuarioDeTestes($usuario)
            && $this->habilitado($request)
            && $request->hasSession()
            && ! $this->simulandoCargo($request->session());
    }

    /**
     * Com um cargo simulado, o usuário de testes passa a ter só as permissões desse cargo.
     */
    public function aplicarCargoSimulado(User $usuario, Session $sessao): void
    {
        $slug = $sessao->get(self::CHAVE_SESSAO);

        if ($slug !== null) {
            $usuario->setRelation('perfis', $this->perfis->buscarPorSlugComPermissoes($slug));
        }
    }

    public function simulandoCargo(Session $sessao): bool
    {
        return $sessao->has(self::CHAVE_SESSAO);
    }

    public function cargoSimulado(Session $sessao): ?string
    {
        return $sessao->get(self::CHAVE_SESSAO);
    }

    public function simularCargo(Session $sessao, ?string $slug): void
    {
        $slug === null
            ? $sessao->forget(self::CHAVE_SESSAO)
            : $sessao->put(self::CHAVE_SESSAO, $slug);
    }

    /**
     * Cargos que podem ser simulados: só os que entram no portal, para não trancar o usuário de testes fora dele.
     *
     * @return Collection<int, Perfil>
     */
    public function cargosSimulaveis(): Collection
    {
        return $this->perfis->comPermissao(PermissaoPortal::AcessarPortal);
    }
}
