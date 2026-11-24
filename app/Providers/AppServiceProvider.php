<?php

namespace App\Providers;

use App\Contracts\ConteudoDinamicoRepository;
use App\Contracts\ConteudoEditavelRepository;
use App\Contracts\ConteudoSiteRepository;
use App\Contracts\NotificacaoRepository;
use App\Contracts\PerfilRepository;
use App\Contracts\SanitizadorHtml;
use App\Contracts\UsuarioRepository;
use App\Enums\PermissaoPortal;
use App\Models\User;
use App\Repositories\CmsConteudoDinamicoRepository;
use App\Repositories\CmsConteudoSiteRepository;
use App\Repositories\ConfigConteudoDinamicoRepository;
use App\Repositories\ConfigConteudoSiteRepository;
use App\Repositories\EloquentConteudoEditavelRepository;
use App\Repositories\EloquentNotificacaoRepository;
use App\Repositories\EloquentPerfilRepository;
use App\Repositories\EloquentUsuarioRepository;
use App\Services\Portal\AcessoLivreLocalService;
use App\Services\Portal\Cms\AreaConteudo;
use App\Services\Portal\Cms\CatalogoAreasConteudo;
use App\Services\Portal\Cms\RegraAprovacaoConteudoService;
use App\Services\SymfonySanitizadorHtml;
use App\View\Composers\LayoutComposer;
use App\View\Composers\NotificacoesComposer;
use App\View\Composers\PortalLayoutComposer;
use App\View\Composers\VisualizarComoComposer;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ConteudoSiteRepository::class, CmsConteudoSiteRepository::class);
        $this->app->when(CmsConteudoSiteRepository::class)->needs(ConteudoSiteRepository::class)->give(ConfigConteudoSiteRepository::class);
        $this->app->bind(ConteudoDinamicoRepository::class, CmsConteudoDinamicoRepository::class);
        $this->app->when(CmsConteudoDinamicoRepository::class)->needs(ConteudoDinamicoRepository::class)->give(ConfigConteudoDinamicoRepository::class);
        $this->app->bind(ConteudoEditavelRepository::class, EloquentConteudoEditavelRepository::class);
        $this->app->bind(SanitizadorHtml::class, SymfonySanitizadorHtml::class);
        $this->app->bind(UsuarioRepository::class, EloquentUsuarioRepository::class);
        $this->app->bind(PerfilRepository::class, EloquentPerfilRepository::class);
        $this->app->bind(NotificacaoRepository::class, EloquentNotificacaoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer(['components.layout', 'components.portal.layout'], LayoutComposer::class);
        View::composer('components.portal.layout', PortalLayoutComposer::class);
        View::composer('components.portal.visualizar-como', VisualizarComoComposer::class);
        View::composer('components.portal.notificacoes', NotificacoesComposer::class);

        Gate::before(function (User $usuario): ?bool {
            return app(AcessoLivreLocalService::class)->concedeTodasPermissoes($usuario, request()) ? true : null;
        });

        foreach (PermissaoPortal::cases() as $permissao) {
            Gate::define($permissao->value, fn (User $usuario): bool => $usuario->possuiPermissao($permissao));
        }

        Gate::define(
            PermissaoPortal::AprovarSite->value,
            fn (User $usuario): bool => app(RegraAprovacaoConteudoService::class)->podeAprovar($usuario),
        );

        Route::bind('area', fn (string $chave): AreaConteudo => app(CatalogoAreasConteudo::class)->buscar($chave) ?? abort(404));
    }
}
