<?php

namespace Database\Factories;

use App\Models\Map;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Map>
 */
class MapFactory extends Factory
{
    protected $model = Map::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $category = $this->faker->randomElement(['valorant', 'cs2', 'lol', 'mlbb', 'ow2', 'mr']);

        $mapsByGame = [
            'valorant' => [
                ['name' => 'Bind', 'img' => 'maps/valorant/bind.png'],
                ['name' => 'Haven', 'img' => 'maps/valorant/haven.png'],
                ['name' => 'Split', 'img' => 'maps/valorant/split.png'],
                ['name' => 'Ascent', 'img' => 'maps/valorant/ascent.png'],
                ['name' => 'Icebox', 'img' => 'maps/valorant/icebox.png'],
                ['name' => 'Breeze', 'img' => 'maps/valorant/breeze.png'],
                ['name' => 'Lotus', 'img' => 'maps/valorant/lotus.png'],
                ['name' => 'Sunset', 'img' => 'maps/valorant/sunset.png'],
            ],
            'cs2' => [
                ['name' => 'Mirage', 'img' => 'maps/cs2/mirage.png'],
                ['name' => 'Inferno', 'img' => 'maps/cs2/inferno.png'],
                ['name' => 'Dust II', 'img' => 'maps/cs2/dust2.png'],
                ['name' => 'Nuke', 'img' => 'maps/cs2/nuke.png'],
                ['name' => 'Overpass', 'img' => 'maps/cs2/overpass.png'],
                ['name' => 'Ancient', 'img' => 'maps/cs2/ancient.png'],
                ['name' => 'Anubis', 'img' => 'maps/cs2/anubis.png'],
            ],
            'lol' => [
                ['name' => 'Summoners Rift', 'img' => 'maps/lol/summoners_rift.png'],
                ['name' => 'Howling Abyss', 'img' => 'maps/lol/howling_abyss.png'],
            ],
            'mlbb' => [
                ['name' => 'The Western Expanse', 'img' => 'maps/mlbb/western_expanse.png'],
                ['name' => 'Celestial Palace', 'img' => 'maps/mlbb/celestial_palace.png'],
                ['name' => 'Imperial Sanctuary', 'img' => 'maps/mlbb/imperial_sanctuary.png'],
            ],
            'ow2' => [
                ['name' => 'Kings Row', 'img' => 'maps/ow2/kings_row.png'],
                ['name' => 'Hanamura', 'img' => 'maps/ow2/hanamura.png'],
                ['name' => 'Watchpoint: Gibraltar', 'img' => 'maps/ow2/gibraltar.png'],
                ['name' => 'Hollywood', 'img' => 'maps/ow2/hollywood.png'],
                ['name' => 'Eichenwalde', 'img' => 'maps/ow2/eichenwalde.png'],
            ],
            'mr' => [
                ['name' => 'Standard Map 1', 'img' => 'maps/mr/map1.png'],
                ['name' => 'Standard Map 2', 'img' => 'maps/mr/map2.png'],
            ],
        ];

        $selectedMap = $this->faker->randomElement($mapsByGame[$category]);

        return [
            'category' => $category,
            'name'     => $selectedMap['name'], 
            'img'      => $selectedMap['img'],
        ];
    }
}
