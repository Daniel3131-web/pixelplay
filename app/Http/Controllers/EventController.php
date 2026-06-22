<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Exibe a listagem de eventos com suporte a busca.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $events = Event::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        })->latest()->get();

        return view('player.eventos', compact('events', 'search'));
    }

    /**
     * Exibe os detalhes de um evento específico.
     */
    public function show($id): View
    {
        $event = Event::with('tournaments')->findOrFail($id);

        return view('player.evento', compact('event'));
    }

    public function meusEventos()
{
    $user = Auth::user();

    $orders = Order::where('user_id', $user->id)
                   ->where('status', 'pago')
                   ->with(['event', 'tournament'])
                   ->get();

    $eventOrders = $orders->filter(fn($order) => $order->event_id !== null);
    $tournamentOrders = $orders->filter(fn($order) => $order->tournament_id !== null);

    return view('player.meus-eventos', [
        'eventOrders' => $eventOrders,
        'tournamentOrders' => $tournamentOrders
    ]);
}
}