@props(['dados'])

<section aria-labelledby="integrantes-titulo" class="bg-fundo-alt py-20">
    <div class="mx-auto flex max-w-7xl flex-col gap-8 px-4 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8">
        <div class="flex items-start gap-5">
            <span class="flex size-14 shrink-0 items-center justify-center rounded-full bg-primaria text-destaque dark:bg-(--color-circulo-dark) dark:text-[#0a192f]">
                <x-ui.icone nome="pessoas" class="size-7" />
            </span>
            <x-ui.section-heading id="integrantes-titulo" :titulo="$dados['titulo']" :subtitulo="$dados['texto']" />
        </div>
        <x-ui.button :href="$dados['botao']['href']" variante="secundario" class="w-fit shrink-0 dark:bg-destaque dark:text-fundo dark:hover:bg-destaque-claro!">{{ $dados['botao']['rotulo'] }}</x-ui.button>
    </div>
</section>
