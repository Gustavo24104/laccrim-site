@props(['nome', 'rotulo', 'tipo' => 'text', 'valor' => null, 'bag' => 'default'])

<div class="flex flex-col gap-3">
    <label for="{{ $nome }}" class="text-sm font-semibold text-titulo">{{ $rotulo }}</label>
    <input
        type="{{ $tipo }}"
        id="{{ $nome }}"
        name="{{ $nome }}"
        @if ($tipo !== 'password') value="{{ old($nome, $valor) }}" @endif
        {{ $attributes->class('w-full rounded-lg border border-linha bg-[#e4ebf2] px-3 py-2 text-texto dark:bg-linha') }}
    >
    @error($nome, $bag)
        <span class="text-sm text-red-600">{{ $message }}</span>
    @enderror
</div>
