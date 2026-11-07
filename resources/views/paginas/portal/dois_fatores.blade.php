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
                        <button type="submit" class="bg-destaque text-primaria hover:bg-destaque-claro border 
                        border-primaria inline-flex items-center justify-center 
                        gap-2 rounded-lg px-6 py-3 text-sm font-semibold transition-colors">
                            Ativar 2-fatores
                        </button>
                    </form>
                        
                    @if (session('status') == 'two-factor-authentication-enabled')
                        <form method="post" action="{{ route('two-factor.confirm') }}">
                            @csrf
                            <div class="mb-4 font-medium text-sm">
                                Please finish configuring two-factor authentication below.
                            </div>
                            <div class="mb-4" style="margin-bottom: 15px;">
                                {!! auth()->user()->twoFactorQrCodeSvg() !!}
                            </div>  
                                <div style="margin-bottom: 15px;">
                                <label for="code" style="display: block;"> Código recebido </label>
                                <input type="text" id="code" name="code" required autofocus style="width: 100%; padding: 8px;">
                                </div>
                            <button type="submit" class="bg-destaque text-primaria hover:bg-destaque-claro border 
                            border-primaria inline-flex items-center justify-center 
                            gap-2 rounded-lg px-6 py-3 text-sm font-semibold transition-colors">
                                Confirmar código
                            </button>
                        </form>
                    @endif

                    @if (session('status') == 'two-factor-authentication-confirmed')
                        <div class="mb-4 font-medium text-sm">
                            Two-factor authentication confirmed and enabled successfully. <br>
                            Códigos de recuperação:
                            <div class="p-4 bg-gray-100 rounded-lg">
                                <ul class="grid grid-cols-1 md:grid-cols-2 gap-2 font-mono text-sm">
                                    @foreach (auth()->user()->recoveryCodes() as $code)
                                        <li>{{ $code }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            </div>
                        </div>
                    @endif

                        {{-- <x-ui.button type="submit" > Entrar </x-ui.button> --}}
                    </div>
            </x-ui.card>
            <x-ui.button :href="route('home')" variante="secundario">Voltar à Página Inicial</x-ui.button>
        </div>
    </section>
</x-layout>

