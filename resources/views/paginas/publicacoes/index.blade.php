<x-layout :titulo="$listagem['titulo']" :descricao="$listagem['texto']">
    <section aria-labelledby="pagina-titulo" class="bg-primaria text-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-16 sm:px-6 md:py-20 lg:px-8">
            <h1 id="pagina-titulo" class="max-w-3xl font-serif text-4xl font-bold leading-tight sm:text-5xl">{{ $listagem['titulo'] }}</h1>
            <p class="max-w-3xl text-lg leading-relaxed text-white/80">{{ $listagem['texto'] }}</p>
        </div>
    </section>

    <section class="bg-fundo py-16">
        <div class="mx-auto flex max-w-7xl flex-col gap-10 px-4 sm:px-6 lg:px-8">
            <nav aria-label="Filtrar publicações">
                <ul class="flex flex-wrap gap-2">
                    @foreach ($listagem['filtros'] as $filtro)
                        <li>
                            <a
                                href="{{ $filtro['href'] }}"
                                @if ($filtro['ativo']) aria-current="true" @endif
                                @class([
                                    'inline-flex rounded-full px-4 py-2 text-sm font-semibold transition-colors',
                                    'bg-primaria text-white dark:bg-destaque! dark:text-fundo!' => $filtro['ativo'],
                                    'bg-superficie text-titulo ring-1 ring-slate-200 hover:bg-fundo-alt' => ! $filtro['ativo'],
                                ])
                            >{{ $filtro['rotulo'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <form method="get" action="{{ $listagem['acao'] }}" role="search" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="flex flex-1 flex-col gap-2">
                    <label for="busca-publicacoes" class="text-sm font-semibold text-texto">Buscar por título, palavra-chave ou conteúdo</label>
                    <input
                        id="busca-publicacoes"
                        type="search"
                        name="q"
                        maxlength="120"
                        value="{{ $listagem['busca'] }}"
                        class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-sm text-texto"
                    >
                </div>
                @if ($listagem['categoria_consulta'])
                    <input type="hidden" name="categoria" value="{{ $listagem['categoria_consulta'] }}">
                @endif
                <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primaria px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-primaria-claro dark:bg-destaque! dark:text-fundo! dark:hover:bg-destaque-claro!">
                    Buscar
                </button>
            </form>

            @if ($listagem['itens'] === [])
                <x-ui.empty-state>{{ $listagem['vazio'] }}</x-ui.empty-state>
            @else
                <ul class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($listagem['itens'] as $publicacao)
                        <li>
                            <x-ui.card class="h-full">
                                <div class="flex items-center justify-between gap-3">
                                    <x-ui.badge>{{ $publicacao['categoria'] }}</x-ui.badge>
                                    <time datetime="{{ $publicacao['data_iso'] }}" class="text-sm text-texto-suave">{{ $publicacao['data_formatada'] }}</time>
                                </div>
                                <h2 class="text-xl font-bold leading-snug text-titulo">{{ $publicacao['titulo'] }}</h2>
                                <p class="flex-1 leading-relaxed text-texto-suave">{{ $publicacao['resumo'] }}</p>
                                <div class="flex flex-wrap items-center justify-between gap-3 border-t border-slate-100 pt-4 text-sm">
                                    <span class="text-texto-suave">{{ $publicacao['autor'] }}</span>
                                    <div class="flex items-center gap-4">
                                        @if ($publicacao['download'])
                                            <a href="{{ $publicacao['download']['href'] }}" download="{{ $publicacao['download']['nome'] }}" class="inline-flex items-center gap-1 font-semibold text-titulo hover:text-destaque">
                                                <x-ui.icone nome="download" class="size-4" />
                                                {{ $publicacao['download']['rotulo'] }}
                                            </a>
                                        @endif
                                        <a href="{{ $publicacao['href'] }}" class="inline-flex items-center gap-1 font-semibold text-titulo hover:text-destaque">
                                            Visualizar<span class="sr-only">: {{ $publicacao['titulo'] }}</span>
                                            <x-ui.icone nome="seta" class="size-4" />
                                        </a>
                                    </div>
                                </div>
                            </x-ui.card>
                        </li>
                    @endforeach
                </ul>
            @endif

            <x-ui.paginacao :paginacao="$listagem['paginacao']" />
        </div>
    </section>
</x-layout>
