<?php


use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaginaEmConstrucaoController;
use App\Http\Controllers\Portal\DashboardController;
use App\Http\Controllers\Portal\PortalController;
use App\Http\Controllers\PublicacaoController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\SobreController;
use Illuminate\Support\Facades\Route;
use Illuminate\View\View;


Route::get('/', PortalController::class)->name('portal')->defaults('titulo', 'Portal da Liga')->middleware('guest');
Route::get('/dashboard', DashboardController::class)->middleware('auth');
Route::get('/dois-fatores', function () {
    return view('paginas.portal.dois_fatores', ['titulo' => 'Configs']);
});