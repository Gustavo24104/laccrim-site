<x-portal.layout :titulo="$titulo">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <x-portal.titulo-pagina titulo="Usuários" descricao="Membros com acesso ao portal e seus cargos." />

        @can(\App\Enums\PermissaoPortal::CriarUsuarios->value)
            <x-ui.button :href="route('portal.usuarios.create')">Novo usuário</x-ui.button>
        @endcan
    </div>

    @if (session('status'))
        <p class="text-sm text-texto" role="status">{{ session('status') }}</p>
    @endif

    <x-ui.card class="w-full overflow-x-auto text-left dark:bg-primaria">
        <table class="w-full text-sm text-texto">
            <thead>
                <tr class="border-b border-linha text-left text-titulo">
                    <th scope="col" class="py-3 pr-4 font-semibold">Nome</th>
                    <th scope="col" class="py-3 pr-4 font-semibold">ID UFU</th>
                    <th scope="col" class="py-3 font-semibold">Cargo</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr class="border-b border-linha last:border-0">
                        <td class="py-3 pr-4">
                            <span class="block font-semibold text-titulo">{{ $usuario->name }}</span>
                            <span class="block text-xs">{{ $usuario->email }}</span>
                        </td>
                        <td class="py-3 pr-4">{{ $usuario->id_ufu ?? '—' }}</td>
                        <td class="py-3">
                            @if ($usuario->is(auth()->user()))
                                {{ $usuario->perfis->pluck('nome')->join(', ') ?: '—' }}
                            @else
                                <form method="post" action="{{ route('portal.usuarios.cargo.update', $usuario) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('put')
                                    <label for="perfil-{{ $usuario->id }}" class="sr-only">Cargo de {{ $usuario->name }}</label>
                                    <select id="perfil-{{ $usuario->id }}" name="perfil_id" class="rounded-lg border border-linha bg-[#e4ebf2] px-2 py-1 text-texto dark:bg-linha">
                                        @foreach ($perfis as $perfil)
                                            <option value="{{ $perfil->id }}" @selected($usuario->perfis->contains($perfil))>{{ $perfil->nome }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="rounded-lg border border-primaria bg-destaque px-3 py-1 text-xs font-semibold text-primaria hover:bg-destaque-claro">Salvar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @error('perfil_id')
            <span class="text-sm text-red-600">{{ $message }}</span>
        @enderror
    </x-ui.card>
</x-portal.layout>
