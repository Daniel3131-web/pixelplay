<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    private const EXPORT_TYPES = ['all', 'events', 'players', 'abandonos'];

    /**
     * Status da pivot `event_user` considerados "ativos" (o jogador
     * confirmou e permaneceu no evento). Qualquer outro valor gravado
     * nesse campo (ex: 'cancelado', 'ausente', 'wo') conta como abandono.
     * Hoje o PaymentController só grava 'confirmado', então ajuste esta
     * lista quando passar a registrar outros status de presença.
     */
    private const ACTIVE_STATUSES = ['confirmado'];

    public function index(Request $request)
    {
        $organizerId = Auth::id();

        // IDs dos eventos criados por ESTE organizador — igual ao OrgController::index(),
        // que já usa where('user_id', Auth::id()) no dashboard. Tudo aqui embaixo
        // fica restrito a esses eventos pra bater com os números do dashboard.
        $organizerEventIds = Event::where('user_id', $organizerId)->pluck('id');

        $eventId = $request->integer('event_id') ?: null;
        // Se o event_id vier na URL mas não for de um evento deste organizador, ignora
        if ($eventId && ! $organizerEventIds->contains($eventId)) {
            $eventId = null;
        }

        $overview = [
            'total_events'  => $organizerEventIds->count(),
            'total_teams'   => Team::whereHas('tournaments', function ($query) use ($organizerEventIds) {
                $query->whereIn('event_id', $organizerEventIds);
            })->count(),
            'total_players' => DB::table('event_user')
                ->whereIn('event_id', $organizerEventIds)
                ->distinct()
                ->count('user_id'),
        ];

        // --- Taxa de abandono, baseada no status da inscrição (event_user) ---
        $dropoutQuery = DB::table('event_user')->whereIn('event_id', $organizerEventIds);
        if ($eventId) {
            $dropoutQuery->where('event_id', $eventId);
        }

        $totalInscricoes = (clone $dropoutQuery)->count();
        $ativos          = (clone $dropoutQuery)->whereIn('status', self::ACTIVE_STATUSES)->count();
        $abandonos       = $totalInscricoes - $ativos;

        $dropout = [
            'total'   => $totalInscricoes,
            'active'  => $ativos,
            'dropped' => $abandonos,
            'rate'    => $totalInscricoes > 0 ? round(($abandonos / $totalInscricoes) * 100, 2) : 0.0,
        ];

        // --- Engajamento: jogadores com 1 evento vs 2+ eventos (só contando os eventos deste organizador) ---
        $countsByPlayer = DB::table('event_user')
            ->whereIn('event_id', $organizerEventIds)
            ->select('user_id', DB::raw('COUNT(event_id) as events_count'))
            ->groupBy('user_id')
            ->get();

        $totalJogadores = $countsByPlayer->count();
        $recorrentes    = $countsByPlayer->where('events_count', '>', 1)->count();

        $engagement = [
            'total'        => $totalJogadores,
            'single_event' => $totalJogadores - $recorrentes,
            'recurrent'    => $recorrentes,
            'rate'         => $totalJogadores > 0 ? round(($recorrentes / $totalJogadores) * 100, 2) : 0.0,
        ];

        $events = Event::where('user_id', $organizerId)
            ->select('id', 'name')
            ->orderByDesc('created_at')
            ->get();

        return view('org.reports.index', compact('overview', 'dropout', 'engagement', 'events', 'eventId'));
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'type' => ['nullable', Rule::in(self::EXPORT_TYPES)],
        ]);

        $type              = $validated['type'] ?? 'all';
        $organizerEventIds = Event::where('user_id', Auth::id())->pluck('id');
        $filename          = "relatorio_pixelplay_{$type}_" . now()->format('Y-m-d_His') . '.csv';

        return Response::streamDownload(function () use ($type, $organizerEventIds) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8: evita acentuação quebrada ao abrir no Excel
            fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            if ($type === 'all' || $type === 'events') {
                $this->writeEventsSection($handle, $organizerEventIds);
            }
            if ($type === 'all' || $type === 'abandonos') {
                $this->writeDropoutSection($handle, $organizerEventIds);
            }
            if ($type === 'all' || $type === 'players') {
                $this->writePlayersSection($handle, $organizerEventIds);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    private function writeEventsSection($handle, $organizerEventIds): void
    {
        fputcsv($handle, ['--- RELATÓRIO DE EVENTOS ---']);
        fputcsv($handle, ['ID', 'Nome do Evento', 'Tipo', 'Data de Criação']);

        Event::query()
            ->whereIn('id', $organizerEventIds)
            ->orderBy('id')
            ->chunk(200, function ($events) use ($handle) {
                foreach ($events as $event) {
                    fputcsv($handle, [
                        $event->id,
                        $event->name,
                        $event->type ?? '-',
                        optional($event->created_at)->format('d/m/Y H:i'),
                    ]);
                }
            });

        fputcsv($handle, []);
    }

    private function writeDropoutSection($handle, $organizerEventIds): void
    {
        fputcsv($handle, ['--- RELATÓRIO DE ABANDONOS / STATUS DE INSCRIÇÃO ---']);
        fputcsv($handle, ['ID do Jogador', 'Nome do Jogador', 'Evento', 'Status']);

        DB::table('event_user')
            ->join('users', 'users.id', '=', 'event_user.user_id')
            ->join('events', 'events.id', '=', 'event_user.event_id')
            ->whereIn('event_user.event_id', $organizerEventIds)
            ->whereNotIn('event_user.status', self::ACTIVE_STATUSES)
            ->select('users.id', 'users.name', 'events.name as event_name', 'event_user.status')
            ->orderBy('users.id')
            ->chunk(200, function ($rows) use ($handle) {
                foreach ($rows as $row) {
                    fputcsv($handle, [$row->id, $row->name, $row->event_name, $row->status]);
                }
            });

        fputcsv($handle, []);
    }

    private function writePlayersSection($handle, $organizerEventIds): void
    {
        fputcsv($handle, ['--- RELATÓRIO DE JOGADORES (ENGAJAMENTO) ---']);
        fputcsv($handle, ['ID', 'Nome do Jogador', 'E-mail', 'Eventos Participados (neste organizador)', 'Data de Cadastro']);

        // Só jogadores que já se inscreveram em algum evento deste organizador
        User::query()
            ->whereHas('user_events', function ($query) use ($organizerEventIds) {
                $query->whereIn('events.id', $organizerEventIds);
            })
            ->withCount(['user_events' => function ($query) use ($organizerEventIds) {
                $query->whereIn('events.id', $organizerEventIds);
            }])
            ->orderBy('id')
            ->chunk(200, function ($users) use ($handle) {
                foreach ($users as $user) {
                    fputcsv($handle, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->user_events_count,
                        optional($user->created_at)->format('d/m/Y H:i'),
                    ]);
                }
            });
    }
}