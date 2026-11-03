@props(['dados'])

<section id="corpo-social" aria-labelledby="corpo-social-titulo" class="bg-fundo py-20">
    <div class="mx-auto flex max-w-7xl flex-col gap-10 px-4 sm:px-6 lg:px-8">
        <x-ui.section-heading id="corpo-social-titulo" :titulo="$dados['titulo']" />

        <ul class="flex flex-col gap-5">
            @forelse ($dados['integrantes'] as $integrante)
                <li>
                    <x-sobre.cartao-pessoa
                        :nome="$integrante['nome']"
                        :subtitulo="$integrante['subtitulo']"
                        :complemento="$integrante['complemento']"
                        :rotulo-texto="$integrante['rotulo_texto']"
                        :texto="$integrante['texto']"
                        :foto="$integrante['foto']"
                        :hierarquia="$integrante['hierarquia']"
                    />
                </li>
            @empty
                @foreach ($dados['niveis'] as $nivel)
                    <li>
                        <x-sobre.cartao-pessoa :hierarquia="$nivel" rotulo-texto="Descrição" vazio />
                    </li>
                @endforeach
            @endforelse
        </ul>
    </div>
</section>
