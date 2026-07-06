<?php

namespace Database\Factories;

use App\Models\Character;
use Illuminate\Database\Eloquent\Factories\Factory;

class CharacterFactory extends Factory
{
    protected $model = Character::class;

    /**
     * Define o estado padrão (Ajustado com categorias dinâmicas do Faker)
     */
    public function definition(): array
    {
        return [
            'name'     => $this->faker->name(),
            'category' => $this->faker->randomElement(['valorant', 'cs2', 'lol', 'mlbb', 'ow2', 'mr']),
            'class'    => $this->faker->randomElement(['Duelista', 'Controlador', 'Iniciador', 'Sentinela']),
            'img'      => 'chars/default.png'
        ];
    }

    /**
     * Método personalizado para popular TODOS os personagens de uma vez.
     */
    public function createAll(): void
    {
        $allCharacters = [];
        $charsByGame = [
            'valorant' => [
                ['name' => 'Astra', 'class' => 'Controlador', 'img' => '/assets/chars/valorant/astra.png'],
                ['name' => 'Breach', 'class' => 'Iniciador', 'img' => '/assets/chars/valorant/breach.png'],
                ['name' => 'Brimstone', 'class' => 'Controlador', 'img' => '/assets/chars/valorant/brimstone.png'],
                ['name' => 'Chamber', 'class' => 'Sentinela', 'img' => '/assets/chars/valorant/chamber.png'],
                ['name' => 'Clove', 'class' => 'Controlador', 'img' => '/assets/chars/valorant/clove.png'],
                ['name' => 'Cypher', 'class' => 'Sentinela', 'img' => '/assets/chars/valorant/cypher.png'],
                ['name' => 'Deadlock', 'class' => 'Sentinela', 'img' => '/assets/chars/valorant/deadlock.png'],
                ['name' => 'Fade', 'class' => 'Iniciador', 'img' => '/assets/chars/valorant/fade.png'],
                ['name' => 'Gekko', 'class' => 'Iniciador', 'img' => '/assets/chars/valorant/gekko.png'],
                ['name' => 'Harbor', 'class' => 'Controlador', 'img' => '/assets/chars/valorant/harbor.png'],
                ['name' => 'Iso', 'class' => 'Duelista', 'img' => '/assets/chars/valorant/iso.png'],
                ['name' => 'Jett', 'class' => 'Duelista', 'img' => '/assets/chars/valorant/jett.png'],
                ['name' => 'Kayo', 'class' => 'Iniciador', 'img' => '/assets/chars/valorant/kayo.png'],
                ['name' => 'Killjoy', 'class' => 'Sentinela', 'img' => '/assets/chars/valorant/killjoy.png'],
                ['name' => 'Neon', 'class' => 'Duelista', 'img' => '/assets/chars/valorant/neon.png'],
                ['name' => 'Omen', 'class' => 'Controlador', 'img' => '/assets/chars/valorant/omen.png'],
                ['name' => 'Phoenix', 'class' => 'Duelista', 'img' => '/assets/chars/valorant/phoenix.png'],
                ['name' => 'Raze', 'class' => 'Duelista', 'img' => '/assets/chars/valorant/raze.png'],
                ['name' => 'Reyna', 'class' => 'Duelista', 'img' => '/assets/chars/valorant/reyna.png'],
                ['name' => 'Sage', 'class' => 'Sentinela', 'img' => '/assets/chars/valorant/sage.png'],
                ['name' => 'Skye', 'class' => 'Iniciador', 'img' => '/assets/chars/valorant/skye.png'],
                ['name' => 'Sova', 'class' => 'Iniciador', 'img' => '/assets/chars/valorant/sova.png'],
                ['name' => 'Tejo', 'class' => 'Duelista', 'img' => '/assets/chars/valorant/tejo.png'],
                ['name' => 'Viper', 'class' => 'Controlador', 'img' => '/assets/chars/valorant/viper.png'],
                ['name' => 'Vyse', 'class' => 'Sentinela', 'img' => '/assets/chars/valorant/vyse.png'],
                ['name' => 'Yoru', 'class' => 'Duelista', 'img' => '/assets/chars/valorant/yoru.png'],
            ],
            // Adicione os outros jogos aqui quando quiser
        ];

        foreach ($charsByGame as $category => $chars) {
            foreach ($chars as $char) {
                $allCharacters[] = [
                    'category'   => $category,
                    'name'       => $char['name'],
                    'class'      => $char['class'],
                    'img'        => $char['img'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Character::insert($allCharacters);
    }
}