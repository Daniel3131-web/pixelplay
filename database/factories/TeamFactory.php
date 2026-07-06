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
            ['name' => 'LOUD', 'acronym' => 'LLL', 'img' => 'assets/teams/loud.png'],
            ['name' => 'FURIA Esports', 'acronym' => 'FUR', 'img' => 'assets/teams/furia.png'],
            ['name' => 'MIBR', 'acronym' => 'MBR', 'img' => 'assets/teams/mibr.png'],
            ['name' => 'Sentinels', 'acronym' => 'SEN', 'img' => 'assets/teams/sentinels.png'],
            ['name' => 'Fnatic', 'acronym' => 'FNC', 'img' => 'assets/teams/fnatic.png'],
            ['name' => 'Paper Rex', 'acronym' => 'PRX', 'img' => 'assets/teams/prx.png'],
            ['name' => 'Team Vitality', 'acronym' => 'VIT', 'img' => 'assets/teams/vitality.png'],
            ['name' => 'G2 Esports', 'acronym' => 'G2', 'img' => 'assets/teams/g2.png'],
            ['name' => 'DRX', 'acronym' => 'DRX', 'img' => 'assets/teams/drx.png'],
            ['name' => 'Natus Vincere', 'acronym' => 'NAVI', 'img' => 'assets/teams/navi.png'],
            ['name' => '100 Thieves', 'acronym' => '100T', 'img' => 'assets/teams/100t.png'],
            ['name' => 'Cloud9', 'acronym' => 'C9', 'img' => 'assets/teams/c9.png'],
            ['name' => 'NRG Esports', 'acronym' => 'NRG', 'img' => 'assets/teams/nrg.png'],
            ['name' => 'Team Liquid', 'acronym' => 'TL', 'img' => 'assets/teams/liquid.png'],
            ['name' => 'ZETA DIVISION', 'acronym' => 'ZETA', 'img' => 'assets/teams/zeta.png'],
            ['name' => 'T1 Esports', 'acronym' => 'T1', 'img' => 'assets/teams/t1.png'],
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
                'img'     => 'assets/teams/default.png'
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
