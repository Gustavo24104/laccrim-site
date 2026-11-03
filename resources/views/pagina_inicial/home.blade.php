<x-layout titulo="Página Inicial">
    @foreach ($secoes as $secao)
        <x-dynamic-component :component="$secao['componente']" :dados="$secao['dados']" />
    @endforeach
</x-layout>
