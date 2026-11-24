@props(['titulo', 'descricao' => null])

<div class="flex flex-col gap-1">
    <h1 class="text-2xl font-bold text-titulo sm:text-3xl">{{ $titulo }}</h1>
    @if ($descricao)
        <p class="text-sm text-texto-suave">{{ $descricao }}</p>
    @endif
</div>
