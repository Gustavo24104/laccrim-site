@props(['paginacao'])

@if ($paginacao->hasPages())
    <nav aria-label="Paginação" {{ $attributes }}>
        <ul class="flex flex-wrap items-center justify-center gap-2">
            @foreach ($paginacao->linkCollection() as $link)
                @php
                    $rotulo = match (true) {
                        $loop->first => 'Anterior',
                        $loop->last => 'Próxima',
                        default => $link['label'],
                    };
                    $classes = [
                        'inline-flex min-w-11 items-center justify-center rounded-lg px-3 py-2 text-sm font-semibold',
                        'bg-primaria text-white' => $link['active'],
                        'text-titulo hover:bg-fundo-alt' => ! $link['active'] && $link['url'],
                        'text-texto-suave' => ! $link['url'],
                    ];
                @endphp
                <li>
                    @if ($link['url'])
                        <a href="{{ $link['url'] }}" @class($classes) @if ($link['active']) aria-current="page" @endif>{{ $rotulo }}</a>
                    @else
                        <span @class($classes) @if ($link['active']) aria-current="page" @endif>{{ $rotulo }}</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>
@endif
