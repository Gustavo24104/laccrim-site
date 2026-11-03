<?php

namespace App\Http\Controllers;

use App\Services\SobreService;
use Illuminate\View\View;

class SobreController extends Controller
{
    public function __invoke(SobreService $sobreService): View
    {
        return view('paginas.sobre_nos', ['pagina' => $sobreService->handle()]);
    }
}
