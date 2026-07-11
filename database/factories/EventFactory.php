<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $type = $this->faker->randomElement(['online', 'presencial', 'corporativo']);

        // Cronologia coerente: inscrição fecha antes do evento começar
        $startDate = $this->faker->dateTimeBetween('-2 months', '+2 months');
        $entryDate = (clone $startDate)->modify('-7 days');
        $endDate   = (clone $startDate)->modify('+' . $this->faker->numberBetween(1, 3) . ' days');

        $maxParticipants = $this->faker->randomElement([100, 250, 500, 1000, 2000]);

        return [
            // Se não existir organizador ainda, cria um — evita quebrar quando
            // a factory é chamada isolada (ex: em testes)
            'user_id' => User::where('role', 'organizador')->inRandomOrder()->first()?->id
                ?? User::factory()->create(['role' => 'organizador'])->id,

            'name' => 'PixelPlay ' . $this->faker->randomElement(['Cup', 'Fest', 'Arena', 'League', 'Showdown']) . ' ' . $startDate->format('Y'),
            'img'  => null,

            'max_participants'     => $maxParticipants,
            // Igual ao app de verdade: current_participants só sobe via
            // PaymentController::increment() quando alguém confirma inscrição.
            // O ReportsDemoSeeder ajusta esse valor depois de gerar as
            // inscrições reais em event_user, pra tudo bater.
            'current_participants' => 0,

            'type'     => $type,
            'location' => $type === 'online' ? 'Discord Oficial da PixelPlay' : $this->faker->city() . ', ' . $this->faker->stateAbbr(),

            'streaming_url' => 'https://twitch.tv/pixelplay_' . $this->faker->word(),
            'entrance_fee'  => $this->faker->randomElement([0, 20, 35, 50, 100]),

            'entry_date' => $entryDate->format('Y-m-d'),
            'start_date' => $startDate->format('Y-m-d'),
            'end_date'   => $endDate->format('Y-m-d'),
            'start_time' => '09:00:00',
            'end_time'   => '19:00:00',

            'description' => $this->faker->paragraph(3),
        ];
    }

    /** Estado auxiliar para forçar um evento já encerrado (útil pra popular relatórios históricos) */
    public function finished(): static
    {
        return $this->state(function () {
            $start = $this->faker->dateTimeBetween('-4 months', '-1 month');
            $end   = (clone $start)->modify('+2 days');
            $entry = (clone $start)->modify('-7 days');

            return [
                'start_date' => $start->format('Y-m-d'),
                'end_date'   => $end->format('Y-m-d'),
                'entry_date' => $entry->format('Y-m-d'),
            ];
        });
    }
}