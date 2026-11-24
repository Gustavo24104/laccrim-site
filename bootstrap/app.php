<?php

use App\Http\Middleware\AcessoLivreLocal;
use App\Http\Middleware\ExigirTrocaDeSenha;
use Illuminate\Contracts\Auth\Middleware\AuthenticatesRequests;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'auth', ExigirTrocaDeSenha::class, 'can:portal.acessar'])
                ->prefix('portal')
                ->name('portal.')
                ->group(base_path('routes/portal.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectUsersTo(fn () => config('fortify.home'));
        $middleware->web(append: [AcessoLivreLocal::class]);
        $middleware->prependToPriorityList(before: AuthenticatesRequests::class, prepend: AcessoLivreLocal::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
