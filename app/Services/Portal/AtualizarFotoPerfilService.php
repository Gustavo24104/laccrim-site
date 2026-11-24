<?php

namespace App\Services\Portal;

use App\Contracts\UsuarioRepository;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Guarda a nova foto de perfil (a mesma exibida em "Quem Somos") e descarta a anterior.
 */
class AtualizarFotoPerfilService
{
    private const DISCO = 'public';

    private const PASTA = 'fotos-perfil';

    public function __construct(private UsuarioRepository $usuarios) {}

    public function handle(User $usuario, UploadedFile $foto): void
    {
        $fotoAnterior = $usuario->foto_perfil;

        $this->usuarios->definirFotoPerfil($usuario, $foto->store(self::PASTA, self::DISCO));

        if ($fotoAnterior !== null) {
            Storage::disk(self::DISCO)->delete($fotoAnterior);
        }
    }
}
