@props(['usuario'])

<section aria-labelledby="hero-usuario-nome" class="flex items-center gap-5 rounded-xl bg-primaria p-6 text-white shadow-sm sm:gap-6 sm:p-8">
    <x-portal.avatar :usuario="$usuario" class="size-20 text-2xl ring-4 ring-destaque sm:size-24 sm:text-3xl" />

    <div class="flex min-w-0 flex-col gap-1">
        <h1 id="hero-usuario-nome" class="truncate text-2xl font-bold sm:text-3xl">{{ $usuario->name }}</h1>
        <p class="text-sm text-white/60">ID UFU: {{ $usuario->id_ufu ?? 'não informado' }}</p>
    </div>
</section>
