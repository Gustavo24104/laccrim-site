@props(['dados'])

<section aria-labelledby="sobre-titulo" class="relative isolate overflow-hidden bg-primaria text-white">
    <div aria-hidden="true" class="absolute -top-40 -right-40 -z-10 size-[36rem] rounded-full bg-primaria-claro/70 blur-3xl"></div>
    <div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-1 bg-linear-to-r from-destaque via-destaque/40 to-transparent"></div>

    <div class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-20 sm:px-6 md:py-28 lg:px-8">
        <h1 id="sobre-titulo" class="max-w-3xl font-serif text-4xl font-bold leading-tight sm:text-5xl">{{ $dados['titulo'] }}</h1>
        <p class="max-w-3xl text-lg leading-relaxed text-white/80">{{ $dados['texto'] }}</p>
    </div>
</section>
