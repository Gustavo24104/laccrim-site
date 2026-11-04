@props([
    'arquivo',
    'titulo',
    'rotuloAtivar',
    'rotuloZoom',
])

<div data-leitor-pdf data-tem-arquivo="1" data-rotulo-ativar="{{ $rotuloAtivar }}" {{ $attributes->class('relative') }}>
    <div data-leitor-palco class="h-80 overflow-hidden rounded-lg border border-slate-200 bg-white sm:h-[32rem] lg:h-[36rem]" tabindex="-1">
        <div data-leitor-quadro class="h-full w-full">
            <iframe
                title="{{ $titulo }}"
                src="{{ $arquivo }}"
                class="h-full w-full border-0"
            ></iframe>
        </div>
    </div>

    <div data-leitor-controles hidden class="absolute right-4 bottom-4 z-20 flex gap-2" role="group" aria-label="{{ $rotuloZoom }}">
        <button type="button" data-leitor-ampliar class="inline-flex size-11 items-center justify-center rounded-lg border border-slate-200 bg-white text-primaria shadow-md hover:bg-fundo-alt" aria-label="Ampliar">
            <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true" focusable="false">
                <circle cx="10" cy="10" r="6.25" />
                <path stroke-linecap="round" d="m14.8 14.8 5 5M7.2 8.7h5.6M7.2 11.5h5.6" />
            </svg>
        </button>
        <button type="button" data-leitor-reduzir class="inline-flex size-11 items-center justify-center rounded-lg border border-slate-200 bg-white text-primaria shadow-md hover:bg-fundo-alt" aria-label="Reduzir">
            <svg class="size-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true" focusable="false">
                <circle cx="10" cy="10" r="6.25" />
                <path stroke-linecap="round" d="m14.8 14.8 5 5M7.2 10h5.6" />
            </svg>
        </button>
    </div>
</div>
