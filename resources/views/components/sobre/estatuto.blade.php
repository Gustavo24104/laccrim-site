@props(['dados'])

<section aria-labelledby="estatuto-titulo" class="bg-fundo-alt py-20">
    <div class="mx-auto flex max-w-7xl flex-col gap-10 px-4 sm:px-6 lg:px-8">
        <x-ui.section-heading id="estatuto-titulo" :titulo="$dados['titulo']" />

        <div class="flex flex-col gap-6">
            @if ($dados['arquivo'])
                <x-ui.leitor-pdf
                    :arquivo="$dados['arquivo']"
                    titulo="Estatuto Social"
                    rotulo-ativar="Ativar visualização avançada do estatuto"
                    rotulo-zoom="Zoom do estatuto"
                />

                <div>
                    <x-ui.button :href="$dados['arquivo']" download="estatuto-social.pdf" variante="secundario" class="w-fit">
                        <x-ui.icone nome="download" class="size-5" />
                        Baixar estatuto
                    </x-ui.button>
                </div>
            @else
                <div class="h-80 rounded-lg border border-slate-200 bg-white sm:h-[32rem] lg:h-[36rem]" role="region" aria-label="Estatuto Social">
                    <p class="sr-only">Nenhum arquivo do estatuto foi publicado.</p>
                </div>

                <div>
                    <x-ui.button variante="secundario" desabilitado class="w-fit" aria-label="Baixar estatuto, arquivo ainda não publicado">
                        <x-ui.icone nome="download" class="size-5" />
                        Baixar estatuto
                    </x-ui.button>
                </div>
            @endif
        </div>
    </div>
</section>
