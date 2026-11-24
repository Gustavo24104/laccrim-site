<?php

namespace Database\Factories\Portal;

use App\Models\Portal\Permissao;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Permissao>
 */
class PermissaoFactory extends Factory
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
            'nome' => fake()->sentence(3),
        ];
    }
}
