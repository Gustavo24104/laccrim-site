<?php

namespace App\Services\Portal;

use App\Contracts\PerfilRepository;
use App\Contracts\UsuarioRepository;
use App\Models\User;
use App\Notifications\SenhaInicialNotification;
use Illuminate\Support\Str;

/**
 * Cria a conta de um ligante com senha aleatória e envia a senha por e-mail.
 * Não há cadastro público: só o ADM TI ou a Presidência chegam aqui.
 */
class CriarUsuarioService
{
    public function __construct(
        private UsuarioRepository $usuarios,
        private PerfilRepository $perfis,
    ) {}

    /**
     * @param  array{name: string, email: string, id_ufu: string, data_nascimento: string, perfil_id: int|string}  $dados
     */
    public function handle(array $dados): User
    {
        $senhaInicial = Str::password(12, symbols: false);

        $usuario = $this->usuarios->criarComPerfil([
            'name' => $dados['name'],
            'email' => $dados['email'],
            'id_ufu' => $dados['id_ufu'],
            'data_nascimento' => $dados['data_nascimento'],
            'password' => $senhaInicial,
            'deve_trocar_senha' => true,
        ], $this->perfis->buscarPorId((int) $dados['perfil_id']));

        $usuario->notify(new SenhaInicialNotification($senhaInicial));

        return $usuario;
    }
}
