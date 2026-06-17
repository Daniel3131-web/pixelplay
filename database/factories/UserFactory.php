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
            'email' => $this->faker->unique()->safeEmail(), // Gera um e-mail válido falso
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Todos os usuários de teste terão a senha "password"
            'img' => '/assets/profiles/avatar/default.png',
            'role' => 'player',
            'remember_token' => Str::random(10),
        ];
    }
}