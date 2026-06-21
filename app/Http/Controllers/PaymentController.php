<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Team;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function checkout($id)
    {
        return view('payment.checkout', ['tournamentId' => $id]);
    }

    public function processSimulation(Request $request)
    {
        $request->validate([
            'tournament_id' => 'required',
            'metodo' => 'required|in:pix,card'
        ]);

        $order = Order::create([
            'user_id' => Auth::id(),
            'tournament_id' => $request->tournament_id,
            'amount' => 50.00,
            'status' => 'pendente',
            'method' => $request->metodo
        ]);

        // Redireciona para o fluxo correspondente
        if ($request->metodo === 'pix') {
            return redirect()->route('payment.processing', $order->id);
        }

        // Se for cartão, vamos para a mesma tela de processamento, 
        // mas você pode criar uma específica se quiser mudar o visual
        return redirect()->route('payment.processing', $order->id);
    }

    public function processing($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('payment.processing', compact('order'));
    }
    public function confirmPayment($orderId)
    {
        $order = Order::findOrFail($orderId);

        if ($order->status !== 'pago') {
            $order->update(['status' => 'pago']);

            $user = User::find($order->user_id);
            $tournament = Tournament::find($order->tournament_id);

            if ($user->team_id && !$tournament->teams()->where('team_id', $user->team_id)->exists()) {
                $tournament->teams()->attach($user->team_id, [
                    'created_at' => now(),
                    'status' => 'confirmado'
                ]);
            }
            $tournament->increment('current_participants');

            $team = Team::with('users')->find($user->team_id);

            foreach ($team->users as $player) {
                $player->increment('tournaments');
            }

        }

        return redirect()->route('payment.success', $orderId);
    }

    public function success($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('payment.success', ['order' => $order]);
    }
}