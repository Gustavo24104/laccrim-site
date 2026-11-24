@php
    $fuso = config('laccrim.fuso_exibicao');
    $aguardando = $painel['situacao'] === \App\Enums\SituacaoConteudo::AguardandoAprovacao;
    $classesBotao = 'inline-flex items-center justify-center gap-2 rounded-lg px-6 py-3 text-sm font-semibold transition-colors';
@endphp

<x-portal.layout :titulo="$titulo">
    <div class="flex flex-col gap-3">
        <a href="{{ route('portal.conteudo.index') }}" class="w-fit text-sm font-semibold text-texto underline underline-offset-4 dark:text-destaque">Voltar para o conteúdo do site</a>

        <div class="flex flex-wrap items-center gap-3">
            <x-portal.titulo-pagina :titulo="$area->pagina.' – '.$area->rotulo" />
            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $painel['situacao']->classesSelo() }}">{{ $painel['situacao']->rotulo() }}</span>
        </div>
    </div>

    @if (session('status'))
        <p class="text-sm text-texto" role="status">{{ session('status') }}</p>
    @endif

    @foreach (['conteudo', 'versao'] as $erroGeral)
        @error($erroGeral)
            <p class="text-sm text-red-600" role="alert">{{ $message }}</p>
        @enderror
    @endforeach

    @if ($aguardando)
        <div class="w-full max-w-3xl rounded-lg border border-amber-300 bg-amber-50 px-4 py-3 text-sm text-amber-900" role="status">
            Aprovação solicitada{{ $painel['solicitante'] ? " por {$painel['solicitante']}" : '' }}{{ $painel['solicitado_em'] ? ' em '.$painel['solicitado_em']->clone()->setTimezone($fuso)->format('d/m/Y \à\s H:i') : '' }}.
            {{ $podeAprovar ? 'Revise o rascunho abaixo e publique ou devolva para ajustes.' : 'O site muda assim que a alteração for aprovada.' }}
        </div>
    @endif

    <x-ui.card class="w-full max-w-3xl text-left dark:bg-primaria">
        <form method="post" action="{{ route('portal.conteudo.update', $area->chave) }}" class="flex flex-col gap-8">
            @csrf
            @method('put')

            @foreach ($area->campos as $campo)
                <x-portal.campo-conteudo :campo="$campo" :valor="$painel['valores'][$campo['nome']] ?? ''" />
            @endforeach

            <div class="flex flex-wrap items-center gap-4">
                <button type="submit" name="acao" value="rascunho" class="{{ $classesBotao }} border border-titulo text-titulo hover:bg-primaria hover:text-white">
                    Salvar rascunho
                </button>

                @if ($podeAprovar)
                    <button type="submit" name="acao" value="publicar" class="{{ $classesBotao }} border border-primaria bg-destaque text-primaria hover:bg-destaque-claro">
                        {{ $aguardando ? 'Aprovar e publicar' : 'Salvar e publicar' }}
                    </button>
                @elseif (! $aguardando)
                    <button type="submit" name="acao" value="solicitar" class="{{ $classesBotao }} border border-primaria bg-destaque text-primaria hover:bg-destaque-claro">
                        Salvar e solicitar aprovação
                    </button>
                @endif

                @if ($painel['possui_rascunho'])
                    <a href="{{ $urlPrevia }}" target="_blank" rel="noopener" class="text-sm font-semibold text-texto underline underline-offset-4 dark:text-destaque">
                        Pré-visualizar rascunho<span class="sr-only"> (abre em nova aba)</span>
                    </a>
                @endif
            </div>
        </form>
    </x-ui.card>

    @if ($podeAprovar && $aguardando)
        <x-ui.card class="w-full max-w-3xl text-left dark:bg-primaria">
            <h2 class="font-serif text-xl font-bold text-titulo">Devolver para ajustes</h2>

            <form method="post" action="{{ route('portal.conteudo.devolver', $area->chave) }}" class="flex flex-col gap-6">
                @csrf

                <div class="flex flex-col gap-3">
                    <label for="motivo" class="text-sm font-semibold text-titulo">Motivo <span class="font-normal text-texto-suave">(opcional)</span></label>
                    <textarea id="motivo" name="motivo" rows="3" maxlength="500" class="w-full rounded-lg border border-linha bg-[#e4ebf2] px-3 py-2 text-texto dark:bg-linha">{{ old('motivo') }}</textarea>
                    @error('motivo')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="{{ $classesBotao }} border border-titulo text-titulo hover:bg-primaria hover:text-white">Devolver</button>
                </div>
            </form>
        </x-ui.card>
    @endif

    <x-ui.card class="w-full max-w-3xl text-left dark:bg-primaria">
        <h2 class="font-serif text-xl font-bold text-titulo">Versões anteriores</h2>
        <p class="text-sm text-texto-suave">As {{ config('cms.versoes_mantidas') }} últimas versões publicadas ficam guardadas. Restaurar copia a versão para o rascunho; o site só muda depois da aprovação.</p>

        @forelse ($painel['versoes'] as $versao)
            <form method="post" action="{{ route('portal.conteudo.versoes.restaurar', [$area->chave, $versao['id']]) }}" class="flex flex-wrap items-center justify-between gap-3 border-b border-linha py-3 last:border-0">
                @csrf
                <span class="text-sm text-texto">
                    @if ($versao['publicado_em'])
                        Publicada em {{ $versao['publicado_em']->clone()->setTimezone($fuso)->format('d/m/Y \à\s H:i') }}
                        @if ($versao['publicado_por'])
                            por {{ $versao['publicado_por'] }}
                        @endif
                    @else
                        Texto original do site
                    @endif
                </span>
                <button type="submit" class="text-sm font-semibold text-texto underline underline-offset-4 dark:text-destaque">Restaurar</button>
            </form>
        @empty
            <p class="text-sm text-texto">Nenhuma versão anterior ainda.</p>
        @endforelse
    </x-ui.card>
</x-portal.layout>
