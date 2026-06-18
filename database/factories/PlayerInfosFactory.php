<?php

namespace Database\Factories;

use App\Models\PlayerInfos;
use App\Models\User;
use App\Models\Team;
use App\Models\TournamentMatch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlayerInfos>
 */
class PlayerInfosFactory extends Factory
{
    protected $model = PlayerInfos::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Relacionamentos automáticos (caso não sejam passados no seeder)
            'user_id' => User::factory(),
            'team_id' => Team::factory(),
            'match_id' => TournamentMatch::factory(),

            // Estatísticas da partida com valores aleatórios realistas
            'kill' => fake()->numberBetween(0, 25),
            'death' => fake()->numberBetween(0, 15),
            'assistance' => fake()->numberBetween(0, 30),
            'gold' => fake()->numberBetween(4000, 18000),
            'score' => fake()->numberBetween(1000, 9500),
        ];
    }
}