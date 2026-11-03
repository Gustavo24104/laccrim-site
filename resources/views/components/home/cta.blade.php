@props(['dados'])

<section aria-labelledby="cta-titulo" class="border-t-4 border-destaque bg-primaria-claro py-20 text-white">
    <div class="mx-auto flex max-w-3xl flex-col items-center gap-6 px-4 text-center sm:px-6">
        <h2 id="cta-titulo" class="text-3xl font-bold md:text-4xl">{{ $dados['titulo'] }}</h2>
        <p class="text-lg leading-relaxed text-white/80">{{ $dados['texto'] }}</p>
        <x-ui.button :href="$dados['botao']['href']">{{ $dados['botao']['rotulo'] }}</x-ui.button>
    </div>
</section>
