@props(['dados'])

<section aria-labelledby="quem-somos-titulo" class="bg-fundo py-20">
    <div class="mx-auto flex max-w-7xl flex-col gap-12 px-4 sm:px-6 lg:px-8">
        <x-ui.section-heading id="quem-somos-titulo" :titulo="$dados['titulo']" />

        <div class="grid gap-x-12 gap-y-10 md:grid-cols-2">
            @foreach ($dados['blocos'] as $bloco)
                <section @if (preg_match('/^[a-z0-9-]+$/', (string) $bloco['chave']) === 1) id="{{ $bloco['chave'] }}" @endif class="flex flex-col gap-4">
                    <h3 class="font-serif text-2xl font-semibold text-titulo">{{ $bloco['titulo'] }}</h3>
                    <div class="min-h-16">
                        @if (($bloco['texto'] ?? '') !== '')
                            <p class="font-sans text-base leading-relaxed whitespace-pre-line text-slate-500 normal-case">{{ $bloco['texto'] }}</p>
                        @endif
                    </div>
                </section>
            @endforeach
        </div>
    </div>
</section>
