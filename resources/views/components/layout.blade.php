@props(['titulo' => null, 'descricao' => null])

@php
    $tituloCompleto = $titulo ? "{$titulo} – {$identidade['sigla']}" : $identidade['sigla'];
    $descricaoPagina = $descricao ?? $identidade['descricao'];
    $declaracoesCss = implode('', array_map(
        fn (string $valor, string $nome): string => "--color-{$nome}: {$valor};",
        $coresPersonalizadas,
        array_keys($coresPersonalizadas),
    ));
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $tituloCompleto }}</title>
    <meta name="description" content="{{ $descricaoPagina }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="pt_BR">
    <meta property="og:title" content="{{ $tituloCompleto }}">
    <meta property="og:description" content="{{ $descricaoPagina }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <link rel="canonical" href="{{ url()->current() }}">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if ($declaracoesCss !== '')
        {{-- A tag fica partida para o validador de CSS não ler este trecho como CSS. --}}
        @php echo '<'.'style>:root { '.$declaracoesCss.' }</'.'style>'; @endphp
    @endif
</head>
<body class="flex min-h-screen flex-col">
    <a href="#conteudo" class="sr-only z-50 rounded-md bg-destaque px-4 py-2 font-semibold text-primaria focus:not-sr-only focus:fixed focus:top-3 focus:left-3">
        Pular para o conteúdo
    </a>

    <x-site.header :identidade="$identidade" :itens-menu="$itensMenu" :item-menu-destaque="$itemMenuDestaque" />

    <main id="conteudo" tabindex="-1" class="flex-1 focus:outline-none">
        {{ $slot }}
    </main>
k
    <x-site.footer :identidade="$identidade" :rodape="$rodape" />
</body>
</html>
