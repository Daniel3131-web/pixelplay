<?php

namespace App\Http\Controllers;

use App\Models\Event;
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
    $events = Auth::user()->events()->latest()->get();

        return view('player.meus-eventos', compact('events'));
    }
}