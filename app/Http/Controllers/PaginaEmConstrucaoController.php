<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

/**
 * Página provisória das abas públicas que ainda serão implementadas.
 */
class PaginaEmConstrucaoController extends Controller
{
    public function __invoke(string $titulo): View
    {
        return view('paginas.em-construcao', ['titulo' => $titulo]);
    }
}
