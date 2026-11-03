@props(['identidade', 'rodape'])

<footer class="bg-primaria text-white/85">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-2 lg:grid-cols-3 lg:px-8">
        <div class="flex flex-col gap-4">
            <x-site.logo :identidade="$identidade" />
            <p class="max-w-sm text-sm leading-relaxed">{{ $identidade['descricao'] }}</p>
        </div>

        <div class="flex flex-col gap-4">
            <h2 class="text-lg font-semibold text-white">Contato</h2>
            <address class="flex flex-col gap-3 text-sm not-italic leading-relaxed">
                <span>
                    <span class="block font-medium text-white">{{ $rodape['instituicao'] }}</span>
                    {{ $rodape['endereco'] }}
                </span>
                <a href="mailto:{{ $rodape['email'] }}" class="inline-flex w-fit items-center gap-2 hover:text-destaque">
                    <x-ui.icone nome="email" class="size-5 text-destaque" />
                    {{ $rodape['email'] }}
                </a>
                @if ($rodape['instagram']['url'])
                    <a href="{{ $rodape['instagram']['url'] }}" class="w-fit hover:text-destaque" rel="noopener">{{ $rodape['instagram']['rotulo'] }}</a>
                @endif
            </address>
        </div>

        <div class="flex flex-col gap-4">
            <h2 class="text-lg font-semibold text-white">Parceiros</h2>
            <ul class="flex flex-wrap gap-2">
                @foreach ($rodape['parceiros'] as $parceiro)
                    <li class="flex h-12 min-w-24 items-center justify-center rounded-lg border border-white/15 bg-white/5 px-3 text-xs font-semibold tracking-wide text-white">
                        @if ($parceiro['logotipo'])
                            <img src="{{ $parceiro['logotipo'] }}" alt="{{ $parceiro['nome'] }}" class="max-h-8 w-auto" loading="lazy">
                        @else
                            {{ $parceiro['nome'] }}
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="border-t border-white/10">
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-4 py-6 text-xs sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8">
            <p>&copy; {{ now(config('laccrim.fuso_exibicao'))->year }} {{ $identidade['sigla'] }}. Todos os direitos reservados.</p>
            <ul class="flex flex-wrap gap-x-6 gap-y-2">
                @foreach ($rodape['links_legais'] as $link)
                    <li><a href="{{ $link['href'] }}" class="hover:text-destaque" @if ($link['ativo']) aria-current="page" @endif>{{ $link['rotulo'] }}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
</footer>
