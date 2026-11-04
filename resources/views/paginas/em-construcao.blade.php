<x-layout :titulo="$titulo">
    <section aria-labelledby="pagina-titulo" class="bg-primaria text-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 md:py-20 lg:px-8">
            <h1 id="pagina-titulo" class="text-4xl font-bold md:text-5xl">{{ $titulo }}</h1>
        </div>
    </section>

    <section class="bg-fundo py-20">
        <div class="mx-auto flex max-w-3xl flex-col items-center gap-6 px-4 text-center sm:px-6">
            <x-ui.empty-state>Esta página está em construção e em breve trará conteúdo atualizado.</x-ui.empty-state>
            <x-ui.button :href="route('home')" variante="secundario">Voltar à Página Inicial</x-ui.button>
        </div>
    </section>
</x-layout>
