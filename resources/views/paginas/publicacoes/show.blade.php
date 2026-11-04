<x-layout :titulo="$publicacao['titulo']" :descricao="$publicacao['resumo']">
    <section aria-labelledby="pagina-titulo" class="bg-primaria text-white">
        <div class="mx-auto flex max-w-5xl flex-col gap-6 px-4 py-16 sm:px-6 md:py-20 lg:px-8">
            <a href="{{ $publicacao['voltar']['href'] }}" class="w-fit text-sm font-semibold text-white/80 hover:text-white">
                Voltar para {{ $publicacao['voltar']['rotulo'] }}
            </a>
            <p class="text-sm font-semibold uppercase tracking-wide text-destaque">{{ $publicacao['categoria'] }}</p>
            <h1 id="pagina-titulo" class="max-w-3xl font-serif text-4xl font-bold leading-tight sm:text-5xl">{{ $publicacao['titulo'] }}</h1>
            <p class="text-white/80">
                <time datetime="{{ $publicacao['data_iso'] }}">{{ $publicacao['data_formatada'] }}</time>
                @if ($publicacao['autor'] !== '')
                    · {{ $publicacao['autor'] }}
                @endif
            </p>
        </div>
    </section>

    <article class="bg-fundo py-16">
        <div class="mx-auto flex max-w-5xl flex-col gap-10 px-4 sm:px-6 lg:px-8">
            @if ($publicacao['resumo'] !== '')
                <p class="max-w-3xl text-lg leading-relaxed text-texto">{{ $publicacao['resumo'] }}</p>
            @endif

            @if ($publicacao['conteudo'] !== '')
                <div class="max-w-3xl leading-relaxed whitespace-pre-line text-texto">{{ $publicacao['conteudo'] }}</div>
            @endif

            @if ($publicacao['pdf'])
                <x-ui.leitor-pdf
                    :arquivo="$publicacao['pdf']"
                    :titulo="$publicacao['titulo']"
                    rotulo-ativar="Ativar visualização avançada do PDF"
                    rotulo-zoom="Zoom do PDF"
                />
            @endif

            @if ($publicacao['anexos'] !== [])
                <section aria-labelledby="anexos-titulo" class="flex flex-col gap-4">
                    <h2 id="anexos-titulo" class="font-serif text-2xl font-bold text-primaria">Arquivos anexos</h2>
                    <ul class="flex flex-col gap-3">
                        @foreach ($publicacao['anexos'] as $anexo)
                            <li>
                                <a href="{{ $anexo['href'] }}" download="{{ $anexo['nome'] }}" class="inline-flex items-center gap-2 font-semibold text-primaria hover:text-destaque">
                                    <x-ui.icone nome="download" class="size-5" />
                                    {{ $anexo['nome'] }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>
    </article>
</x-layout>
