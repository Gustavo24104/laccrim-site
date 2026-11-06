@props(['titulo', 'subtitulo' => null, 'id' => null, 'centralizado' => false, 'claro' => false])

<div {{ $attributes->class(['flex max-w-2xl flex-col gap-3', 'mx-auto items-center text-center' => $centralizado]) }}>
    <span aria-hidden="true" class="h-1 w-12 rounded-full bg-destaque"></span>
    <h2 @if ($id) id="{{ $id }}" @endif @class(['text-3xl font-bold md:text-4xl', 'text-white' => $claro, 'text-titulo' => ! $claro])>
        {{ $titulo }}
    </h2>
    @if ($subtitulo)
        <p @class(['text-base md:text-lg', 'text-white/80' => $claro, 'text-texto-suave' => ! $claro])>{{ $subtitulo }}</p>
    @endif
</div>
