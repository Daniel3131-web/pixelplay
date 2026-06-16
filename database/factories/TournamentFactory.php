<?php

namespace Database\Factories;

use App\Models\Model;
use App\Models\TournamentMatch;
use Illuminate\Database\Eloquent\Factories\Factory;

class TournamentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Sorteia uma categoria válida baseada no enum
        $categorias = ['valorant', 'cs2', 'lol', 'mlbb', 'ow2', 'mr'];
        $categoriaSorteada = $this->faker->randomElement($categorias);

        // Sorteia uma quantidade de participantes válida baseada no enum
        $max_vagas = ['4', '8', '16'];
        $max_vaga = $this->faker->randomElement($max_vagas);
        $current_participants = mt_rand(0, $max_vaga);

        $statusSorteado = $this->faker->randomElement(['Aberto','Agendado','Em andamento','Finalizado']);

        if ($statusSorteado === 'Aberto') {
            // Acontecerá no futuro distante (daqui a 1 ou 2 meses), logo as inscrições ainda estão rolando hoje
            $dataInicio = $this->faker->dateTimeBetween('+30 days', '+60 days');
            $live = false;
        } elseif ($statusSorteado === 'Agendado') {
            // Inscrições já fecharam, mas o campeonato ainda não começou (acontece daqui a 2 dias)
            $dataInicio = $this->faker->dateTimeBetween('+1 day', '+4 days');
            $live = false;
        } elseif ($statusSorteado === 'Em andamento') {
            // Está acontecendo HOJE (começou ontem e termina daqui a 2 dias)
            $dataInicio = $this->faker->dateTimeBetween('-1 day', 'now');
            $live = $this->faker->boolean(70); // 70% de chance de ter jogo ao vivo acontecendo agora
        } else {
            // Finalizado: aconteceu no passado (entre 1 e 3 meses atrás)
            $dataInicio = $this->faker->dateTimeBetween('-3 months', '-1 month');
            $live = false;
        }

        $dataFim = clone $dataInicio;
        $dataFim->modify('+3 days'); 

        $dataLimiteInscricao = clone $dataInicio;
        
        if ($statusSorteado === 'Aberto') {
            // Se está ABERTO, as inscrições fecham no futuro (ex: 5 dias antes de começar o torneio)
            $dataLimiteInscricao->modify('-5 days'); 
        } else {
            $dataLimiteInscricao->modify('-5 days');
        }

        return [
            'name' => 'Torneio Grand Master de ' . ucfirst($categoriaSorteada),
            'description' => $this->faker->paragraph(3), // Gera um texto com 3 parágrafos de regras
            'category' => $categoriaSorteada,
            'max_participants' => $max_vaga,
            'current_participants' => $current_participants,
            'live' => $live,
            'status' => $statusSorteado,
            // Sorteia uma taxa entre R$ 0,00 (Gratuito) e R$ 100,00
            'entrance_fee' => $this->faker->randomElement([0.00, 20.00, 50.00, 100.00]),
            'awards' => $this->faker->randomElement([100.00, 200.00, 500.00, 1000.00]),
            // Formatando as datas para o padrão do banco (Y-m-d)
            'start_date' => $dataInicio->format('Y-m-d'),
            'end_date' => $dataFim->format('Y-m-d'),
            'entry_date' => $dataLimiteInscricao->format('Y-m-d'),
            // Horários aleatórios
            'start_time' => '13:00:00',
            'end_time' => '22:00:00',
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
