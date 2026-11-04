<div {{ $attributes->class('flex items-center gap-3 rounded-xl border border-dashed border-slate-300 bg-fundo-alt p-6 text-texto-suave') }} role="status">
    <x-ui.icone nome="informacao" class="size-5 shrink-0 text-destaque" />
    <p>{{ $slot }}</p>
</div>
