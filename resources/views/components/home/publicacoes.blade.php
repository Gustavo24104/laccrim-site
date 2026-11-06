@props(['dados'])

<section aria-labelledby="publicacoes-titulo" class="bg-fundo py-20">
    <div class="mx-auto flex max-w-7xl flex-col gap-10 px-4 sm:px-6 lg:px-8">
        <x-ui.section-heading id="publicacoes-titulo" :titulo="$dados['titulo']" :subtitulo="$dados['subtitulo'] ?? null" />

        @if ($dados['itens'] === [])
            <x-ui.empty-state>{{ $dados['vazio'] }}</x-ui.empty-state>
        @else
            <ul class="grid gap-4 lg:grid-cols-3">
                @foreach ($dados['itens'] as $publicacao)
                    <li>
                        <x-ui.card class="h-full flex-row items-start">
                            <span class="flex size-11 shrink-0 items-center justify-center rounded-lg bg-destaque/15 text-titulo>
                                <x-ui.icone nome="documento" />
                            </span>
                            <div class="flex flex-col gap-2">
                                <span class="text-xs font-semibold uppercase tracking-wide text-texto-suave">{{ $publicacao['tipo'] }}</span>
                                <h3 class="text-lg font-bold leading-snug text-titulo">{{ $publicacao['titulo'] }}</h3>
                                <p class="text-sm text-texto-suave">
                                    {{ $publicacao['autor'] }} · <time datetime="{{ $publicacao['data_iso'] }}">{{ $publicacao['data_formatada'] }}</time>
                                </p>
                            </div>
                        </x-ui.card>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</section>
