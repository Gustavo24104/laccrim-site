<?php

namespace App\Services;

use App\Contracts\ConteudoDinamicoRepository;
use App\Contracts\ConteudoSiteRepository;
use App\Enums\HierarquiaCorpoSocial;

/**
 * Monta a página Sobre Nós com o conteúdo institucional e as pessoas já aprovadas para exibição.
 */
class SobreService
{
    public function __construct(
        private ConteudoSiteRepository $conteudoSite,
        private ConteudoDinamicoRepository $conteudoDinamico,
    ) {}

    /**
     * @return array{
     *     banner: array{titulo: string, texto: string},
     *     quem_somos: array{titulo: string, blocos: list<array{chave: string, titulo: string, texto: string}>},
     *     estatuto: array{titulo: string, arquivo: ?string},
     *     corpo_social: array{titulo: string, niveis: list<array{rotulo: string, cor: string}>, integrantes: list<array<string, mixed>>},
     *     professores: array{titulo: string, itens: list<array<string, mixed>>}
     * }
     */
    public function handle(): array
    {
        $pagina = $this->conteudoSite->paginaSobre();

        return [
            'banner' => [
                'titulo' => (string) ($pagina['banner']['titulo'] ?? ''),
                'texto' => (string) ($pagina['banner']['texto'] ?? ''),
            ],
            'quem_somos' => [
                'titulo' => (string) ($pagina['quem_somos']['titulo'] ?? ''),
                'blocos' => array_values($pagina['quem_somos']['blocos'] ?? []),
            ],
            'estatuto' => [
                'titulo' => (string) ($pagina['estatuto']['titulo'] ?? ''),
                'arquivo' => $this->urlPublica($pagina['estatuto']['arquivo'] ?? null),
            ],
            'corpo_social' => [
                'titulo' => (string) ($pagina['corpo_social']['titulo'] ?? ''),
                'niveis' => array_map(
                    fn (HierarquiaCorpoSocial $nivel): array => [
                        'rotulo' => $nivel->rotulo(),
                        'cor' => $nivel->cor(),
                    ],
                    HierarquiaCorpoSocial::cases(),
                ),
                'integrantes' => $this->integrantes(),
            ],
            'professores' => [
                'titulo' => (string) ($pagina['professores']['titulo'] ?? ''),
                'itens' => $this->professores(),
            ],
        ];
    }

    /**
     * @return list<array{nome: string, subtitulo: string, complemento: null, rotulo_texto: string, texto: string, foto: ?string, hierarquia: array{rotulo: string, cor: string}}>
     */
    private function integrantes(): array
    {
        return collect($this->conteudoDinamico->corpoSocialPublico())
            ->map(function (array $pessoa): ?array {
                $nivel = HierarquiaCorpoSocial::tryFrom((string) ($pessoa['hierarquia'] ?? ''));

                if ($nivel === null) {
                    return null;
                }

                return [
                    'nome' => (string) ($pessoa['nome'] ?? ''),
                    'subtitulo' => (string) ($pessoa['cargo'] ?? ''),
                    'texto' => (string) ($pessoa['descricao'] ?? ''),
                    'foto' => $this->urlPublica(is_string($pessoa['foto'] ?? null) ? $pessoa['foto'] : null),
                    'hierarquia' => [
                        'rotulo' => $nivel->rotulo(),
                        'cor' => $nivel->cor(),
                    ],
                    'ordem_nivel' => $nivel->ordem(),
                    'ordem' => (int) ($pessoa['ordem'] ?? 0),
                ];
            })
            ->filter()
            ->sortBy([
                ['ordem_nivel', 'asc'],
                ['ordem', 'asc'],
                ['nome', 'asc'],
            ])
            ->map(fn (array $pessoa): array => [
                'nome' => $pessoa['nome'],
                'subtitulo' => $pessoa['subtitulo'],
                'complemento' => null,
                'rotulo_texto' => 'Descrição',
                'texto' => $pessoa['texto'],
                'foto' => $pessoa['foto'],
                'hierarquia' => $pessoa['hierarquia'],
            ])
            ->values()
            ->all();
    }

    /**
     * @return list<array{nome: string, titulacao: string, area_atuacao: string, apresentacao: string, foto: ?string}>
     */
    private function professores(): array
    {
        return collect($this->conteudoDinamico->professoresOrientadoresPublicos())
            ->map(fn (array $pessoa): array => [
                'nome' => (string) ($pessoa['nome'] ?? ''),
                'titulacao' => (string) ($pessoa['titulacao'] ?? ''),
                'area_atuacao' => (string) ($pessoa['area_atuacao'] ?? ''),
                'apresentacao' => (string) ($pessoa['apresentacao'] ?? ''),
                'foto' => $this->urlPublica(is_string($pessoa['foto'] ?? null) ? $pessoa['foto'] : null),
                'ordem' => (int) ($pessoa['ordem'] ?? 0),
            ])
            ->sortBy([
                ['ordem', 'asc'],
                ['nome', 'asc'],
            ])
            ->map(fn (array $pessoa): array => [
                'nome' => $pessoa['nome'],
                'titulacao' => $pessoa['titulacao'],
                'area_atuacao' => $pessoa['area_atuacao'],
                'apresentacao' => $pessoa['apresentacao'],
                'foto' => $pessoa['foto'],
            ])
            ->values()
            ->all();
    }

    private function urlPublica(mixed $url): ?string
    {
        if (! is_string($url) || $url === '') {
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
}
