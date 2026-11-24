<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\AlterarCargoRequest;
use App\Models\User;
use App\Services\Portal\AlterarCargoService;
use Illuminate\Http\RedirectResponse;

class CargoUsuarioController extends Controller
{
    public function update(AlterarCargoRequest $request, User $usuario, AlterarCargoService $alterarCargo): RedirectResponse
    {
        $alterarCargo->handle($usuario, $request->integer('perfil_id'));

        return redirect()->route('portal.usuarios.index')
            ->with('status', "Cargo de {$usuario->name} atualizado.");
    }
}
