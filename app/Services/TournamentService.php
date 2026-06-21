<?php

namespace App\Services;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use Illuminate\Database\Eloquent\Collection;

class TournamentService
{
    public function searchTournaments(string $search = null): Collection|array
    {
        $query = Tournament::query();

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('category', 'like', '%' . $search . '%');
        }

        return $query->get();
    }

    public function getTournamentWithDetails(int $id): Tournament
    {
        return Tournament::with([
            'matches.teamA',
            'matches.teamB',
            'matches.winner'
        ])->findOrFail($id);
    }

    public function getMatchWithDetails(int $id): TournamentMatch
    {
        return TournamentMatch::with([
            'tournament',
            'teamA',
            'teamB',
            'player_Infos.player'
        ])->findOrFail($id);
    }

    public function createTournament(array $data): Tournament
    {
        return Tournament::create($data);
    }

    public function updateTournament(Tournament $tournament, array $data): Tournament
    {
        $tournament->update($data);
        return $tournament;
    }

    public function deleteTournament(Tournament $tournament): void
    {
        $tournament->delete();
    }
}
