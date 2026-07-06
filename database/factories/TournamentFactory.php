<?php

namespace Database\Factories;

use App\Models\Tournament;
use App\Models\TournamentMatch;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TournamentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Sorteia uma categoria válida baseada no enum
        $categorias = ['valorant'];
        $categoriaSorteada = $this->faker->randomElement($categorias);

        // Sorteia uma quantidade de participantes válida baseada no enum
        $max_vagas = [4, 8, 16];
        $max_vaga = $this->faker->randomElement($max_vagas);
        $current_participants = mt_rand(0, $max_vaga);

        // $statusSorteado = $this->faker->randomElement(['Aberto','Agendado','Em andamento','Finalizado']);

        // if ($statusSorteado === 'Aberto') {
        //     $dataInicio = $this->faker->dateTimeBetween('+30 days', '+60 days');
        //     $live = false;
        // } elseif ($statusSorteado === 'Agendado') {
        //     $dataInicio = $this->faker->dateTimeBetween('+1 day', '+4 days');
        //     $live = false;
        // } elseif ($statusSorteado === 'Em andamento') {
        //     $dataInicio = $this->faker->dateTimeBetween('-1 day', 'now');
        //     $live = $this->faker->boolean(70); 
        // } else {
        //     $dataInicio = $this->faker->dateTimeBetween('-3 months', '-1 month');
        //     $live = false;
        // }

        $dataInicio = $this->faker->dateTimeBetween('-1 months', '+1 month');
        $dataFim = clone $dataInicio;
        $dataFim->modify('+7 days'); 

        $dataLimiteInscricao = clone $dataInicio;
        $dataLimiteInscricao->modify('-7 days'); 

        return [
            'event_id' => Event::inRandomOrder()->first()?->id ?? Event::factory(),
            'user_id' => User::where('role', 'organizador')->inRandomOrder()->first()?->id ?? User::factory(),

            'name' => 'Torneio Grand Master de ' . ucfirst($categoriaSorteada),
            'description' => $this->faker->paragraph(3), 
            'category' => $categoriaSorteada,
            'max_participants' => $max_vaga,
            'current_participants' => $current_participants,
            // 'live' => $live,
            // 'status' => $statusSorteado,
            
            'entrance_fee' => $this->faker->randomElement([0.00, 20.00, 50.00, 100.00]),
            'awards' => $this->faker->randomElement([100.00, 200.00, 500.00, 1000.00]),
            
            'start_date' => $dataInicio->format('Y-m-d'),
            'end_date' => $dataFim->format('Y-m-d'),
            'entry_date' => $dataLimiteInscricao->format('Y-m-d'),
            
            'start_time' => '13:00:00',
            'end_time' => '22:00:00',

            'streaming_url' => 'https://www.twitch.tv/pixelplay_inc',
            'img' => null,
        ];
    }

    /**
     * Define a quantidade dinâmica de partidas que este torneio terá.
     */
    public function hasMatches(int $count): static
    {
        return $this->has(
            TournamentMatch::factory()->count($count),
            'matches'
        );
    }
}