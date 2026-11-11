<x-layout :titulo="$titulo">
    <section aria-labelledby="pagina-titulo" class="bg-primaria text-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 md:py-20 lg:px-8">
            <h1 id="dashboard-principal" class="text-4xl font-bold md:text-5xl">{{ $titulo }}</h1>
        </div>
    </section>

    <section class="bg-fundo py-20">
        <div class="mx-auto flex max-w-3xl flex-col items-center gap-6 px-4 text-center sm:px-6">
            <x-ui.empty-state>Você esta logado!</x-ui.empty-state>
            <x-ui.button :href="route('home')" variante="secundario">Voltar à Página Inicial</x-ui.button>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                    <div> 
                        <button type="submit" class="bg-destaque text-primaria hover:bg-destaque-claro border 
                        border-primaria inline-flex items-center justify-center 
                        gap-2 rounded-lg px-6 py-3 text-sm font-semibold transition-colors">
                            Sair da conta
                        </button>
                    </div>
                {{-- <x-ui.button type="submit"> Sair da conta </x-ui.button> --}}
            </form>
        </div>
    </section>
</x-layout>