<x-portal.layout :titulo="$titulo">
    <x-portal.titulo-pagina titulo="Conteúdo do site" descricao="Edite os textos das páginas públicas. As alterações só aparecem no site depois de aprovadas." />

    @foreach ($linhas as $pagina => $areas)
        <x-ui.card class="w-full overflow-x-auto text-left dark:bg-primaria">
            <h2 class="font-serif text-xl font-bold text-titulo">{{ $pagina }}</h2>

            <table class="w-full text-sm text-texto">
                <thead>
                    <tr class="border-b border-linha text-left text-titulo">
                        <th scope="col" class="py-3 pr-4 font-semibold">Área</th>
                        <th scope="col" class="py-3 pr-4 font-semibold">Situação</th>
                        <th scope="col" class="py-3 pr-4 font-semibold">Última publicação</th>
                        <th scope="col" class="py-3 font-semibold"><span class="sr-only">Ações</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($areas as $linha)
                        <tr class="border-b border-linha last:border-0">
                            <td class="py-3 pr-4 font-semibold text-titulo">{{ $linha['area']->rotulo }}</td>
                            <td class="py-3 pr-4">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $linha['situacao']->classesSelo() }}">{{ $linha['situacao']->rotulo() }}</span>
                            </td>
                            <td class="py-3 pr-4">
                                @if ($linha['publicado_em'])
                                    {{ $linha['publicado_em']->clone()->setTimezone(config('laccrim.fuso_exibicao'))->format('d/m/Y H:i') }}
                                    @if ($linha['publicado_por'])
                                        <span class="block text-xs text-texto-suave">por {{ $linha['publicado_por'] }}</span>
                                    @endif
                                @else
                                    <span class="text-texto-suave">Texto original</span>
                                @endif
                            </td>
                            <td class="py-3 text-right">
                                <a href="{{ route('portal.conteudo.edit', $linha['area']->chave) }}" class="font-semibold text-texto underline underline-offset-4 dark:text-destaque">
                                    Editar<span class="sr-only"> {{ $pagina }} – {{ $linha['area']->rotulo }}</span>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </x-ui.card>
    @endforeach
</x-portal.layout>
