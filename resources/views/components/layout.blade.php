<!DOCTYPE html>
<html lang="pt" data-theme="lofi">
<head class="bg-primaria text-white">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' - LACCRIM' : 'LACCRIM' }}</title>
    <link rel="preconnect" href="<https://fonts.bunny.net>">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" type="text/css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen flex flex-col bg-primaria text-white font-sans">
    <nav class="navbar bg-base-100 bg-primaria text-white">
        <div class="navbar-start">
            <button href="/" class="btn btn-ghost bg-transparent hover:bg-destaque text-xl text-white">LACCRIM/UFU</button>
        </div>
        <div class="navbar items-center justify-between sticky">
            <button href="/" class="btn btn-ghost bg-transparent hover:bg-destaque text-xl text-white">Home</button>
            <button href="/" class="btn btn-ghost bg-transparent hover:bg-destaque text-xl text-white">Sobre nós</button>
            <button href="/" class="btn btn-ghost bg-transparent hover:bg-destaque text-xl text-white">Notícias</button>
            <button href="/" class="btn btn-ghost bg-transparent hover:bg-destaque text-xl text-white">Editais</button>
            <button href="/" class="btn btn-ghost bg-transparent hover:bg-destaque text-xl text-white">Atas</button>
            <button href="/" class="btn btn-ghost bg-transparent hover:bg-destaque text-xl text-white">Eventos</button>
            <button href="/" class="btn btn-ghost bg-transparent hover:bg-destaque text-xl text-white">Protocolo virtual</button>
        </div>
        <div class="navbar-end gap-2">
            <button href="/" class="btn bg-destaque hover:bg-destaqueEscuro text-xl text-white">Página de membro</button>
            
            {{-- <a href="#" class="btn btn-ghost btn-sm text-white">Sign In</a> --}}
            {{-- <a href="#" class="btn btn-primary btn-sm">Sign Up</a> --}}
        </div>
    </nav>

    <main class="flex-1 container mx-auto px-4 py-8 bg-primaria text-white">
        {{ $slot }}
    </main>

    <footer class="footer footer-center p-5 bg-base-300 text-base-content text-xs bg-primaria text-white">
        <div class="sm:flex sm:items-center sm:justify-between">
            <a href="https://www.fadir.ufu.br/">https://www.fadir.ufu.br</a>
            <a>laccrim@fadir.ufu.br</a>
            <a>instagram</a>
            <a href="#">Política de Privacidade</a>
            <a href="#">Termos de Privacidade</a>
            
        </div>
    </footer>
</body>
</html>