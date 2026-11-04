@props(['dados'])

<section aria-labelledby="protocolo-titulo" class="bg-fundo pb-20">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-8 rounded-2xl bg-primaria p-8 text-white shadow-lg md:flex-row md:items-center md:justify-between md:p-12">
            <div class="flex items-start gap-5">
                <span class="flex size-14 shrink-0 items-center justify-center rounded-full border border-destaque/60 text-destaque">
                    <x-ui.icone nome="envio" class="size-7" />
                </span>
                <div class="flex max-w-2xl flex-col gap-2">
                    <h2 id="protocolo-titulo" class="text-2xl font-bold md:text-3xl">{{ $dados['titulo'] }}</h2>
                    <p class="leading-relaxed text-white/80">{{ $dados['texto'] }}</p>
                </div>
            </div>
            <x-ui.button :href="$dados['botao']['href']" class="w-fit shrink-0">{{ $dados['botao']['rotulo'] }}</x-ui.button>
        </div>
    </div>
</section>
