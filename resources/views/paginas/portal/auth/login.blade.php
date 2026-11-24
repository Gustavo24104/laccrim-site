<x-layout :titulo="$titulo">
    <section aria-labelledby="pagina-titulo" class="bg-primaria text-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 md:py-20 lg:px-8">
            <h1 id="pagina-titulo" class="text-4xl font-bold md:text-5xl">{{ $titulo }}</h1>
        </div>
    </section>

    <section class="bg-fundo py-20">
        <div class="mx-auto flex max-w-2xl flex-col items-center gap-6 px-4 sm:px-6">
            <x-ui.card class="w-full text-left dark:bg-primaria">
                <h2 class="font-serif text-xl font-bold text-titulo">Portal LACCRIM - Login</h2>

                @if (session('status'))
                    <p class="text-sm text-texto" role="status">{{ session('status') }}</p>
                @endif

                <form method="post" action="{{ route('login') }}" class="flex flex-col gap-8">
                    @csrf

                    <div class="flex flex-col gap-3">
                        <label for="email" class="text-sm font-semibold text-titulo">E-mail</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            class="w-full rounded-lg border border-linha bg-[#e4ebf2] px-3 py-2 text-texto dark:bg-linha"
                        >
                        @error('email')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex flex-col gap-3">
                        <label for="password" class="text-sm font-semibold text-titulo">Senha</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="w-full rounded-lg border border-linha bg-[#e4ebf2] px-3 py-2 text-texto dark:bg-linha"
                        >
                        @error('password')
                            <span class="text-sm text-red-600">{{ $message }}</span>
                        @enderror
                        <a href="{{ route('password.request') }}" class="self-end text-sm font-semibold text-texto underline underline-offset-4 dark:text-destaque">
                            Esqueci a senha
                        </a>
                    </div>

                    <div class="flex items-center justify-start gap-6">
                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-primaria bg-destaque px-6 py-3 text-sm font-semibold text-primaria transition-colors hover:bg-destaque-claro">
                            Entrar
                        </button>
                    </div>
                </form>
            </x-ui.card>
            <x-ui.button :href="route('home')" variante="secundario" class="dark:bg-primaria dark:hover:bg-primaria-claro">Voltar à Página Inicial</x-ui.button>
        </div>
    </section>
</x-layout>
