<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tournament;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrgController extends Controller
{
    public function index()
    {
        $tournaments = Tournament::where('user_id', Auth::id())->get();

        $totalTournaments = $tournaments->count();

        return view('org.dashboard', [
            'tournaments' => $tournaments,
            'totalTournaments' => $totalTournaments,
        ]);
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
            'name' => 'required|string|unique:tournaments,name|max:255',
            'description' => 'nullable|string',
            'category' => 'required|in:valorant,cs2,lol,mlbb,ow2,mr',
            'max_participants' => 'required|in:4,8,16',
            'entrance_fee' => 'required|numeric|min:0',
            'awards' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required',
            'end_time' => 'required',
            'entry_date' => 'required|date|before_or_equal:start_date',
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $organizer = Auth::user();

        $tournament = DB::transaction(function () use ($request, $organizer) {
            $newTournament = new Tournament();

            $newTournament->user_id = $organizer->id;
            $newTournament->name = $request->name;
            $newTournament->description = $request->description;
            $newTournament->category = $request->category;
            $newTournament->max_participants = $request->max_participants;
            $newTournament->entrance_fee = $request->entrance_fee;
            $newTournament->awards = $request->awards;
            $newTournament->start_date = $request->start_date;
            $newTournament->end_date = $request->end_date;
            $newTournament->start_time = $request->start_time;
            $newTournament->end_time = $request->end_time;
            $newTournament->entry_date = $request->entry_date;
            $newTournament->status = 'Agendado';

            if ($request->hasFile('img') && $request->file('img')->isValid()) {
                $requestImage = $request->img;
                $extension = $requestImage->extension();
                $imageName = md5($requestImage->getClientOriginalName() . strtotime("now")) . "." . $extension;

                $requestImage->move(public_path('/assets/tournaments/'), $imageName);
                $newTournament->img = "/assets/tournaments/" . $imageName;
            }

            $newTournament->save();

            return $newTournament;
        });

        return redirect()->route('org.dashboard')
            ->with('success', 'Torneio criado com sucesso!');
    }

    public function edit($id)
    {
        $tournament = Tournament::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        return view('org.torneio-edit', ['tournament' => $tournament]);
    }

    public function update(Request $request, $id)
    {
        $tournament = Tournament::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        $tournament->update($request->except('img'));

        if ($request->hasFile('img') && $request->file('img')->isValid()) {
            $imageName = md5($request->img->getClientOriginalName() . strtotime("now")) . "." . $request->img->extension();
            $request->img->move(public_path('/assets/tournaments/'), $imageName);
            $tournament->img = "/assets/tournaments/" . $imageName;
            $tournament->save();
        }

        return redirect()->route('org.dashboard')->with('success', 'Torneio atualizado!');
    }

    public function destroy($id)
    {
        $tournament = Tournament::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($tournament->img) {
            $imagePath = public_path($tournament->img);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $tournament->delete();

        return redirect()->route('org.dashboard')->with('msg', 'Torneio removido com sucesso!');
    }
}