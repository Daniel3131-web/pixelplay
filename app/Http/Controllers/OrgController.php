<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament; // 1. CORRIGIDO: Importação da Model que estava faltando

class OrgController extends Controller
{
    public function index() 
    {
        return view('org.dashboard');
    }

    public function create()
    {
        return view('org.torneio-create'); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:valorant,cs2,lol,mlbb,ow2,mr',
            'max_participants' => 'required|in:4,8,16',
            'status' => 'required|in:Aberto,Agendado',
            'entrance_fee' => 'required|numeric|min:0',
            'awards' => 'required|numeric|min:0',
            'entry_date' => 'required|date',
            'start_date' => 'required|date|after_or_equal:entry_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'description' => 'required|string',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $tournament = new Tournament;

        $tournament->name = $request->name;
        $tournament->category = $request->category;
        $tournament->max_participants = $request->max_participants;
        $tournament->status = $request->status;
        $tournament->entrance_fee = $request->entrance_fee;
        $tournament->awards = $request->awards;
        $tournament->entry_date = $request->entry_date;
        $tournament->start_date = $request->start_date;
        $tournament->end_date = $request->end_date;
        $tournament->start_time = $request->start_time;
        $tournament->end_time = $request->end_time;
        $tournament->description = $request->description;

        if ($request->hasFile('img') && $request->file('img')->isValid()) {

            $requestImage = $request->img;
            $extension = $requestImage->extension();
            $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

            $requestImage->move(public_path('/assets/tournaments/banner/'), $imageName);

            $tournament->img = "/assets/tournaments/banner/" . $imageName;
        }

        $tournament->save();

        return redirect()->route('org.dashboard')->with('msg', 'Torneio publicado com sucesso!');
    }
}