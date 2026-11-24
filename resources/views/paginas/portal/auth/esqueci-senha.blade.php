<x-layout :titulo="$titulo">
    <section aria-labelledby="pagina-titulo" class="bg-primaria text-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 md:py-20 lg:px-8">
            <h1 id="pagina-titulo" class="text-4xl font-bold md:text-5xl">{{ $titulo }}</h1>
        </div>
    </section>

    <section class="bg-fundo py-20">
        <div class="mx-auto flex max-w-2xl flex-col items-center gap-6 px-4 sm:px-6">
            <x-ui.card class="w-full text-left dark:bg-primaria">
                <h2 class="font-serif text-xl font-bold text-titulo">Recuperar acesso</h2>
                <p class="text-sm text-texto">Informe o e-mail da sua conta para receber o link de redefinição de senha.</p>

                @if (session('status'))
                    <p class="text-sm text-texto" role="status">{{ session('status') }}</p>
                @endif

                <form method="post" action="{{ route('password.email') }}" class="flex flex-col gap-8">
                    @csrf

                    <x-ui.campo nome="email" rotulo="E-mail" tipo="email" required autofocus autocomplete="email" />

                    <div class="flex items-center justify-start gap-6">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-primaria bg-destaque px-6 py-3 text-sm font-semibold text-primaria transition-colors hover:bg-destaque-claro">
                            Enviar link
                        </button>
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-texto underline underline-offset-4 dark:text-destaque">
                            Voltar ao login
                        </a>
                    </div>
                </form>
            </x-ui.card>
        </div>
    </section>
</x-layout>
