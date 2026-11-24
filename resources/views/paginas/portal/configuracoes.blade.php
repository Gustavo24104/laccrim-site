<x-portal.layout :titulo="$titulo">
    <x-portal.titulo-pagina titulo="Configurações do perfil" />

    <x-ui.card class="w-full max-w-2xl text-left dark:bg-primaria">
        <h2 class="font-serif text-xl font-bold text-titulo">Foto de perfil</h2>
        <p class="text-sm text-texto-suave">Esta foto aparece no portal e no Corpo Social da página Sobre Nós, quando você integra o Corpo Social.</p>

        @if (session('status') === 'foto-atualizada')
            <p class="text-sm text-texto" role="status">Foto atualizada com sucesso.</p>
        @endif

        <form method="post" action="{{ route('portal.configuracoes.foto') }}" enctype="multipart/form-data" class="flex flex-col gap-6">
            @csrf
            @method('put')

            <div class="flex items-center gap-5">
                <x-portal.avatar :usuario="auth()->user()" class="size-20 text-2xl" />

                <div class="flex flex-col gap-2">
                    <label for="foto" class="text-sm font-semibold text-titulo">Nova foto (JPG, PNG ou WEBP, até 2 MB)</label>
                    <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp" required class="text-sm text-texto file:mr-3 file:rounded-lg file:border file:border-primaria file:bg-destaque file:px-4 file:py-2 file:text-sm file:font-semibold file:text-primaria">
                    @error('foto')
                        <span class="text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-primaria bg-destaque px-6 py-3 text-sm font-semibold text-primaria transition-colors hover:bg-destaque-claro">
                    Enviar foto
                </button>
            </div>
        </form>
    </x-ui.card>

    @if ($exibirAusencia)
        <x-ui.card class="w-full max-w-2xl text-left dark:bg-primaria">
            <h2 class="font-serif text-xl font-bold text-titulo">Ausência da Presidência</h2>
            <p class="text-sm text-texto-suave">Enquanto você estiver ausente, a Vice-Presidência pode aprovar as alterações do site e é avisada disso.</p>

            @if (session('status') === 'ausencia-atualizada')
                <p class="text-sm text-texto" role="status">Ausência atualizada.</p>
            @endif

            <form method="post" action="{{ route('portal.configuracoes.ausencia') }}" class="flex flex-col gap-6">
                @csrf
                @method('put')
                <input type="hidden" name="ausente" value="{{ auth()->user()->ausente ? 0 : 1 }}">

                <p class="text-sm text-texto">
                    Situação atual: <strong>{{ auth()->user()->ausente ? 'ausente' : 'presente' }}</strong>
                </p>

                <div>
                    <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-primaria bg-destaque px-6 py-3 text-sm font-semibold text-primaria transition-colors hover:bg-destaque-claro">
                        {{ auth()->user()->ausente ? 'Encerrar ausência' : 'Estou ausente' }}
                    </button>
                </div>
            </form>
        </x-ui.card>
    @endif

    <x-ui.card class="w-full max-w-2xl text-left dark:bg-primaria">
        <h2 class="font-serif text-xl font-bold text-titulo">Alterar senha</h2>

        @if (session('status') === 'password-updated')
            <p class="text-sm text-texto" role="status">Senha alterada com sucesso.</p>
        @endif

        <form method="post" action="{{ route('user-password.update') }}" class="flex flex-col gap-8">
            @csrf
            @method('put')

            <x-ui.campo nome="current_password" rotulo="Senha atual" tipo="password" bag="updatePassword" required autocomplete="current-password" />
            <x-ui.campo nome="password" rotulo="Nova senha" tipo="password" bag="updatePassword" required autocomplete="new-password" />
            <x-ui.campo nome="password_confirmation" rotulo="Confirme a nova senha" tipo="password" bag="updatePassword" required autocomplete="new-password" />

            <div class="flex items-center justify-start gap-6">
                <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-lg border border-primaria bg-destaque px-6 py-3 text-sm font-semibold text-primaria transition-colors hover:bg-destaque-claro">
                    Salvar nova senha
                </button>
            </div>
        </form>
    </x-ui.card>
</x-portal.layout>
