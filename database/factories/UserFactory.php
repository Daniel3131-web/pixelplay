<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define o estado padrão do modelo.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(), // Gera um nome real aleatório (ex: "Daniel Silva")
            'nickname' => $this->faker->unique()->userName(), // Gera um nick único (ex: "dark_knight99")
            'email' => $this->faker->unique()->safeEmail(), // Gera um e-mail válido falso
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Todos os usuários de teste terão a senha "password"
            'avatar' => 'avatars/default.png',
            'role' => 'player',
            'remember_token' => Str::random(10),
        ];
    }
}