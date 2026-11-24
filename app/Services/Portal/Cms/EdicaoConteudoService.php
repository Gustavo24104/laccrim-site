<?php

namespace App\Services\Portal\Cms;

use App\Contracts\ConteudoEditavelRepository;
use App\Contracts\SanitizadorHtml;
use App\Enums\SituacaoConteudo;
use App\Models\Portal\ConteudoEditavel;
use App\Models\Portal\VersaoConteudo;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Validation\ValidationException;

/**
 * Edição de rascunhos das áreas do site e restauração de versões anteriores.
 */
class EdicaoConteudoService
{
    public function __construct(
        private ConteudoEditavelRepository $conteudos,
        private CatalogoAreasConteudo $catalogo,
        private SobreposicaoConteudoService $sobreposicao,
        private SanitizadorHtml $sanitizador,
    ) {}

    /**
     * @return list<array{area: AreaConteudo, situacao: SituacaoConteudo, publicado_em: ?CarbonInterface, publicado_por: ?string}>
     */
    public function resumo(): array
    {
        $registros = $this->conteudos->todosIndexadosPorChave();
        $linhas = [];

        foreach ($this->catalogo->todas() as $area) {
            $registro = $registros[$area->chave] ?? null;

            $linhas[] = [
                'area' => $area,
                'situacao' => $registro->situacao ?? SituacaoConteudo::Publicado,
                'publicado_em' => $registro?->publicado_em,
                'publicado_por' => $registro?->publicador?->name,
            ];
        }

        return $linhas;
    }

    /**
     * @return array{
     *     situacao: SituacaoConteudo,
     *     possui_rascunho: bool,
     *     valores: array<string, string>,
     *     solicitante: ?string,
     *     solicitado_em: ?CarbonInterface,
     *     versoes: list<array{id: int, publicado_em: ?CarbonInterface, publicado_por: ?string}>
     * }
     */
    public function painel(AreaConteudo $area): array
    {
        $registro = $this->conteudos->buscarPorChave($area->chave)?->load(['solicitante', 'versoes.publicador']);

        return [
            'situacao' => $registro->situacao ?? SituacaoConteudo::Publicado,
            'possui_rascunho' => is_array($registro?->rascunho),
            'valores' => $this->valoresDoFormulario($area, $registro),
            'solicitante' => $registro?->solicitante?->name,
            'solicitado_em' => $registro?->aprovacao_solicitada_em,
            'versoes' => $registro === null ? [] : $registro->versoes->map(fn (VersaoConteudo $versao): array => [
                'id' => $versao->id,
                'publicado_em' => $versao->publicado_em,
                'publicado_por' => $versao->publicador?->name,
            ])->all(),
        ];
    }

    /**
     * Valores exibidos no formulário: o rascunho em edição ou o conteúdo em vigor.
     *
     * @return array<string, string>
     */
    private function valoresDoFormulario(AreaConteudo $area, ?ConteudoEditavel $registro): array
    {
        $vigentes = $this->sobreposicao->valoresVigentes($area);

        return is_array($registro?->rascunho) ? [...$vigentes, ...$registro->rascunho] : $vigentes;
    }

    /**
     * @param  array<string, mixed>  $valores
     */
    public function salvarRascunho(AreaConteudo $area, array $valores, User $editor): ConteudoEditavel
    {
        return $this->gravar($area, $this->conteudos->buscarPorChave($area->chave), $valores, $editor);
    }

    public function restaurarVersao(AreaConteudo $area, int $versaoId, User $editor): ConteudoEditavel
    {
        $registro = $this->conteudos->buscarPorChave($area->chave);
        $versao = $registro ? $this->conteudos->buscarVersao($registro, $versaoId) : null;

        if ($versao === null) {
            throw ValidationException::withMessages(['versao' => 'Versão não encontrada.']);
        }

        return $this->gravar($area, $registro, [...$this->sobreposicao->valoresVigentes($area), ...$versao->conteudo], $editor);
    }

    /**
     * Um rascunho já enviado para aprovação continua aguardando; os demais viram rascunho em edição.
     *
     * @param  array<string, mixed>  $valores
     */
    private function gravar(AreaConteudo $area, ?ConteudoEditavel $registro, array $valores, User $editor): ConteudoEditavel
    {
        $situacao = $registro?->situacao === SituacaoConteudo::AguardandoAprovacao
            ? SituacaoConteudo::AguardandoAprovacao
            : SituacaoConteudo::Rascunho;

        return $this->conteudos->salvarRascunho($area->chave, $this->normalizar($area, $valores), $editor, $situacao);
    }

    /**
     * Mantém só os campos da área, como texto, e sanitiza os campos de HTML.
     *
     * @param  array<string, mixed>  $valores
     * @return array<string, string>
     */
    private function normalizar(AreaConteudo $area, array $valores): array
    {
        $camposHtml = $area->camposHtml();
        $normalizados = [];

        foreach ($area->nomesDosCampos() as $nome) {
            $valor = trim((string) ($valores[$nome] ?? ''));
            $normalizados[$nome] = in_array($nome, $camposHtml, true) ? $this->sanitizador->limpar($valor) : $valor;
        }

        return $normalizados;
    }
}
