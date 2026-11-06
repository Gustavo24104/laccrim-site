@props(['identidade', 'itensMenu', 'itemMenuDestaque'])

<header class="sticky top-0 z-40 bg-primaria text-white shadow-md">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="rounded-md" aria-label="{{ $identidade['sigla'] }} – Página Inicial">
            <x-site.logo :identidade="$identidade" />
        </a>

        <nav aria-label="Menu principal" class="hidden lg:block">
            <ul class="flex items-center gap-1">
                @foreach ($itensMenu as $item)
                    <li>
                        <a
                            href="{{ $item['href'] }}"
                            @if ($item['ativo']) aria-current="page" @endif
                            @class([
                                'block border-b-2 px-3 py-2 text-sm font-medium transition-colors',
                                'border-destaque text-white' => $item['ativo'],
                                'border-transparent text-white/85 hover:border-destaque/60 hover:text-white' => ! $item['ativo'],
                            ])
                        >{{ $item['rotulo'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="flex items-center gap-3">
            <div class="hidden lg:block">
                <x-ui.button :href="$itemMenuDestaque['href']" class="px-5 py-2.5">{{ $itemMenuDestaque['rotulo'] }}</x-ui.button>
            </div>

            <button
                type = "button"
                data-tema-alternar
                class="inline-flex size-10 items-center justify-center rounded-md text-white hover:bg-white/10"
                aria-pressed="{{ request()->cookie('tema') === 'escuro' ? 'true' : 'false' }}"
                aria-label="{{ request()->cookie('tema') === 'escuro' ? 'Ativar tema claro' : 'Ativar tema escuro' }}"
            >
                <x-ui.icone nome="sol" class="size-5 dark:hidden" />
                <x-ui.icone nome="lua" class="hidden size-5 dark:block" />
            </button>

            <button
                type="button"
                class="inline-flex items-center justify-center rounded-md p-2 text-white hover:bg-white/10 lg:hidden"
                data-menu-alternar
                aria-controls="menu-movel"
                aria-expanded="false"
                aria-label="Abrir menu"
            >
                <x-ui.icone nome="menu" class="size-7" />
            </button>
        </div>
    </div>

    <nav id="menu-movel" aria-label="Menu principal para celular" class="hidden border-t border-white/10 lg:hidden">
        <ul class="flex flex-col gap-1 px-4 py-4 sm:px-6">
            @foreach ($itensMenu as $item)
                <li>
                    <a
                        href="{{ $item['href'] }}"
                        @if ($item['ativo']) aria-current="page" @endif
                        @class([
                            'block rounded-md border-l-4 px-4 py-3 text-base font-medium',
                            'border-destaque bg-white/5 text-white' => $item['ativo'],
                            'border-transparent text-white/85 hover:bg-white/5 hover:text-white' => ! $item['ativo'],
                        ])
                    >{{ $item['rotulo'] }}</a>
                </li>
            @endforeach
            <li class="pt-3">
                <x-ui.button :href="$itemMenuDestaque['href']" class="w-full">{{ $itemMenuDestaque['rotulo'] }}</x-ui.button>
            </li>
        </ul>
    </nav>
</header>
