<?php

namespace App\Http\Controllers\Portal;

use App\Enums\PermissaoPortal;
use App\Http\Controllers\Controller;
use App\Http\Requests\Portal\SalvarConteudoRequest;
use App\Services\Portal\Cms\AreaConteudo;
use App\Services\Portal\Cms\EdicaoConteudoService;
use App\Services\Portal\Cms\FluxoAprovacaoConteudoService;
use App\Services\Portal\Cms\PreviaConteudoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConteudoController extends Controller
{
    public function index(EdicaoConteudoService $edicao): View
    {
        return view('paginas.portal.conteudo.index', [
            'titulo' => 'Conteúdo do site',
            'linhas' => collect($edicao->resumo())->groupBy(fn (array $linha): string => $linha['area']->pagina),
        ]);
    }

    public function edit(Request $request, AreaConteudo $area, EdicaoConteudoService $edicao, PreviaConteudoService $previa): View
    {
        return view('paginas.portal.conteudo.editar', [
            'titulo' => 'Editar conteúdo',
            'area' => $area,
            'painel' => $edicao->painel($area),
            'urlPrevia' => $previa->urlPrevia($area),
            'podeAprovar' => $request->user()->can(PermissaoPortal::AprovarSite->value),
        ]);
    }

    public function update(
        SalvarConteudoRequest $request,
        AreaConteudo $area,
        EdicaoConteudoService $edicao,
        FluxoAprovacaoConteudoService $fluxo,
    ): RedirectResponse {
        $edicao->salvarRascunho($area, $request->validated('campos'), $request->user());

        $status = match ($request->validated('acao')) {
            SalvarConteudoRequest::ACAO_SOLICITAR => $this->mensagemSolicitacao($fluxo->solicitarAprovacao($area, $request->user())),
            SalvarConteudoRequest::ACAO_PUBLICAR => $this->publicar($fluxo, $area, $request),
            default => 'Rascunho salvo. O site só muda depois da aprovação.',
        };

        return redirect()->route('portal.conteudo.edit', $area->chave)->with('status', $status);
    }

    private function publicar(FluxoAprovacaoConteudoService $fluxo, AreaConteudo $area, Request $request): string
    {
        $fluxo->publicar($area, $request->user());

        return 'Alteração publicada no site.';
    }

    private function mensagemSolicitacao(int $notificados): string
    {
        return $notificados === 0
            ? 'Aprovação solicitada. Nenhum aprovador disponível foi encontrado para notificar.'
            : 'Aprovação solicitada. '.($notificados === 1 ? '1 aprovador foi notificado.' : "{$notificados} aprovadores foram notificados.");
    }
}
