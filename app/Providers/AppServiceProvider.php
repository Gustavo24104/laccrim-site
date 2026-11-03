<?php

namespace App\Providers;

use App\Contracts\ConteudoDinamicoRepository;
use App\Contracts\ConteudoSiteRepository;
use App\Repositories\ConfigConteudoDinamicoRepository;
use App\Repositories\ConfigConteudoSiteRepository;
use App\View\Composers\LayoutComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ConteudoSiteRepository::class, ConfigConteudoSiteRepository::class);
        $this->app->bind(ConteudoDinamicoRepository::class, ConfigConteudoDinamicoRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.layout', LayoutComposer::class);
    }
}
