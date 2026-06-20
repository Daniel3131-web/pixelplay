<?php

namespace Database\Factories;

use App\Models\Character;
use Illuminate\Database\Eloquent\Factories\Factory;

class CharacterFactory extends Factory
{
    protected $model = Character::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'category' => 'valorant',
            'class' => 'Duelista',
            'img' => 'img/default.png'
        ];
    }

    public function createAll()
    {
        $allCharacters = [];
        $charsByGame = [
            'valorant' => [
                ['name' => 'Astra', 'class' => 'Controlador', 'img' => 'chars/valorant/astra.png'],
                ['name' => 'Breach', 'class' => 'Iniciador', 'img' => 'chars/valorant/breach.png'],
                ['name' => 'Brimstone', 'class' => 'Controlador', 'img' => 'chars/valorant/brimstone.png'],
                ['name' => 'Chamber', 'class' => 'Sentinela', 'img' => 'chars/valorant/chamber.png'],
                ['name' => 'Clove', 'class' => 'Controlador', 'img' => 'chars/valorant/clove.png'],
                ['name' => 'Cypher', 'class' => 'Sentinela', 'img' => 'chars/valorant/cypher.png'],
                ['name' => 'Deadlock', 'class' => 'Sentinela', 'img' => 'chars/valorant/deadlock.png'],
                ['name' => 'Fade', 'class' => 'Iniciador', 'img' => 'chars/valorant/fade.png'],
                ['name' => 'Gekko', 'class' => 'Iniciador', 'img' => 'chars/valorant/gekko.png'],
                ['name' => 'Harbor', 'class' => 'Controlador', 'img' => 'chars/valorant/harbor.png'],
                ['name' => 'Iso', 'class' => 'Duelista', 'img' => 'chars/valorant/iso.png'],
                ['name' => 'Jett', 'class' => 'Duelista', 'img' => 'chars/valorant/jett.png'],
                ['name' => 'Kayo', 'class' => 'Iniciador', 'img' => 'chars/valorant/kayo.png'],
                ['name' => 'Killjoy', 'class' => 'Sentinela', 'img' => 'chars/valorant/killjoy.png'],
                ['name' => 'Neon', 'class' => 'Duelista', 'img' => 'chars/valorant/neon.png'],
                ['name' => 'Omen', 'class' => 'Controlador', 'img' => 'chars/valorant/omen.png'],
                ['name' => 'Phoenix', 'class' => 'Duelista', 'img' => 'chars/valorant/phoenix.png'],
                ['name' => 'Raze', 'class' => 'Duelista', 'img' => 'chars/valorant/raze.png'],
                ['name' => 'Reyna', 'class' => 'Duelista', 'img' => 'chars/valorant/reyna.png'],
                ['name' => 'Sage', 'class' => 'Sentinela', 'img' => 'chars/valorant/sage.png'],
                ['name' => 'Skye', 'class' => 'Iniciador', 'img' => 'chars/valorant/skye.png'],
                ['name' => 'Sova', 'class' => 'Iniciador', 'img' => 'chars/valorant/sova.png'],
                ['name' => 'Tejo', 'class' => 'Duelista', 'img' => 'chars/valorant/tejo.png'],
                ['name' => 'Viper', 'class' => 'Controlador', 'img' => 'chars/valorant/viper.png'],
                ['name' => 'Vyse', 'class' => 'Sentinela', 'img' => 'chars/valorant/vyse.png'],
                ['name' => 'Yoru', 'class' => 'Duelista', 'img' => 'chars/valorant/yoru.png'],
            ],
            // Adicione aqui os outros jogos 
        ];

        foreach ($charsByGame as $category => $chars) {
            foreach ($chars as $char) {
                $allCharacters[] = [
                    'category' => $category,
                    'name' => $char['name'],
                    'class' => $char['class'],
                    'img' => $char['img'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        Character::insert($allCharacters);
    }
}