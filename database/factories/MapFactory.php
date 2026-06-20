<?php

namespace Database\Factories;

use App\Models\Map;
use Illuminate\Database\Eloquent\Factories\Factory;

class MapFactory extends Factory
{
    protected $model = Map::class;

    /**
     * Define o estado padrão (usado para testes ou criar um mapa aleatório).
     */
    public function definition(): array
    {
        //
        return [
            //
        ];
    }

    /**
     * Método personalizado para popular TODOS os mapas de uma vez.
     */
    public function createAll()
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
            //continuar adicionando os outros jogos aqui dentro...
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