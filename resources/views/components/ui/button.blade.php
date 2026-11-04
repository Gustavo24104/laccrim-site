@props(['href' => null, 'variante' => 'primario', 'desabilitado' => false])

@php
    $classesVariante = match ($variante) {
        'secundario' => 'bg-primaria text-white hover:bg-primaria-claro',
        'contorno' => 'border border-primaria text-primaria hover:bg-primaria hover:text-white',
        'contorno-claro' => 'border border-white/70 text-white hover:bg-white/10',
        default => 'bg-destaque text-primaria hover:bg-destaque-claro',
    };
    $classes = [
        'inline-flex items-center justify-center gap-2 rounded-lg px-6 py-3 text-sm font-semibold transition-colors',
        $classesVariante,
        'cursor-not-allowed opacity-60' => $desabilitado,
    ];
@endphp

@if ($desabilitado)
    <button type="button" disabled {{ $attributes->class($classes) }}>{{ $slot }}</button>
@else
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>{{ $slot }}</a>
@endif
