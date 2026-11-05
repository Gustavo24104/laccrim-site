@props(['dados'])

<section aria-labelledby="eventos-titulo" class="bg-fundo-alt py-20">
    <div class="mx-auto flex max-w-7xl flex-col gap-10 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
            <x-ui.section-heading id="eventos-titulo" :titulo="$dados['titulo']" :subtitulo="$dados['subtitulo'] ?? null" />
            <x-ui.button :href="$dados['botao']['href']" variante="contorno" class="w-fit shrink-0">{{ $dados['botao']['rotulo'] }}</x-ui.button>
        </div>

        @if ($dados['itens'] === [])
            <x-ui.empty-state>{{ $dados['vazio'] }}</x-ui.empty-state>
        @else
            <ul class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($dados['itens'] as $evento)
                    <li>
                        <x-ui.card class="h-full">
                            <div class="flex flex-wrap items-center gap-2">
                                <x-ui.badge variante="primaria">{{ $evento['categoria'] }}</x-ui.badge>
                                <x-ui.badge :variante="$evento['inscricoes'] === 'abertas' ? 'destaque' : 'neutro'">{{ $evento['situacao'] }}</x-ui.badge>
                            </div>
                            <h3 class="text-xl font-bold leading-snug text-titulo">{{ $evento['titulo'] }}</h3>
                            <p class="flex-1 leading-relaxed text-texto-suave">{{ $evento['resumo'] }}</p>
                            <dl class="flex flex-col gap-2 border-t border-slate-100 pt-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <dt><x-ui.icone nome="calendario" class="size-5 text-destaque" /><span class="sr-only">Data e hora</span></dt>
                                    <dd><time datetime="{{ $evento['data_iso'] }}">{{ $evento['data_formatada'] }} às {{ $evento['hora_formatada'] }}</time></dd>
                                </div>
                                <div class="flex items-center gap-2">
                                    <dt><x-ui.icone nome="local" class="size-5 text-destaque" /><span class="sr-only">Local ou modalidade</span></dt>
                                    <dd>{{ $evento['local'] }}</dd>
                                </div>
                            </dl>
                            <a href="{{ $dados['botao']['href'] }}" class="inline-flex w-fit items-center gap-1 text-sm font-semibold text-titulo hover:text-destaque">
                                Ver detalhes<span class="sr-only">: {{ $evento['titulo'] }}</span>
                                <x-ui.icone nome="seta" class="size-4" />
                            </a>
                        </x-ui.card>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
