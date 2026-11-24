<x-portal.layout :titulo="$titulo">
    <x-portal.titulo-pagina titulo="Criar conta de ligante" descricao="Uma senha provisória será gerada e enviada ao e-mail informado." />

    <x-ui.card class="w-full max-w-2xl text-left dark:bg-primaria">
        @if (session('status'))
            <p class="text-sm text-texto" role="status">{{ session('status') }}</p>
        @endif

        <form method="post" action="{{ route('portal.usuarios.store') }}" class="flex flex-col gap-8">
            @csrf

            <x-ui.campo nome="name" rotulo="Nome completo" required autocomplete="off" />
            <x-ui.campo nome="email" rotulo="E-mail" tipo="email" required autocomplete="off" />
            <x-ui.campo nome="data_nascimento" rotulo="Data de nascimento" tipo="date" required />
            <x-ui.campo nome="id_ufu" rotulo="ID UFU" required />

            <div class="flex flex-col gap-3">
                <label for="perfil_id" class="text-sm font-semibold text-titulo">Cargo</label>
                <select id="perfil_id" name="perfil_id" required class="w-full rounded-lg border border-linha bg-[#e4ebf2] px-3 py-2 text-texto dark:bg-linha">
                    <option value="" disabled @selected(! old('perfil_id'))>Selecione o cargo</option>
                    @foreach ($perfis as $perfil)
                        <option value="{{ $perfil->id }}" @selected((int) old('perfil_id') === $perfil->id)>{{ $perfil->nome }}</option>
                    @endforeach
                </select>
                @error('perfil_id')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-start gap-6">
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-primaria bg-destaque px-6 py-3 text-sm font-semibold text-primaria transition-colors hover:bg-destaque-claro">
                    Criar conta
                </button>
            </div>
        </form>
    </x-ui.card>
</x-portal.layout>
