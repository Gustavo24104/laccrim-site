@props(['dados'])

<section id="professores-orientadores" aria-labelledby="professores-titulo" class="bg-fundo-alt py-20">
    <div class="mx-auto flex max-w-7xl flex-col gap-10 px-4 sm:px-6 lg:px-8">
        <x-ui.section-heading id="professores-titulo" :titulo="$dados['titulo']" />

        <ul class="flex flex-col gap-5">
            @forelse ($dados['itens'] as $professor)
                <li>
                    <x-sobre.cartao-pessoa
                        :nome="$professor['nome']"
                        :subtitulo="$professor['titulacao']"
                        :complemento="$professor['area_atuacao']"
                        rotulo-texto="Apresentação"
                        :texto="$professor['apresentacao']"
                        :foto="$professor['foto']"
                    />
                </li>
            @empty
                <li>
                    <x-sobre.cartao-pessoa complemento="" rotulo-texto="Apresentação" vazio />
                </li>
            @endforelse
        </ul>
    </div>
</section>
