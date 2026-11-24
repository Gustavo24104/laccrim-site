<?php

namespace Database\Factories;

use App\Enums\PerfilAcesso;
use App\Models\Portal\Perfil;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Vincula o usuário a um perfil padrão (requer o PerfilSeeder executado).
     */
    public function comPerfil(PerfilAcesso $perfil): static
    {
        return $this->afterCreating(function (User $usuario) use ($perfil): void {
            $usuario->perfis()->attach(Perfil::query()->where('slug', $perfil->value)->firstOrFail());
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
