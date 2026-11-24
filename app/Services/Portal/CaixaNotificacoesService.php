<?php

namespace App\Services\Portal;

use App\Contracts\NotificacaoRepository;
use App\Enums\TipoNotificacao;
use App\Models\User;
use Carbon\CarbonInterface;
use Illuminate\Notifications\DatabaseNotification;

/**
 * Notificações exibidas no sino do portal: lista recente, contagem e marcação como lida.
 */
class CaixaNotificacoesService
{
    private const LIMITE_PAINEL = 15;

    public function __construct(private NotificacaoRepository $notificacoes) {}

    /**
     * @return array{nao_lidas: int, itens: list<array{id: string, tipo: string, classes_selo: string, titulo: string, mensagem: string, tempo: string, lida: bool}>}
     */
    public function painel(User $usuario): array
    {
        return [
            'nao_lidas' => $this->notificacoes->totalNaoLidas($usuario),
            'itens' => $this->notificacoes->recentes($usuario, self::LIMITE_PAINEL)
                ->map(fn (DatabaseNotification $notificacao): array => $this->formatar($notificacao))
                ->values()
                ->all(),
        ];
    }

    /**
     * @return ?string URL de destino da notificação, quando houver.
     */
    public function marcarComoLida(User $usuario, string $id): ?string
    {
        $notificacao = $this->notificacoes->buscarDoUsuario($usuario, $id);
        $this->notificacoes->marcarComoLida($notificacao);

        return $notificacao->data['url'] ?? null;
    }

    public function marcarTodasComoLidas(User $usuario): void
    {
        $this->notificacoes->marcarTodasComoLidas($usuario);
    }

    /**
     * Até 4 semanas mostra há quanto tempo a notificação chegou; depois disso, a data.
     */
    public function tempoDecorrido(CarbonInterface $momento): string
    {
        $minutos = (int) floor($momento->diffInMinutes(now(), true));
        $horas = intdiv($minutos, 60);
        $dias = intdiv($horas, 24);
        $semanas = intdiv($dias, 7);

        return match (true) {
            $minutos < 1 => 'agora mesmo',
            $horas < 1 => $this->ha($minutos, 'minuto', 'minutos'),
            $dias < 1 => $this->ha($horas, 'hora', 'horas'),
            $semanas < 1 => $this->ha($dias, 'dia', 'dias'),
            $semanas <= 4 => $this->ha($semanas, 'semana', 'semanas'),
            default => $momento->clone()->setTimezone(config('laccrim.fuso_exibicao'))->format('d/m/Y'),
        };
    }

    /**
     * @return array{id: string, tipo: string, classes_selo: string, titulo: string, mensagem: string, tempo: string, lida: bool}
     */
    private function formatar(DatabaseNotification $notificacao): array
    {
        $tipo = TipoNotificacao::tryFrom((string) ($notificacao->data['tipo'] ?? '')) ?? TipoNotificacao::Aviso;

        return [
            'id' => $notificacao->id,
            'tipo' => $tipo->rotulo(),
            'classes_selo' => $tipo->classesSelo(),
            'titulo' => (string) ($notificacao->data['titulo'] ?? ''),
            'mensagem' => (string) ($notificacao->data['mensagem'] ?? ''),
            'tempo' => $this->tempoDecorrido($notificacao->created_at),
            'lida' => $notificacao->read_at !== null,
        ];
    }

    private function ha(int $quantidade, string $singular, string $plural): string
    {
        return 'há '.$quantidade.' '.($quantidade === 1 ? $singular : $plural);
    }
}
