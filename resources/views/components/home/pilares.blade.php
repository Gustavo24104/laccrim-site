@props(['dados'])

<section aria-labelledby="pilares-titulo" class="bg-fundo-alt py-20">
    <div class="mx-auto flex max-w-7xl flex-col gap-12 px-4 sm:px-6 lg:px-8">
        <x-ui.section-heading id="pilares-titulo" :titulo="$dados['titulo']" :subtitulo="$dados['subtitulo'] ?? null" centralizado />

        <ul class="grid gap-6 md:grid-cols-3">
            @foreach ($dados['itens'] as $pilar)
                <li>
                    <x-ui.card class="h-full border-t-4 border-t-destaque">
                        <span class="flex size-12 items-center justify-center rounded-full bg-primaria-claro text-destaque dark:bg-(--color-circulo-dark) dark:text-[#0a192f]">
                            <x-ui.icone :nome="$pilar['icone']" />
                        </span>
                        <h3 class="text-xl font-bold text-titulo">{{ $pilar['titulo'] }}</h3>
                        <p class="leading-relaxed text-texto-suave">{{ $pilar['descricao'] }}</p>
                    </x-ui.card>
                </li>
            @endforeach
        </ul>
    </div>
</section>
