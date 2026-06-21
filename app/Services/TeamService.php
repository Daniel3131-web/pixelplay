<?php

namespace App\Services;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeamService
{
    public function createTeam(array $data, User $leader): Team
    {
        return DB::transaction(function () use ($data, $leader) {
            $team = new Team();
            $team->name = $data['name'];
            $team->acronym = $data['acronym'];
            $team->description = $data['description'] ?? null;
            $team->privacy = $data['privacy'];
            $team->leader_id = $leader->id;

            if ($data['privacy'] === 'private' && isset($data['password'])) {
                $team->password = Hash::make($data['password']);
            }

            if (isset($data['img'])) {
                $team->img = $this->uploadImage($data['img']);
            }

            $team->save();
            $leader->update(['team_id' => $team->id]);

            return $team;
        });
    }

    public function leaveTeam(User $user): void
    {
        if (!$user->team_id) {
            throw new \Exception('Usuário não está em nenhum time.');
        }

        $team = Team::findOrFail($user->team_id);

        DB::transaction(function () use ($user, $team) {
            if ($team->leader_id === $user->id) {
                $nextLeader = User::where('team_id', $team->id)
                    ->where('id', '!=', $user->id)
                    ->first();

                if ($nextLeader) {
                    $team->update(['leader_id' => $nextLeader->id]);
                } else {
                    $team->delete();
                }
            }

            $user->update(['team_id' => null]);
        });
    }

    public function joinTeam(User $user, Team $team, ?string $password = null): void
    {
        if ($user->team_id === $team->id) {
            throw new \Exception('Você já está no time!');
        }

        if ($user->team_id) {
            throw new \Exception('Você já pertence a outra equipe! Saia dela primeiro.');
        }

        $team->loadCount('users');

        if ($team->users_count >= $team->max_participants) {
            throw new \Exception('Esse time já está cheio.');
        }

        if ($team->privacy !== 'public') {
            if (!$password || !Hash::check($password, $team->password)) {
                throw new \Exception('Senha incorreta!');
            }
        }

        $user->update(['team_id' => $team->id]);
    }

    private function uploadImage($file): string
    {
        if (!$file->isValid()) {
            throw new \Exception('Arquivo inválido.');
        }

        $extension = $file->extension();
        $imageName = md5($file->getClientOriginalName() . strtotime('now')) . '.' . $extension;
        $file->move(public_path('/assets/teams/'), $imageName);

        return '/assets/teams/' . $imageName;
    }
}
