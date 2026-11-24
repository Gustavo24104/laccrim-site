<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\PrimeiroAcessoRequest;
use App\Services\Portal\TrocarSenhaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PrimeiroAcessoController extends Controller
{
    public function edit(): View
    {
        return view('paginas.portal.primeiro-acesso', ['titulo' => 'Primeiro acesso']);
    }

    public function update(PrimeiroAcessoRequest $request, TrocarSenhaService $trocarSenha): RedirectResponse
    {
        $trocarSenha->handle($request->user(), $request->validated('password'));

        return redirect(config('fortify.home'))->with('status', 'Senha definida com sucesso.');
    }
}
