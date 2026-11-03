<?php

namespace App\Http\Controllers;

use App\Services\PublicacaoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicacaoController extends Controller
{
    public function index(Request $request, PublicacaoService $publicacoes, string $pagina): View
    {
        return view('paginas.publicacoes.index', [
            'listagem' => $publicacoes->listar(
                $pagina,
                $this->textoDaConsulta($request, 'categoria'),
                $this->textoDaConsulta($request, 'q'),
                $request->url(),
                $request->query(),
            ),
        ]);
    }

    public function show(PublicacaoService $publicacoes, string $publicacao): View
    {
        $item = $publicacoes->encontrar($publicacao);

        abort_if($item === null, 404);

        return view('paginas.publicacoes.show', ['publicacao' => $item]);
    }

    private function textoDaConsulta(Request $request, string $chave): string
    {
        $valor = $request->query($chave);

        return is_string($valor) ? $valor : '';
    }
}
