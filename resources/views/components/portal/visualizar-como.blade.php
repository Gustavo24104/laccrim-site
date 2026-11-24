@props(['id' => 'visualizar-como'])

@if ($visualizarComoHabilitado)
    <form method="post" action="{{ route('portal.visualizar-como') }}" class="flex flex-wrap items-center gap-2 rounded-lg border border-dashed border-destaque px-3 py-2 text-sm text-texto">
        @csrf
        <label for="{{ $id }}" class="font-semibold text-titulo">Visualizar como (localhost):</label>
        <select id="{{ $id }}" name="perfil" onchange="this.form.submit()" class="rounded-lg border border-linha bg-[#e4ebf2] px-2 py-1 text-texto dark:bg-linha">
            <option value="" @selected($cargoSimulado === null)>Todas as permissões</option>
            @foreach ($cargosSimulaveis as $cargo)
                <option value="{{ $cargo->slug }}" @selected($cargoSimulado === $cargo->slug)>{{ $cargo->nome }}</option>
            @endforeach
        </select>
        <noscript><button type="submit" class="underline">Aplicar</button></noscript>
    </form>
@endif
