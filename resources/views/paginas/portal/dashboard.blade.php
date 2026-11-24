<x-portal.layout :titulo="$titulo">
    <x-portal.hero-usuario :usuario="auth()->user()" />

    @if (session('status'))
        <p class="text-sm text-texto" role="status">{{ session('status') }}</p>
    @endif

    <div class="xl:hidden">
        <x-portal.visualizar-como id="visualizar-como-movel" />
    </div>

    <x-ui.empty-state>Os painéis e atalhos do portal aparecerão aqui conforme os módulos forem liberados.</x-ui.empty-state>
</x-portal.layout>
