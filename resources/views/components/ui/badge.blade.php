@props(['variante' => 'destaque'])

<span {{ $attributes->class([
    'inline-flex w-fit items-center rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide',
    match ($variante) {
        'neutro' => 'bg-slate-100 text-texto',
        'primaria' => 'bg-primaria text-white',
        default => 'bg-destaque/15 text-titulo ring-1 ring-destaque/50',
    },
]) }}>{{ $slot }}</span>
