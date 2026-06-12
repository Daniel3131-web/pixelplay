<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tournament;

class TournamentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tournament::factory()->count(100)->create();
    }
}
