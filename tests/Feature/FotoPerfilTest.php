<?php

use App\Enums\PerfilAcesso;
use App\Models\User;
use Database\Seeders\PerfilSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->seed(PerfilSeeder::class);
    Storage::fake('public');
    $this->ligante = User::factory()->comPerfil(PerfilAcesso::Ligante)->create();
});

function fotoPng(string $nome = 'foto.png'): UploadedFile
{
    return UploadedFile::fake()->createWithContent(
        $nome,
        base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNkYAAAAAYAAjCB0C8AAAAASUVORK5CYII='),
    );
}

test('o usuário envia a foto de perfil e ela aparece no portal', function () {
    $this->actingAs($this->ligante)
        ->put(route('portal.configuracoes.foto'), ['foto' => fotoPng()])
        ->assertRedirect(route('portal.configuracoes'))
        ->assertSessionHasNoErrors();

    $caminho = $this->ligante->fresh()->foto_perfil;

    Storage::disk('public')->assertExists($caminho);

    $this->actingAs($this->ligante->fresh())
        ->get(route('portal.dashboard'))
        ->assertSee(Storage::disk('public')->url($caminho), false);
});

test('trocar a foto apaga a anterior', function () {
    $this->actingAs($this->ligante)->put(route('portal.configuracoes.foto'), ['foto' => fotoPng('primeira.png')]);
    $primeira = $this->ligante->fresh()->foto_perfil;

    $this->actingAs($this->ligante->fresh())->put(route('portal.configuracoes.foto'), ['foto' => fotoPng('segunda.png')]);

    Storage::disk('public')->assertMissing($primeira);
    Storage::disk('public')->assertExists($this->ligante->fresh()->foto_perfil);
});

test('arquivo que não é imagem é recusado mesmo com extensão de imagem', function () {
    $caminho = tempnam(sys_get_temp_dir(), 'foto');
    file_put_contents($caminho, "%PDF-1.4\n%conteúdo de um PDF disfarçado de imagem");

    $this->actingAs($this->ligante)
        ->put(route('portal.configuracoes.foto'), [
            'foto' => new UploadedFile($caminho, 'foto.png', null, null, true),
        ])
        ->assertSessionHasErrors('foto');

    expect($this->ligante->fresh()->foto_perfil)->toBeNull();
});
