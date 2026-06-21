<?php

namespace Database\Factories;

use App\Models\Map;
use Illuminate\Database\Eloquent\Factories\Factory;

class MapFactory extends Factory
{
    protected $model = Map::class;

    /**
     * Define o estado padrão (Evita erros quando outros Seeders chamam a Factory em massa)
     */
    public function definition(): array
    {
        return [
            'name'     => $this->faker->unique()->word(), 
            'category' => $this->faker->randomElement(['valorant', 'cs2', 'lol', 'mlbb', 'ow2', 'mr']),
            'img'      => 'maps/default.png'
        ];
    }

    /**
     * Método personalizado para popular TODOS os mapas reais de uma vez.
     */
    public function createAll(): void
    {
        $allMaps = [];
        
        $mapsByGame = [
            'valorant' => [
                ['name' => 'Abyss', 'img' => 'maps/valorant/abyss.png'],
                ['name' => 'Ascent', 'img' => 'maps/valorant/ascent.png'],
                ['name' => 'Bind', 'img' => 'maps/valorant/bind.png'],
                ['name' => 'Breeze', 'img' => 'maps/valorant/breeze.png'],
                ['name' => 'Corrode', 'img' => 'maps/valorant/corrode.png'],
                ['name' => 'Fracture', 'img' => 'maps/valorant/fracture.png'],
                ['name' => 'Haven', 'img' => 'maps/valorant/haven.png'],
                ['name' => 'Icebox', 'img' => 'maps/valorant/icebox.png'],
                ['name' => 'Lotus', 'img' => 'maps/valorant/lotus.png'],
                ['name' => 'Pearl', 'img' => 'maps/valorant/pearl.png'],
                ['name' => 'Split', 'img' => 'maps/valorant/split.png'],
                ['name' => 'Sunset', 'img' => 'maps/valorant/sunset.png'],
            ],
            'cs2' => [
                ['name' => 'Mirage', 'img' => 'maps/cs2/mirage.png'],
                ['name' => 'Dust II', 'img' => 'maps/cs2/dust2.png'],
                ['name' => 'Inferno', 'img' => 'maps/cs2/inferno.png'],
                ['name' => 'Overpass', 'img' => 'maps/cs2/overpass.png'],
                ['name' => 'Nuke', 'img' => 'maps/cs2/nuke.png'],
                ['name' => 'Ancient', 'img' => 'maps/cs2/ancient.png'],
                ['name' => 'Anubis', 'img' => 'maps/cs2/anubis.png'],
            ],
            // Você pode continuar adicionando os outros jogos aqui...
        ];

        foreach ($mapsByGame as $category => $maps) {
            foreach ($maps as $map) {
                $allMaps[] = [
                    'category'   => $category,
                    'name'       => $map['name'],
                    'img'        => $map['img'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        Map::insert($allMaps);
    }
}