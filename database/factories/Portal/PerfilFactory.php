<?php

namespace Database\Factories\Portal;

use App\Models\Portal\Perfil;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Perfil>
 */
class PerfilFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'slug' => fake()->unique()->slug(2),
            'nome' => fake()->jobTitle(),
            'descricao' => null,
        ];
    }
}
