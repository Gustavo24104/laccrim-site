@props(['identidade'])

<span {{ $attributes->class('flex items-center gap-3') }}>
    @if ($identidade['logotipo'])
        <span class="inline-flex size-11 shrink-0 items-center justify-center rounded-full bg-white">
            <img src="{{ $identidade['logotipo'] }}" alt="Logotipo da {{ $identidade['sigla'] }}" class="size-10" >
        </span>
    @else
        <span aria-hidden="true" class="flex size-10 shrink-0 items-center justify-center rounded-lg border-2 border-dashed border-destaque/70 text-[0.625rem] font-semibold uppercase text-destaque">
            Logo
        </span>
    @endif
    <span class="font-serif text-lg font-bold tracking-wide text-white">{{ $identidade['sigla'] }}</span>
</span>
