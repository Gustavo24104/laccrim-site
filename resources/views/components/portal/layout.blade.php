@props(['titulo' => null])

@php
    $tituloCompleto = $titulo ? "{$titulo} – Portal da Liga {$identidade['sigla']}" : "Portal da Liga {$identidade['sigla']}";
    $declaracoesCss = implode('', array_map(
        fn (string $valor, string $nome): string => "--color-{$nome}: {$valor};",
        $coresPersonalizadas,
        array_keys($coresPersonalizadas),
    ));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" @class(['dark' => request()->cookie('tema') === 'escuro'])>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $tituloCompleto }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if ($declaracoesCss !== '')
        {{-- A tag fica partida para o validador de CSS não ler este trecho como CSS. --}}
        @php echo '<'.'style>:root { '.$declaracoesCss.' }</'.'style>'; @endphp
    @endif
</head>
<body class="min-h-screen bg-fundo-alt">
    <a href="#conteudo" class="sr-only z-50 rounded-md bg-destaque px-4 py-2 font-semibold text-primaria focus:not-sr-only focus:fixed focus:top-3 focus:left-3">
        Pular para o conteúdo
    </a>

    <div class="flex min-h-screen">
        <div data-sidebar-fundo class="fixed inset-0 z-30 hidden bg-black/50 lg:hidden"></div>

        <aside
            id="portal-menu-lateral"
            data-sidebar
            data-aberta="false"
            data-recolhida="false"
            aria-label="Menu do portal"
            class="group fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-primaria text-white shadow-lg transition-[width,transform] duration-200 data-[aberta=true]:translate-x-0 lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 lg:shadow-none lg:data-[recolhida=true]:w-20"
        >
            <div class="flex items-center justify-between gap-2 border-b border-white/10 px-4 py-4">
                <a href="{{ route('portal.dashboard') }}" class="rounded-md" aria-label="Portal da Liga – Início">
                    <x-site.logo :identidade="$identidade" class="lg:group-data-[recolhida=true]:[&>span:last-child]:sr-only" />
                </a>
                <button type="button" data-sidebar-fechar class="inline-flex size-9 items-center justify-center rounded-md hover:bg-white/10 lg:hidden" aria-label="Fechar menu">
                    <x-ui.icone nome="fechar" class="size-6" />
                </button>
            </div>

            <nav class="flex-1 overflow-y-auto px-3 py-4" aria-label="Seções do portal">
                <ul class="flex flex-col gap-1">
                    @foreach ($itensMenuPortal as $item)
                        <li>
                            <a
                                href="{{ $item['href'] }}"
                                title="{{ $item['rotulo'] }}"
                                @if ($item['ativo']) aria-current="page" @endif
                                @class([
                                    'flex items-center gap-3 rounded-md border-l-4 px-3 py-2.5 text-sm font-medium transition-colors',
                                    'border-destaque bg-white/10 text-white' => $item['ativo'],
                                    'border-transparent text-white/80 hover:bg-white/5 hover:text-white' => ! $item['ativo'],
                                ])
                            >
                                <x-ui.icone :nome="$item['icone']" class="size-5 shrink-0" />
                                <span class="lg:group-data-[recolhida=true]:sr-only">{{ $item['rotulo'] }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <div class="flex flex-col gap-1 border-t border-white/10 px-3 py-4">
                <a href="{{ route('home') }}" title="Ir para o site" class="flex items-center gap-3 rounded-md border-l-4 border-transparent px-3 py-2.5 text-sm font-medium text-white/80 hover:bg-white/5 hover:text-white">
                    <x-ui.icone nome="globo" class="size-5 shrink-0" />
                    <span class="lg:group-data-[recolhida=true]:sr-only">Ir para o site</span>
                </a>
                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Sair" class="flex w-full items-center gap-3 rounded-md border-l-4 border-transparent px-3 py-2.5 text-left text-sm font-medium text-white/80 hover:bg-white/5 hover:text-white">
                        <x-ui.icone nome="sair" class="size-5 shrink-0" />
                        <span class="lg:group-data-[recolhida=true]:sr-only">Sair</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex min-w-0 flex-1 flex-col">
            <header class="sticky top-0 z-20 flex items-center justify-between gap-4 border-b border-linha bg-superficie px-4 py-3 sm:px-6">
                <div class="flex min-w-0 items-center gap-2">
                    <button type="button" data-sidebar-abrir aria-controls="portal-menu-lateral" aria-expanded="false" aria-label="Abrir menu" class="inline-flex size-10 items-center justify-center rounded-md text-titulo hover:bg-linha lg:hidden">
                        <x-ui.icone nome="menu" class="size-6" />
                    </button>
                    <button type="button" data-sidebar-recolher aria-controls="portal-menu-lateral" aria-expanded="true" aria-label="Recolher menu lateral" class="hidden size-10 items-center justify-center rounded-md text-titulo hover:bg-linha lg:inline-flex">
                        <x-ui.icone nome="recolher" class="size-5 transition-transform [[aria-expanded=false]_&]:rotate-180" />
                    </button>
                    @if ($titulo)
                        <span class="truncate font-serif text-lg font-bold whitespace-nowrap text-titulo">{{ $titulo }}</span>
                    @endif
                </div>

                <div class="flex items-center gap-2 sm:gap-3">
                    <div class="hidden xl:block">
                        <x-portal.visualizar-como />
                    </div>

                    <button
                        type="button"
                        data-tema-alternar
                        class="inline-flex size-10 items-center justify-center rounded-md text-titulo hover:bg-linha"
                        aria-pressed="{{ request()->cookie('tema') === 'escuro' ? 'true' : 'false' }}"
                        aria-label="{{ request()->cookie('tema') === 'escuro' ? 'Ativar tema claro' : 'Ativar tema escuro' }}"
                    >
                        <x-ui.icone nome="sol" class="size-5 dark:hidden" />
                        <x-ui.icone nome="lua" class="hidden size-5 dark:block" />
                    </button>

                    <x-portal.notificacoes />

                    @if ($usuario)
                        <a href="{{ route('portal.configuracoes') }}" class="rounded-full" aria-label="Configurações do perfil de {{ $usuario->name }}">
                            <x-portal.avatar :usuario="$usuario" class="size-9 text-sm" />
                        </a>
                    @endif
                </div>
            </header>

            <main id="conteudo" tabindex="-1" class="flex flex-1 flex-col gap-6 p-4 focus:outline-none sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
