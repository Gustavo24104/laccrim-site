@props(['campo', 'valor' => ''])

@php
    $chave = "campos.{$campo['nome']}";
    $id = 'campo-'.str_replace('_', '-', $campo['nome']);
    $obrigatorio = $campo['obrigatorio'] ?? true;
    $valorAtual = old($chave, $valor);
    $classesEntrada = 'w-full rounded-lg border border-linha bg-[#e4ebf2] px-3 py-2 text-texto dark:bg-linha';
@endphp

<div class="flex flex-col gap-3">
    <label for="{{ $id }}" id="{{ $id }}-rotulo" class="text-sm font-semibold text-titulo">
        {{ $campo['rotulo'] }}
        @unless ($obrigatorio)
            <span class="font-normal text-texto-suave">(opcional)</span>
        @endunless
    </label>

    @switch($campo['tipo'])
        @case('texto_longo')
            <textarea id="{{ $id }}" name="campos[{{ $campo['nome'] }}]" rows="3" @required($obrigatorio) class="{{ $classesEntrada }}">{{ $valorAtual }}</textarea>
            @break

        @case('html')
            <div data-editor-rico class="flex flex-col overflow-hidden rounded-lg border border-linha bg-white dark:bg-primaria-claro">
                <textarea id="{{ $id }}" name="campos[{{ $campo['nome'] }}]" rows="8" data-editor-entrada aria-describedby="{{ $id }}-dica" class="{{ $classesEntrada }} rounded-none border-0">{{ $valorAtual }}</textarea>
            </div>
            <span id="{{ $id }}-dica" class="text-xs text-texto-suave">Negrito, itálico, sublinhado, subtítulos, listas, citações e links. Outras formatações são removidas ao salvar.</span>
            @break

        @default
            <input
                type="{{ match ($campo['tipo']) { 'email' => 'email', 'url' => 'url', default => 'text' } }}"
                id="{{ $id }}"
                name="campos[{{ $campo['nome'] }}]"
                value="{{ $valorAtual }}"
                @required($obrigatorio)
                class="{{ $classesEntrada }}"
            >
    @endswitch

    @error($chave)
        <span class="text-sm text-red-600">{{ $message }}</span>
    @enderror
</div>
