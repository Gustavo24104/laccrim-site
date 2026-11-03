<x-layout titulo="Sobre Nós" :descricao="$pagina['banner']['texto']">
    <x-sobre.banner :dados="$pagina['banner']" />
    <x-sobre.quem-somos :dados="$pagina['quem_somos']" />
    <x-sobre.estatuto :dados="$pagina['estatuto']" />
    <x-sobre.corpo-social :dados="$pagina['corpo_social']" />
    <x-sobre.professores :dados="$pagina['professores']" />
</x-layout>
