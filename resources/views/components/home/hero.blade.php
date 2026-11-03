@props(['dados'])

<section aria-labelledby="hero-titulo" class="relative isolate overflow-hidden bg-primaria text-white">
    <div aria-hidden="true" class="absolute -right-40 -top-40 -z-10 size-[36rem] rounded-full bg-primaria-claro/70 blur-3xl"></div>
    <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-1 bg-linear-to-r from-destaque via-destaque/40 to-transparent"></div>

    <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 md:py-28 lg:px-8">
        <div class="flex max-w-3xl flex-col gap-6">
            <p class="text-xs font-semibold uppercase tracking-[0.25em] text-destaque sm:text-sm">{{ $dados['identificacao'] }}</p>

            <h1 id="hero-titulo" class="text-4xl font-bold leading-tight sm:text-5xl lg:text-6xl">
                {{ $dados['titulo_partes']['antes'] }}<span class="text-destaque">{{ $dados['titulo_partes']['destaque'] }}</span>{{ $dados['titulo_partes']['depois'] }}
            </h1>

            <p class="max-w-2xl text-lg leading-relaxed text-white/80">{{ $dados['texto'] }}</p>

            <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                @foreach ($dados['botoes'] as $botao)
                    <x-ui.button :href="$botao['href']" :variante="$botao['variante'] ?? 'primario'">{{ $botao['rotulo'] }}</x-ui.button>
                @endforeach
            </div>
        </div>
    </div>
</section>
