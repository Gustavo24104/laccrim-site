<?php

namespace App\Services;

use App\Contracts\ConteudoDinamicoRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Prepara a listagem e o detalhe públicos de notícias, editais e atas.
 */
class PublicacaoService
{
    public function __construct(private ConteudoDinamicoRepository $conteudoDinamico) {}

    /**
     * @param  array<string, mixed>  $consulta
     * @return array<string, mixed>
     */
    public function listar(string $pagina, string $categoria, string $busca, string $url, array $consulta): array
    {
        $pagina = $pagina === 'editais' ? 'editais' : 'noticias';
        $busca = Str::limit(trim($busca), 120, '');
        $categorias = $this->categorias();
        $chaves = array_column($categorias, 'chave');
        $grupo = $this->conteudoDinamico->chavesDoGrupo('editais-e-atas');
        $resolvida = $this->categoriaResolvida($categoria, $chaves, $pagina);
        $registros = collect($this->conteudoDinamico->publicacoesPublicas());

        if ($resolvida === 'grupo') {
            $registros = $registros->whereIn('categoria', $grupo);
        } elseif ($resolvida !== 'todas') {
            $registros = $registros->where('categoria', $resolvida);
        }

        if ($busca !== '') {
            $termo = Str::ascii(Str::lower($busca));
            $registros = $registros->filter(fn (array $item): bool => $this->correspondeABusca($item, $termo));
        }

        $porPagina = $this->conteudoDinamico->porPaginaPublicacoes();
        $paginaAtual = LengthAwarePaginator::resolveCurrentPage();
        $total = $registros->count();
        $itens = $registros->forPage($paginaAtual, $porPagina)->values();
        $paginacao = (new LengthAwarePaginator($itens, $total, $porPagina, $paginaAtual, ['path' => $url]))
            ->appends($this->consultaDaPaginacao($consulta));
        $textos = $this->conteudoDinamico->textosListagem($pagina);
        $rota = $pagina === 'editais' ? 'editais' : 'noticias';

        return [
            'titulo' => (string) ($textos['titulo'] ?? ($pagina === 'editais' ? 'Editais e Atas' : 'Notícias')),
            'texto' => (string) ($textos['texto'] ?? ''),
            'acao' => route($rota),
            'busca' => $busca,
            'categoria_consulta' => $resolvida === 'grupo' ? null : $resolvida,
            'filtros' => $this->filtros($categorias, $grupo, $resolvida, $busca, $rota),
            'itens' => $itens->map(fn (array $item): array => $this->formatarCartao($item, $categorias))->all(),
            'paginacao' => $paginacao,
            'vazio' => $busca !== ''
                ? 'Nenhuma publicação encontrada para essa busca.'
                : 'Nenhuma publicação disponível no momento.',
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    public function encontrar(string $slug): ?array
    {
        $item = collect($this->conteudoDinamico->publicacoesPublicas())
            ->first(fn (array $publicacao): bool => ($publicacao['slug'] ?? null) === $slug);

        if (! is_array($item)) {
            return null;
        }

        return $this->formatarDetalhe($item, $this->categorias());
    }

    /**
     * @param  list<string>  $chaves
     */
    private function categoriaResolvida(string $categoria, array $chaves, string $pagina): string
    {
        if ($categoria === 'todas' || in_array($categoria, $chaves, true)) {
            return $categoria;
        }

        return $pagina === 'editais' ? 'grupo' : 'noticia';
    }

    /**
     * @return list<array{chave: string, rotulo: string, rotulo_filtro: string}>
     */
    private function categorias(): array
    {
        return collect($this->conteudoDinamico->categoriasPublicacao())
            ->filter(fn (mixed $categoria): bool => is_array($categoria)
                && is_string($categoria['chave'] ?? null)
                && preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $categoria['chave']) === 1)
            ->map(fn (array $categoria): array => [
                'chave' => $categoria['chave'],
                'rotulo' => $this->rotulo($categoria, 'rotulo'),
                'rotulo_filtro' => $this->rotulo($categoria, 'rotulo_filtro'),
                'ordem' => (int) ($categoria['ordem'] ?? 0),
            ])
            ->sortBy([
                ['ordem', 'asc'],
                ['rotulo', 'asc'],
            ])
            ->unique('chave')
            ->map(fn (array $categoria): array => [
                'chave' => $categoria['chave'],
                'rotulo' => $categoria['rotulo'],
                'rotulo_filtro' => $categoria['rotulo_filtro'] !== '' ? $categoria['rotulo_filtro'] : $categoria['rotulo'],
            ])
            ->values()
            ->all();
    }

    /**
     * @param  array<string, mixed>  $categoria
     */
    private function rotulo(array $categoria, string $campo): string
    {
        $valor = $categoria[$campo] ?? null;

        if (is_string($valor) && $valor !== '') {
            return $valor;
        }

        return $campo === 'rotulo' ? (string) $categoria['chave'] : '';
    }

    /**
     * @param  list<array{chave: string, rotulo: string, rotulo_filtro: string}>  $categorias
     * @param  list<string>  $grupo
     * @return list<array{rotulo: string, href: string, ativo: bool}>
     */
    private function filtros(array $categorias, array $grupo, string $resolvida, string $busca, string $rota): array
    {
        $opcoes = [
            ['chave' => 'todas', 'rotulo' => 'Todas'],
            ...array_map(fn (array $categoria): array => [
                'chave' => $categoria['chave'],
                'rotulo' => $categoria['rotulo_filtro'],
            ], $categorias),
        ];

        return array_map(function (array $opcao) use ($grupo, $resolvida, $busca, $rota): array {
            $parametros = ['categoria' => $opcao['chave']];

            if ($busca !== '') {
                $parametros['q'] = $busca;
            }

            return [
                'rotulo' => $opcao['rotulo'],
                'href' => route($rota, $parametros),
                'ativo' => $opcao['chave'] === 'todas'
                    ? $resolvida === 'todas'
                    : $resolvida === $opcao['chave'] || ($resolvida === 'grupo' && in_array($opcao['chave'], $grupo, true)),
            ];
        }, $opcoes);
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function correspondeABusca(array $item, string $termo): bool
    {
        $palavras = collect($item['palavras_chave'] ?? [])
            ->filter(fn (mixed $palavra): bool => is_string($palavra))
            ->implode(' ');
        $texto = Str::ascii(Str::lower(implode(' ', [
            (string) ($item['titulo'] ?? ''),
            (string) ($item['resumo'] ?? ''),
            (string) ($item['conteudo'] ?? ''),
            $palavras,
        ])));

        return str_contains($texto, $termo);
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  list<array{chave: string, rotulo: string, rotulo_filtro: string}>  $categorias
     * @return array<string, mixed>
     */
    private function formatarCartao(array $item, array $categorias): array
    {
        $data = $this->paraFusoDeExibicao((string) ($item['publicado_em'] ?? ''));
        $download = $this->anexoPrincipal($this->anexos($item));

        return [
            'categoria' => $this->rotuloDaChave($categorias, (string) ($item['categoria'] ?? '')),
            'titulo' => (string) ($item['titulo'] ?? ''),
            'resumo' => (string) ($item['resumo'] ?? ''),
            'autor' => (string) ($item['autor'] ?? ''),
            'data_iso' => $data->toDateString(),
            'data_formatada' => $data->format('d/m/Y'),
            'href' => $this->hrefDetalhe($item),
            'download' => $download === null ? null : [
                'href' => $download['href'],
                'rotulo' => $download['pdf'] ? 'Baixar PDF' : 'Baixar anexo',
                'nome' => $download['nome'],
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  list<array{chave: string, rotulo: string, rotulo_filtro: string}>  $categorias
     * @return array<string, mixed>
     */
    private function formatarDetalhe(array $item, array $categorias): array
    {
        $data = $this->paraFusoDeExibicao((string) ($item['publicado_em'] ?? ''));
        $anexos = $this->anexos($item);
        $pdf = collect($anexos)->first(fn (array $anexo): bool => $anexo['pdf']);
        $chave = (string) ($item['categoria'] ?? '');
        $naAreaDeEditais = in_array($chave, $this->conteudoDinamico->chavesDoGrupo('editais-e-atas'), true);

        return [
            'categoria' => $this->rotuloDaChave($categorias, $chave),
            'titulo' => (string) ($item['titulo'] ?? ''),
            'resumo' => (string) ($item['resumo'] ?? ''),
            'conteudo' => (string) ($item['conteudo'] ?? ''),
            'autor' => (string) ($item['autor'] ?? ''),
            'data_iso' => $data->toDateString(),
            'data_formatada' => $data->format('d/m/Y'),
            'voltar' => [
                'href' => route($naAreaDeEditais ? 'editais' : 'noticias', ['categoria' => $chave]),
                'rotulo' => $naAreaDeEditais ? 'Editais e Atas' : 'Notícias',
            ],
            'anexos' => $anexos,
            'pdf' => $pdf['href'] ?? null,
        ];
    }

    /**
     * @param  list<array{chave: string, rotulo: string, rotulo_filtro: string}>  $categorias
     */
    private function rotuloDaChave(array $categorias, string $chave): string
    {
        foreach ($categorias as $categoria) {
            if ($categoria['chave'] === $chave) {
                return $categoria['rotulo'];
            }
        }

        return $chave;
    }

    /**
     * @param  array<string, mixed>  $item
     */
    private function hrefDetalhe(array $item): string
    {
        $slug = (string) ($item['slug'] ?? '');
        $chave = (string) ($item['categoria'] ?? '');
        $rota = in_array($chave, $this->conteudoDinamico->chavesDoGrupo('editais-e-atas'), true)
            ? 'editais.show'
            : 'noticias.show';

        return route($rota, ['publicacao' => $slug]);
    }

    /**
     * @param  array<string, mixed>  $item
     * @return list<array{nome: string, href: string, pdf: bool}>
     */
    private function anexos(array $item): array
    {
        return collect($item['anexos'] ?? [])
            ->filter(fn (mixed $anexo): bool => is_array($anexo))
            ->map(function (array $anexo): ?array {
                $href = $this->urlPublica(is_string($anexo['url'] ?? null) ? $anexo['url'] : null);

                if ($href === null) {
                    return null;
                }

                $nome = is_string($anexo['nome'] ?? null) && $anexo['nome'] !== '' ? $anexo['nome'] : 'Anexo';

                return [
                    'nome' => $nome,
                    'href' => $href,
                    'pdf' => $this->ePdf($anexo, $href, $nome),
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param  list<array{nome: string, href: string, pdf: bool}>  $anexos
     * @return array{nome: string, href: string, pdf: bool}|null
     */
    private function anexoPrincipal(array $anexos): ?array
    {
        foreach ($anexos as $anexo) {
            if ($anexo['pdf']) {
                return $anexo;
            }
        }

        return $anexos[0] ?? null;
    }

    /**
     * @param  array<string, mixed>  $anexo
     */
    private function ePdf(array $anexo, string $href, string $nome): bool
    {
        $mime = strtolower((string) ($anexo['mime'] ?? ''));
        $caminho = strtolower((string) (parse_url($href, PHP_URL_PATH) ?? ''));

        return $mime === 'application/pdf'
            || str_ends_with(strtolower($nome), '.pdf')
            || str_ends_with($caminho, '.pdf');
    }

    private function urlPublica(?string $url): ?string
    {
        if ($url === null || $url === '') {
            return null;
        }

        if (str_starts_with($url, '/') && ! str_starts_with($url, '//') && ! str_starts_with($url, '/\\')) {
            return $url;
        }

        $esquema = parse_url($url, PHP_URL_SCHEME);

        if (! is_string($esquema) || ! in_array(strtolower($esquema), ['http', 'https'], true)) {
            return null;
        }

        return filter_var($url, FILTER_VALIDATE_URL) ? $url : null;
    }

    /**
     * @param  array<string, mixed>  $consulta
     * @return array<string, string>
     */
    private function consultaDaPaginacao(array $consulta): array
    {
        $parametros = [];

        foreach ($consulta as $chave => $valor) {
            if (! is_string($chave) || $chave === 'page' || ! is_string($valor)) {
                continue;
            }

            $parametros[$chave] = $valor;
        }

        return $parametros;
    }

    private function paraFusoDeExibicao(string $dataUtc): Carbon
    {
        return Carbon::parse($dataUtc, 'UTC')->setTimezone(config('laccrim.fuso_exibicao'));
    }
}
