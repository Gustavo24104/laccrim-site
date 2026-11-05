<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaginaEmConstrucaoController;
use App\Http\Controllers\PublicacaoController;
use App\Http\Controllers\SobreController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/sobre-nos', SobreController::class)->name('sobre');
Route::get('/noticias', [PublicacaoController::class, 'index'])->name('noticias')->defaults('pagina', 'noticias');
Route::get('/noticias/{publicacao}', [PublicacaoController::class, 'show'])->name('noticias.show')->where('publicacao', '[a-z0-9]+(?:-[a-z0-9]+)*');
Route::get('/editais-e-atas', [PublicacaoController::class, 'index'])->name('editais')->defaults('pagina', 'editais');
Route::get('/editais-e-atas/{publicacao}', [PublicacaoController::class, 'show'])->name('editais.show')->where('publicacao', '[a-z0-9]+(?:-[a-z0-9]+)*');
Route::get('/eventos', PaginaEmConstrucaoController::class)->name('eventos')->defaults('titulo', 'Eventos');
Route::get('/protocolo-virtual', PaginaEmConstrucaoController::class)->name('protocolo')->defaults('titulo', 'Protocolo Virtual');
Route::get('/portal', PaginaEmConstrucaoController::class)->name('portal')->defaults('titulo', 'Portal da Liga');
Route::get('/politica-de-privacidade', PaginaEmConstrucaoController::class)->name('privacidade')->defaults('titulo', 'Política de Privacidade');
Route::get('/termos-de-uso', PaginaEmConstrucaoController::class)->name('termos')->defaults('titulo', 'Termos de Uso');

Route::post('/tema', function (Illuminate\Http\Request $request) {
    $tema = $request->string('tema')->value() === 'escuro' ? 'escuro':'clarfo';
    return response()->noContent()->cookie('tema', $tema, 60*24*365);
})->name('tema');
