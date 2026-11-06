@props(['dados'])

<section aria-labelledby="noticias-titulo" class="bg-fundo py-20">
    <div class="mx-auto flex max-w-7xl flex-col gap-10 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <x-ui.section-heading id="noticias-titulo" :titulo="$dados['titulo']" :subtitulo="$dados['subtitulo'] ?? null" />
            <x-ui.button :href="$dados['botao']['href']" variante="contorno" class="w-fit shrink-0">{{ $dados['botao']['rotulo'] }}</x-ui.button>
        </div>

        @if ($dados['itens'] === [])
            <x-ui.empty-state>{{ $dados['vazio'] }}</x-ui.empty-state>
        @else
            <ul class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($dados['itens'] as $publicacao)
                    <li>
                        <x-ui.card class="h-full">
                            <div class="flex items-center justify-between gap-3">
                                <x-ui.badge>{{ $publicacao['categoria'] }}</x-ui.badge>
                                <time datetime="{{ $publicacao['data_iso'] }}" class="text-sm text-texto-suave">{{ $publicacao['data_formatada'] }}</time>
                            </div>
                            <h3 class="text-xl font-bold leading-snug text-titulo">{{ $publicacao['titulo'] }}</h3>
                            <p class="flex-1 leading-relaxed text-texto-suave">{{ $publicacao['resumo'] }}</p>
                            <div class="flex items-center justify-between gap-3 border-t border-slate-100 pt-4 text-sm">
                                <span class="text-texto-suave">{{ $publicacao['autor'] }}</span>
                                <a href="{{ $publicacao['href'] ?? $dados['botao']['href'] }}" class="inline-flex items-center gap-1 font-semibold text-tiutlo hover:text-destaque">
                                    Visualizar<span class="sr-only">: {{ $publicacao['titulo'] }}</span>
                                    <x-ui.icone nome="seta" class="size-4" />
                                </a>
                            </div>
                        </x-ui.card>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
