@php
    $naoLidas = $painelNotificacoes['nao_lidas'];
@endphp

<div class="relative">
    <button
        type="button"
        data-notificacoes-alternar
        aria-controls="painel-notificacoes"
        aria-expanded="false"
        aria-label="Notificações{{ $naoLidas > 0 ? " ({$naoLidas} não lidas)" : '' }}"
        class="relative inline-flex size-10 items-center justify-center rounded-md text-titulo hover:bg-linha"
    >
        <x-ui.icone nome="sino" class="size-6" />
        @if ($naoLidas > 0)
            <span aria-hidden="true" class="absolute top-1 right-1 inline-flex min-w-5 items-center justify-center rounded-full bg-red-600 px-1 text-[0.6875rem] leading-5 font-bold text-white">
                {{ $naoLidas > 9 ? '9+' : $naoLidas }}
            </span>
        @endif
    </button>

    <div
        id="painel-notificacoes"
        data-notificacoes-painel
        class="absolute right-0 z-30 mt-2 hidden w-96 max-w-[calc(100vw-2rem)] rounded-xl border border-linha bg-superficie text-left shadow-lg"
    >
        <div class="flex items-center justify-between gap-3 border-b border-linha px-4 py-3">
            <h2 class="font-serif text-base font-bold text-titulo">Notificações</h2>
            @if ($naoLidas > 0)
                <form method="post" action="{{ route('portal.notificacoes.lidas') }}">
                    @csrf
                    <button type="submit" class="text-xs font-semibold text-texto underline underline-offset-4 dark:text-destaque">Marcar todas como lidas</button>
                </form>
            @endif
        </div>

        @if ($painelNotificacoes['itens'] === [])
            <p class="px-4 py-6 text-sm text-texto-suave">Você não tem notificações.</p>
        @else
            <ul class="max-h-96 divide-y divide-linha overflow-y-auto">
                @foreach ($painelNotificacoes['itens'] as $notificacao)
                    <li>
                        <form method="post" action="{{ route('portal.notificacoes.lida', $notificacao['id']) }}">
                            @csrf
                            <button type="submit" @class([
                                'flex w-full flex-col gap-1 px-4 py-3 text-left transition-colors hover:bg-fundo-alt',
                                'bg-destaque/10' => ! $notificacao['lida'],
                            ])>
                                <span class="flex items-center justify-between gap-2">
                                    <span class="rounded-full px-2 py-0.5 text-[0.6875rem] font-semibold {{ $notificacao['classes_selo'] }}">{{ $notificacao['tipo'] }}</span>
                                    <span class="text-xs text-texto-suave">{{ $notificacao['tempo'] }}</span>
                                </span>
                                <span class="flex items-center gap-2 text-sm font-semibold text-titulo">
                                    @unless ($notificacao['lida'])
                                        <span class="size-2 shrink-0 rounded-full bg-destaque" aria-hidden="true"></span>
                                        <span class="sr-only">Não lida:</span>
                                    @endunless
                                    {{ $notificacao['titulo'] }}
                                </span>
                                <span class="line-clamp-3 text-sm text-texto">{{ $notificacao['mensagem'] }}</span>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
