<x-portal.layout :titulo="$titulo">
    <x-portal.titulo-pagina titulo="Enviar aviso geral" descricao="O aviso aparece nas notificações de todos os membros com acesso ao portal." />

    <x-ui.card class="w-full max-w-2xl text-left dark:bg-primaria">
        @if (session('status'))
            <p class="text-sm text-texto" role="status">{{ session('status') }}</p>
        @endif

        <form method="post" action="{{ route('portal.avisos.store') }}" class="flex flex-col gap-8">
            @csrf

            <x-ui.campo nome="titulo" rotulo="Título" required maxlength="120" />

            <div class="flex flex-col gap-3">
                <label for="mensagem" class="text-sm font-semibold text-titulo">Mensagem</label>
                <textarea id="mensagem" name="mensagem" rows="6" required maxlength="2000" class="w-full rounded-lg border border-linha bg-[#e4ebf2] px-3 py-2 text-texto dark:bg-linha">{{ old('mensagem') }}</textarea>
                @error('mensagem')
                    <span class="text-sm text-red-600">{{ $message }}</span>
                @enderror
            </div>

            <div class="flex items-center justify-start gap-6">
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-primaria bg-destaque px-6 py-3 text-sm font-semibold text-primaria transition-colors hover:bg-destaque-claro">
                    Enviar aviso
                </button>
            </div>
        </form>
    </x-ui.card>
</x-portal.layout>
