<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function checkout(int $id, string $type)
    {
        $user = Auth::user();

        if ($type === 'event') {
            $event = Event::findOrFail($id);

            // Verifica se o evento tem limite de vagas e se já lotou
            if (isset($event->max_participants) && $event->current_participants >= $event->max_participants) {
                return redirect()->back()->withErrors(['error' => 'Desculpe, este evento já está lotado.']);
            }

            return view('payment.checkout', ['eventId' => $id]);
        }

        if ($type === 'tournament') {
            $tournament = Tournament::findOrFail($id);

            // Validação A: Verifica se o torneio já atingiu o limite máximo de times
            if (isset($tournament->max_participants) && $tournament->current_participants >= $tournament->max_participants) {
                return redirect()->back()->withErrors(['error' => 'Desculpe, este torneio já atingiu o limite máximo de times inscritos.']);
            }

            $team = $user->User_Team;

            // Validação B: Verifica se o usuário sequer possui um time
            if (!$team) {
                return redirect()->back()->withErrors(['error' => 'Você precisa estar em um time para se inscrever neste torneio.']);
            }

            // Validação C: Verifica se quem está tentando comprar é o Líder do time
            if ($team->leader_id !== $user->id) {
                return redirect()->back()->withErrors(['error' => 'Acesso negado: Apenas o líder do time pode realizar a inscrição e o pagamento.']);
            }

            // Validação D: Verifica se o time possui exatamente 5 membros
            if ($team->users()->count() !== 5) {
                return redirect()->back()->withErrors(['error' => 'Seu time precisa ter exatamente 5 membros ativos para participar deste torneio.']);
            }

            return view('payment.checkout', ['tournamentId' => $id]);
        }

        abort(400, 'Tipo de checkout inválido.');
    }

    public function processSimulation(Request $request)
    {
        $request->validate([
            'tournament_id' => 'nullable|integer',
            'event_id' => 'nullable|integer',
            'metodo' => 'required|in:pix,card'
        ]);

        if (!$request->tournament_id && !$request->event_id) {
            return back()->withErrors(['error' => 'Nenhum item selecionado para o checkout.']);
        }

        $amount = 0;
        $isTeamPayment = false;

        if ($request->tournament_id) {
            $tournament = Tournament::findOrFail($request->tournament_id);
            // Multiplica o valor unitário por 5 
            $amount = $tournament->entrance_fee * 5;
            $isTeamPayment = true;
        } elseif ($request->event_id) {
            $event = Event::findOrFail($request->event_id);
            $amount = $event->entrance_fee; // Valor individual
            $isTeamPayment = false;
        }

        $order = Order::create([
            'user_id' => Auth::id(),
            'tournament_id' => $request->tournament_id,
            'event_id' => $request->event_id,
            'amount' => $amount,
            'status' => 'pendente',
            'metodo' => $request->metodo,
            'is_team_payment' => $isTeamPayment
        ]);
        return redirect()->route('payment.processing', $order->id);
    }



    public function processing(int $orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('payment.processing', compact('order'));
    }

    public function confirmPayment(int $orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->status !== 'pago') {
            $order->update(['status' => 'pago']);
            $user = User::find($order->user_id);

            if ($order->tournament_id) {
                $tournament = Tournament::find($order->tournament_id);

                if ($user->team_id && !$tournament->teams()->where('team_id', $user->team_id)->exists()) {
                    $tournament->teams()->attach($user->team_id, [
                        'created_at' => now(),
                        'status' => 'confirmado'
                    ]);
                }

                $tournament->increment('current_participants');

                $this->liberarIngressosParaTime($order->id);

                $team = Team::with('users')->find($user->team_id);
                if ($team) {
                    foreach ($team->users as $player) {
                        $player->increment('tournaments');
                    }
                }
            }

            if ($order->event_id) {
                $user->increment('events');
                $event = Event::find($order->event_id);
                $event->increment('current_participants');
                $event->users()->syncWithoutDetaching([$user->id => ['status' => 'confirmado']]);
            }
        }

        return redirect()->route('payment.success', $orderId);
    }

    public function success(int $orderId)
    {
        // Busca a ordem ou lança erro 404 se não existir
        $order = Order::findOrFail($orderId);

        if ($order->user_id !== Auth::id()) {
            abort(403, 'Você não tem permissão para visualizar esta transação.');
        }

        return view('payment.success', ['order' => $order]);
    }

    public function liberarIngressosParaTime(int $masterOrderId)
    {
        $masterOrder = Order::findOrFail($masterOrderId);

        if ($masterOrder->is_team_payment && $masterOrder->status === 'pago') {

            $team = $masterOrder->user->User_Team;

            foreach ($team->users as $member) {
                Order::create([
                    'user_id' => $member->id,
                    'tournament_id' => $masterOrder->tournament_id,
                    'event_id' => null,
                    'amount' => 0.00,
                    'status' => 'pago',
                    'metodo' => 'time_payment',
                    'is_team_payment' => false
                ]);
            }

            return true;
        }
        return false;
    }

    public function validateEntry(int $orderId)
    {
        $order = Order::with('user')->findOrFail($orderId);

        if ($order->status !== 'pago') {
            return view('admin.checkin_error', [
                'message' => 'Pagamento não identificado ou pendente.'
            ]);
        }

        if ($order->checked_in === true) {
            return view('admin.checkin_error', [
                'message' => 'Acesso Negado: Este ingresso já foi validado na entrada!'
            ]);
        }

        $order->update([
            'checked_in' => true
        ]);

        return view('admin.checkin_success', [
            'user' => $order->user,
            'order' => $order
        ]);
    }
}