<?php

use App\Enums\PermissaoPortal;
use App\Http\Controllers\Portal\AusenciaController;
use App\Http\Controllers\Portal\AvisoController;
use App\Http\Controllers\Portal\CargoUsuarioController;
use App\Http\Controllers\Portal\ConfiguracoesController;
use App\Http\Controllers\Portal\ConteudoController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\DevolverConteudoController;
use App\Http\Controllers\Portal\FotoPerfilController;
use App\Http\Controllers\Portal\NotificacaoController;
use App\Http\Controllers\Portal\PortalController;
use App\Http\Controllers\Portal\PrimeiroAcessoController;
use App\Http\Controllers\Portal\RestaurarVersaoConteudoController;
use App\Http\Controllers\Portal\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::get('/', PortalController::class)->name('portal')->defaults('titulo', 'Portal da Liga')->middleware('guest');
Route::get('/dashboard', DashboardController::class)->name('dashboard');
Route::get('/dois-fatores', function () {
    return view('paginas.portal.dois_fatores', ['titulo' => 'Configs']);
});

Route::get('/configuracoes', ConfiguracoesController::class)->name('configuracoes');
Route::put('/configuracoes/foto', [FotoPerfilController::class, 'update'])->name('configuracoes.foto');
Route::put('/configuracoes/ausencia', AusenciaController::class)->name('configuracoes.ausencia');

Route::middleware('can:'.PermissaoPortal::EditarSite->value)->group(function () {
    Route::put('/conteudo/{area}', [ConteudoController::class, 'update'])->name('conteudo.update');
    Route::post('/conteudo/{area}/versoes/{versao}/restaurar', RestaurarVersaoConteudoController::class)->name('conteudo.versoes.restaurar')->whereNumber('versao');
    Route::get('/conteudo/{area}', [ConteudoController::class, 'edit'])->name('conteudo.edit');
    Route::get('/conteudo', [ConteudoController::class, 'index'])->name('conteudo.index');
});

Route::post('/conteudo/{area}/devolver', DevolverConteudoController::class)->name('conteudo.devolver')->middleware('can:'.PermissaoPortal::AprovarSite->value);

Route::post('/notificacoes/lidas', [NotificacaoController::class, 'marcarTodasComoLidas'])->name('notificacoes.lidas');
Route::post('/notificacoes/{notificacao}/lida', [NotificacaoController::class, 'marcarComoLida'])->name('notificacoes.lida')->whereUuid('notificacao');

Route::middleware('can:'.PermissaoPortal::EnviarAvisos->value)->group(function () {
    Route::post('/avisos', [AvisoController::class, 'store'])->name('avisos.store');
    Route::get('/avisos/criar', [AvisoController::class, 'create'])->name('avisos.create');
});

Route::get('/primeiro-acesso', [PrimeiroAcessoController::class, 'edit'])->name('primeiro-acesso.edit');
Route::put('/primeiro-acesso', [PrimeiroAcessoController::class, 'update'])->name('primeiro-acesso.update');

Route::middleware('can:'.PermissaoPortal::CriarUsuarios->value)->group(function () {
    Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/criar', [UsuarioController::class, 'create'])->name('usuarios.create');
});

Route::middleware('can:'.PermissaoPortal::AlterarCargos->value)->group(function () {
    Route::put('/usuarios/{usuario}/cargo', [CargoUsuarioController::class, 'update'])->name('usuarios.cargo.update');
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
});
