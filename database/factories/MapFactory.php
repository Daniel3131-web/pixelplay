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
                ['name' => 'Abyss', 'img' => 'assets/maps/valorant/abyss.png'],
                ['name' => 'Ascent', 'img' => 'assets/maps/valorant/ascent.png'],
                ['name' => 'Bind', 'img' => 'assets/maps/valorant/bind.png'],
                ['name' => 'Breeze', 'img' => 'assets/maps/valorant/breeze.png'],
                ['name' => 'Corrode', 'img' => 'assets/maps/valorant/corrode.png'],
                ['name' => 'Fracture', 'img' => 'assets/maps/valorant/fracture.png'],
                ['name' => 'Haven', 'img' => 'assets/maps/valorant/haven.png'],
                ['name' => 'Icebox', 'img' => 'assets/maps/valorant/icebox.png'],
                ['name' => 'Lotus', 'img' => 'assets/maps/valorant/lotus.png'],
                ['name' => 'Pearl', 'img' => 'assets/maps/valorant/pearl.png'],
                ['name' => 'Split', 'img' => 'assets/maps/valorant/split.png'],
                ['name' => 'Sunset', 'img' => 'assets/maps/valorant/sunset.png'],
            ],
            'cs2' => [
                ['name' => 'Mirage', 'img' => 'assets/maps/cs2/mirage.png'],
                ['name' => 'Dust II', 'img' => 'assets/maps/cs2/dust2.png'],
                ['name' => 'Inferno', 'img' => 'assets/maps/cs2/inferno.png'],
                ['name' => 'Overpass', 'img' => 'assets/maps/cs2/overpass.png'],
                ['name' => 'Nuke', 'img' => 'assets/maps/cs2/nuke.png'],
                ['name' => 'Ancient', 'img' => 'assets/maps/cs2/ancient.png'],
                ['name' => 'Anubis', 'img' => 'assets/maps/cs2/anubis.png'],
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