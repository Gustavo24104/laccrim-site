@props(['usuario'])

@if ($usuario->urlFotoPerfil())
    <img src="{{ $usuario->urlFotoPerfil() }}" alt="Foto de perfil de {{ $usuario->name }}" {{ $attributes->class('shrink-0 rounded-full object-cover') }}>
@else
    <span aria-hidden="true" {{ $attributes->class('inline-flex shrink-0 items-center justify-center rounded-full bg-destaque font-semibold text-primaria') }}>
        {{ $usuario->iniciais() }}
    </span>
@endif
