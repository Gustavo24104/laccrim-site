<x-layout :titulo="$titulo">
    <section aria-labelledby="pagina-titulo" class="bg-primaria text-white">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 md:py-20 lg:px-8">
            <h1 id="pagina-titulo" class="text-4xl font-bold md:text-5xl">{{ $titulo }}</h1>
        </div>
    </section>

    <section class="bg-fundo py-20">
        <div class="mx-auto flex max-w-3xl flex-col items-center gap-6 px-4 text-center sm:px-6">
            {{-- <x-ui.empty-state>Página de login!</x-ui.empty-state> --}}
            <x-ui.card> 
                <b> Autenticacao de 2 fatores </b>
                <form method="post" action="{{ route('two-factor.enable') }}"> 
                    @csrf
                    <div> 
                    </form>
                        <form method="post" action="{{ route('two-factor.login') }}">
                            @csrf
                            <div class="mb-4 font-medium text-sm">
                                Insira o código mostrado no seu app.
                            </div>
                                <div style="margin-bottom: 15px;">
                                <label for="code" style="display: block;"> Código mostrado </label>
                                <input type="text" id="code" name="code" autofocus style="width: 100%; padding: 8px;">
                                </div>
                            
                            <div class="mb-4 font-medium text-sm">
                                Perdeu o aplicativo? Insira um dos codigos de recuperação.
                            </div>
                                <div style="margin-bottom: 15px;">
                                <label for="recovery_code" style="display: block;"> Código de recuperação </label>
                                <input type="text" id="recovery_code" name="recovery_code" autofocus style="width: 100%; padding: 8px;">
                                </div>
                            
                                <button type="submit" class="bg-destaque text-primaria hover:bg-destaque-claro border 
                            border-primaria inline-flex items-center justify-center 
                            gap-2 rounded-lg px-6 py-3 text-sm font-semibold transition-colors">
                                Confirmar código
                            </button>
                        </form>
                        {{-- <x-ui.button type="submit" > Entrar </x-ui.button> --}}
                    </div>
            </x-ui.card>
            <x-ui.button :href="route('home')" variante="secundario">Voltar à Página Inicial</x-ui.button>
        </div>
    </section>
</x-layout>

