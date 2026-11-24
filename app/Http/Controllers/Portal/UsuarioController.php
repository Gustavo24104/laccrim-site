<?php

namespace App\Http\Controllers\Portal;

use App\Contracts\PerfilRepository;
use App\Contracts\UsuarioRepository;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\CriarUsuarioRequest;
use App\Services\Portal\CriarUsuarioService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UsuarioController extends Controller
{
    public function create(PerfilRepository $perfis): View
    {
        return view('paginas.portal.usuarios.criar', [
            'titulo' => 'Novo usuário',
            'perfis' => $perfis->todos(),
        ]);
    }

    public function store(CriarUsuarioRequest $request, CriarUsuarioService $criarUsuario): RedirectResponse
    {
        $usuario = $criarUsuario->handle($request->validated());

        return redirect()->route('portal.usuarios.create')
            ->with('status', "Conta de {$usuario->name} criada. A senha provisória foi enviada para {$usuario->email}.");
    }

    public function index(UsuarioRepository $usuarios, PerfilRepository $perfis): View
    {
        return view('paginas.portal.usuarios.index', [
            'titulo' => 'Usuários',
            'usuarios' => $usuarios->listarComPerfis(),
            'perfis' => $perfis->todos(),
        ]);
    }
}
