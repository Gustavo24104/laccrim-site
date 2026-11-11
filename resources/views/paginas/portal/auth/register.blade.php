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
                <b> Formulario de registro </b>
                <form method="post" action="{{ route('register') }}"> 
                    @csrf

                    <div style="margin-bottom: 15px;">
                    <label for="email" style="display: block;">E-mail</label>
                    <input type="email" id="email" name="email" readonly value="{{ $email_registrado }}" required autofocus style="width: 100%; padding: 8px;">
                    </div>

                    <div style="margin-bottom: 15px;">
                    <label for="name" style="display: block;"> Nome Completo </label>
                    <input type="name" id="name" name="name" required autofocus style="width: 100%; padding: 8px;">
                    </div>


                    <div style="margin-bottom: 15px;">
                    <label for="password" style="display: block;">Senha</label>
                    <input type="password" id="password" name="password" required style="width: 100%; padding: 8px;">
                    {{-- tem q colocar opção de mostrar/esconder a senha --}}
                    </div>

                    <div style="margin-bottom: 15px;">
                    <label for="password_confirmation" style="display: block;">Confirme Senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required style="width: 100%; padding: 8px;">
                    {{-- tem q colocar opção de mostrar/esconder a senha --}}
                    </div>

                    @error('password')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror

                    @error('token')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    
                    <input type="hidden" name="token" readonly value="{{ $token ?? old('token') }}">
                    <div> 
                        <button type="submit" class="bg-destaque text-primaria hover:bg-destaque-claro border 
                        border-primaria inline-flex items-center justify-center 
                        gap-2 rounded-lg px-6 py-3 text-sm font-semibold transition-colors">
                            Registrar
                        </button>
                        {{-- <x-ui.button type="submit" > Entrar </x-ui.button> --}}
                    </div>
                </form>
            </x-ui.card>
            <x-ui.button :href="route('home')" variante="secundario">Voltar à Página Inicial</x-ui.button>
        </div>
    </section>
</x-layout>

