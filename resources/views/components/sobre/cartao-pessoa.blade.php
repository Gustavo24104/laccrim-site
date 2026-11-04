@props([
    'nome' => '',
    'subtitulo' => '',
    'complemento' => null,
    'rotuloTexto' => 'Descrição',
    'texto' => '',
    'foto' => null,
    'hierarquia' => null,
    'vazio' => false,
])

@php
    $corHierarquia = is_array($hierarquia)
        && is_string($hierarquia['cor'] ?? null)
        && preg_match('/^#[0-9a-fA-F]{6}$/', $hierarquia['cor']) === 1
        ? $hierarquia['cor']
        : null;
@endphp

<article @if ($vazio) data-cartao-vazio @endif class="relative flex items-center overflow-hidden rounded-lg border border-slate-200 bg-fundo shadow-sm">
    @if ($corHierarquia !== null)
        <span class="absolute top-4 right-4 inline-flex max-w-[9.5rem] items-center justify-center rounded-full px-3 py-1 text-center font-sans text-[0.65rem] font-semibold tracking-wider text-white uppercase sm:max-w-none sm:text-xs" @style(["background-color: {$corHierarquia}"])>{{ $hierarquia['rotulo'] }}</span>
    @elseif ($vazio)
        <p class="sr-only">Professor orientador</p>
    @endif

    <div class="flex w-28 shrink-0 items-center justify-center self-stretch bg-slate-100 sm:w-40 md:w-52">
        <div class="aspect-[4/5] w-full overflow-hidden bg-slate-200/70">
            @if ($foto)
                <img
                    src="{{ $foto }}"
                    alt="{{ $nome !== '' ? 'Foto de '.$nome : 'Foto' }}"
                    width="1080"
                    height="1350"
                    class="h-full w-full object-cover"
                >
            @else
                <div data-foto-vazia class="flex h-full w-full items-center justify-center text-slate-400">
                    <x-ui.icone nome="usuario" class="h-[72%]! w-[72%]!" />
                </div>
            @endif
        </div>
    </div>

    <div @class([
        'flex min-w-0 flex-1 flex-col justify-center gap-2 py-5 pl-4 sm:gap-3 sm:py-6 sm:pl-6',
        'pr-28 sm:pr-40' => $corHierarquia !== null,
        'pr-4 sm:pr-6' => $corHierarquia === null,
    ])>
        @if ($nome !== '')
            <h3 class="font-serif text-xl font-semibold text-primaria sm:text-2xl">{{ $nome }}</h3>
        @else
            <p class="min-h-8 font-serif text-xl font-semibold text-primaria sm:min-h-9 sm:text-2xl" aria-hidden="true"></p>
        @endif

        <p class="min-h-5 font-sans text-sm font-semibold tracking-wider text-[#2d3748] uppercase">{{ $subtitulo }}</p>

        @if ($complemento !== null)
            <p class="min-h-5 font-sans text-sm font-semibold tracking-wider text-[#2d3748] uppercase">{{ $complemento }}</p>
        @endif

        <p class="font-sans text-sm font-semibold tracking-wider text-[#2d3748] uppercase">{{ $rotuloTexto }}</p>
        <p class="min-h-10 font-sans text-sm leading-relaxed whitespace-pre-line text-slate-500 normal-case">{{ $texto }}</p>
    </div>
</article>
