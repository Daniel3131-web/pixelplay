<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User; 
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $realTeams = [
            ['name' => 'LOUD', 'acronym' => 'LLL', 'img' => 'teams/loud.png'],
            ['name' => 'FURIA Esports', 'acronym' => 'FUR', 'img' => 'teams/furia.png'],
            ['name' => 'MIBR', 'acronym' => 'MBR', 'img' => 'teams/mibr.png'],
            ['name' => 'Sentinels', 'acronym' => 'SEN', 'img' => 'teams/sentinels.png'],
            ['name' => 'Fnatic', 'acronym' => 'FNC', 'img' => 'teams/fnatic.png'],
            ['name' => 'Paper Rex', 'acronym' => 'PRX', 'img' => 'teams/prx.png'],
            ['name' => 'Team Vitality', 'acronym' => 'VIT', 'img' => 'teams/vitality.png'],
            ['name' => 'G2 Esports', 'acronym' => 'G2', 'img' => 'teams/g2.png'],
            ['name' => 'DRX', 'acronym' => 'DRX', 'img' => 'teams/drx.png'],
            ['name' => 'Natus Vincere', 'acronym' => 'NAVI', 'img' => 'teams/navi.png'],
            ['name' => '100 Thieves', 'acronym' => '100T', 'img' => 'teams/100t.png'],
            ['name' => 'Cloud9', 'acronym' => 'C9', 'img' => 'teams/c9.png'],
            ['name' => 'NRG Esports', 'acronym' => 'NRG', 'img' => 'teams/nrg.png'],
            ['name' => 'Team Liquid', 'acronym' => 'TL', 'img' => 'teams/tl.png'],
            ['name' => 'ZETA DIVISION', 'acronym' => 'ZETA', 'img' => 'teams/zeta.png'],
            ['name' => 'T1 Esports', 'acronym' => 'T1', 'img' => 'teams/t1.png'],
        ];


        static $teamCount = 0;

        if ($teamCount < count($realTeams)) {
            $selectedTeam = $realTeams[$teamCount];

            $selectedTeam['privacy'] = 'private'; 
            $selectedTeam['password'] = Hash::make('12345678');
            $selectedTeam['description'] = 'Time de Esport Profissional';

            $teamCount++;
        } else {
            // Caso você queira criar mais de 16 times futuramente, o fallback evita quebras
            $word = $this->faker->unique()->word();
            $selectedTeam = [
                'name'    => 'Team ' . ucfirst($word),
                'acronym' => strtoupper(substr($word, 0, 3)),
                'privacy' => 'public',
                'password' => null,
                'description' => 'Time de Esport Amador',
                'img'     => 'teams/default.png'
            ];
        }

        return [
            'name'    => $selectedTeam['name'],
            'acronym' => $selectedTeam['acronym'],
            'privacy'     => $selectedTeam['privacy'],
            'password' => $selectedTeam['password'],
            'img'     => $selectedTeam['img']
            
        ];
    }
}
