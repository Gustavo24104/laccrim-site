<?php

namespace App\Services;

use App\Contracts\ConteudoDinamicoRepository;
use App\Contracts\ConteudoSiteRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Monta as seções visíveis da página inicial, na ordem configurada, com os dados prontos para exibição.
 */
class HomeService
{
    /**
     * @var array<string, string>
     */
    private const COMPONENTES = [
        'hero' => 'home.hero',
        'pilares' => 'home.pilares',
        'noticias' => 'home.noticias',
        'eventos' => 'home.eventos',
        'publicacoes' => 'home.publicacoes',
        'protocolo' => 'home.protocolo',
        'integrantes' => 'home.integrantes',
        'cta' => 'home.cta',
    ];

    /**
     * @var array<string, string>
     */
    private const SITUACOES_INSCRICAO = [
        'abertas' => 'Inscrições abertas',
        'encerradas' => 'Inscrições encerradas',
        'esgotadas' => 'Vagas esgotadas',
    ];

    public function __construct(
        private ConteudoSiteRepository $conteudoSite,
        private ConteudoDinamicoRepository $conteudoDinamico,
        private ResolvedorDeLink $resolvedorDeLink,
    ) {}

    /**
     * @return list<array{chave: string, componente: string, dados: array<string, mixed>}>
     */
    public function handle(): array
    {
        return collect($this->conteudoSite->secoesHome())
            ->filter(fn (array $secao): bool => $secao['visivel'] && isset(self::COMPONENTES[$secao['chave']]))
            ->sortBy('ordem')
            ->map(fn (array $secao): array => [
                'chave' => $secao['chave'],
                'componente' => self::COMPONENTES[$secao['chave']],
                'dados' => $this->dadosDaSecao($secao['chave']),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function dadosDaSecao(string $chave): array
    {
        $dados = $this->resolverBotoes($this->conteudoSite->conteudoSecaoHome($chave));
        $limite = $dados['limite'] ?? 3;

        return match ($chave) {
            'hero' => [...$dados, 'titulo_partes' => $this->separarDestaque($dados['titulo'], $dados['destaque'] ?? '')],
            'noticias' => [...$dados, 'itens' => array_map($this->formatarPublicacao(...), $this->conteudoDinamico->ultimasPublicacoes($limite))],
            'eventos' => [...$dados, 'itens' => array_map($this->formatarEvento(...), $this->conteudoDinamico->proximosEventos($limite))],
            'publicacoes' => [...$dados, 'itens' => array_map($this->formatarPublicacao(...), $this->conteudoDinamico->publicacoesEmDestaque($limite))],
            default => $dados,
        };
    }

    /**
     * @param  array<string, mixed>  $dados
     * @return array<string, mixed>
     */
    private function resolverBotoes(array $dados): array
    {
        if (isset($dados['botao'])) {
            $dados['botao']['href'] = $this->resolvedorDeLink->href($dados['botao']);
        }

        if (isset($dados['botoes'])) {
            $dados['botoes'] = array_map(
                fn (array $botao): array => [...$botao, 'href' => $this->resolvedorDeLink->href($botao)],
                $dados['botoes'],
            );
        }

        return $dados;
    }

    /**
     * @return array{antes: string, destaque: string, depois: string}
     */
    private function separarDestaque(string $titulo, string $destaque): array
    {
        if ($destaque === '' || ! Str::contains($titulo, $destaque)) {
            return ['antes' => $titulo, 'destaque' => '', 'depois' => ''];
        }

        return [
            'antes' => Str::before($titulo, $destaque),
            'destaque' => $destaque,
            'depois' => Str::after($titulo, $destaque),
        ];
    }

    /**
     * @param  array<string, mixed>  $publicacao
     * @return array<string, mixed>
     */
    private function formatarPublicacao(array $publicacao): array
    {
        $data = $this->paraFusoDeExibicao($publicacao['publicado_em']);
        $formatada = [
            'titulo' => (string) ($publicacao['titulo'] ?? ''),
            'autor' => (string) ($publicacao['autor'] ?? ''),
            'data_iso' => $data->toDateString(),
            'data_formatada' => $data->format('d/m/Y'),
        ];

        if (isset($publicacao['tipo'])) {
            return [...$formatada, 'tipo' => (string) $publicacao['tipo']];
        }

        return [
            ...$formatada,
            'categoria' => $this->rotuloCategoria((string) ($publicacao['categoria'] ?? '')),
            'resumo' => (string) ($publicacao['resumo'] ?? ''),
            'href' => $this->hrefPublicacao($publicacao),
        ];
    }

    private function rotuloCategoria(string $chave): string
    {
        foreach ($this->conteudoDinamico->categoriasPublicacao() as $categoria) {
            if (! is_array($categoria) || ($categoria['chave'] ?? null) !== $chave) {
                continue;
            }

            $rotulo = $categoria['rotulo'] ?? null;

            return is_string($rotulo) && $rotulo !== '' ? $rotulo : $chave;
        }

        return $chave;
    }

    /**
     * @param  array<string, mixed>  $publicacao
     */
    private function hrefPublicacao(array $publicacao): ?string
    {
        $slug = $publicacao['slug'] ?? null;

        if (! is_string($slug) || preg_match('/^[a-z0-9]+(?:-[a-z0-9]+)*$/', $slug) !== 1) {
            return null;
        }

        $rota = in_array($publicacao['categoria'] ?? null, $this->conteudoDinamico->chavesDoGrupo('editais-e-atas'), true)
            ? 'editais.show'
            : 'noticias.show';

        return route($rota, ['publicacao' => $slug]);
    }

    /**
     * @param  array<string, string>  $evento
     * @return array<string, string>
     */
    private function formatarEvento(array $evento): array
    {
        $inicio = $this->paraFusoDeExibicao($evento['inicio']);

        return [
            ...$evento,
            'data_iso' => $inicio->toIso8601String(),
            'data_formatada' => $inicio->format('d/m/Y'),
            'hora_formatada' => $inicio->format('H\hi'),
            'situacao' => self::SITUACOES_INSCRICAO[$evento['inscricoes']] ?? $evento['inscricoes'],
        ];
    }

    private function paraFusoDeExibicao(string $dataUtc): Carbon
    {
        return Carbon::parse($dataUtc, 'UTC')->setTimezone(config('laccrim.fuso_exibicao'));
    }
}
