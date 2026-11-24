<?php

namespace App\Services\Portal\Cms;

use App\Contracts\ConteudoEditavelRepository;
use App\Enums\SituacaoConteudo;
use App\Models\Portal\ConteudoEditavel;
use App\Models\User;
use App\Notifications\ConteudoAguardandoAprovacaoNotification;
use App\Notifications\ConteudoRevisadoNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

/**
 * Solicitação, aprovação (publicação) e devolução dos rascunhos do site.
 */
class FluxoAprovacaoConteudoService
{
    public function __construct(
        private ConteudoEditavelRepository $conteudos,
        private CatalogoAreasConteudo $catalogo,
        private SobreposicaoConteudoService $sobreposicao,
        private RegraAprovacaoConteudoService $regraAprovacao,
    ) {}

    /**
     * @return int Quantidade de aprovadores notificados.
     */
    public function solicitarAprovacao(AreaConteudo $area, User $solicitante): int
    {
        $registro = $this->rascunhoDe($area);

        $this->conteudos->marcarAguardandoAprovacao($registro, $solicitante);

        $aprovadores = $this->regraAprovacao->aprovadoresExceto($solicitante);

        Notification::send($aprovadores, new ConteudoAguardandoAprovacaoNotification(
            $this->rotuloCompleto($area),
            $solicitante->name,
            route('portal.conteudo.edit', $area->chave),
        ));

        return $aprovadores->count();
    }

    public function publicar(AreaConteudo $area, User $publicador): void
    {
        $registro = $this->rascunhoDe($area);
        $solicitante = $registro->solicitante;

        $this->conteudos->publicarRascunho(
            $registro,
            $this->sobreposicao->valoresVigentes($area),
            $publicador,
            $this->catalogo->versoesMantidas(),
        );

        $this->avisarSolicitante($area, $solicitante, $publicador, aprovado: true);
    }

    public function devolver(AreaConteudo $area, User $revisor, ?string $motivo): void
    {
        $registro = $this->conteudos->buscarPorChave($area->chave);

        if ($registro?->situacao !== SituacaoConteudo::AguardandoAprovacao) {
            throw ValidationException::withMessages(['conteudo' => 'Não há solicitação de aprovação pendente nesta área.']);
        }

        $solicitante = $registro->solicitante;

        $this->conteudos->devolverParaRascunho($registro);

        $this->avisarSolicitante($area, $solicitante, $revisor, aprovado: false, motivo: $motivo);
    }

    private function rascunhoDe(AreaConteudo $area): ConteudoEditavel
    {
        $registro = $this->conteudos->buscarPorChave($area->chave);

        if (! is_array($registro?->rascunho)) {
            throw ValidationException::withMessages(['conteudo' => 'Salve um rascunho antes de continuar.']);
        }

        return $registro;
    }

    private function avisarSolicitante(AreaConteudo $area, ?User $solicitante, User $revisor, bool $aprovado, ?string $motivo = null): void
    {
        if ($solicitante === null || $solicitante->is($revisor)) {
            return;
        }

        $solicitante->notify(new ConteudoRevisadoNotification(
            $this->rotuloCompleto($area),
            $aprovado,
            $revisor->name,
            $motivo,
            route('portal.conteudo.edit', $area->chave),
        ));
    }

    private function rotuloCompleto(AreaConteudo $area): string
    {
        return "{$area->pagina} – {$area->rotulo}";
    }
}
