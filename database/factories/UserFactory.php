<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
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

            'last_name' => $this->faker->lastName(),
            'id_card' => $this->faker->unique()->randomNumber(8),
            'gender' => $this->faker->randomElement(['Masculino', 'Femenino', 'Otro']),
            'birth_date' => $this->faker->date(),
            'age' => $this->faker->numberBetween(18, 70),
            'ethnicity' => $this->faker->word(),
            'phone' => $this->faker->phoneNumber(),
            'user_type' => 'usuario',  // Puedes cambiarlo según sea necesario
            'status' => 'aspirante',    // O puedes configurarlo como 'paciente' si lo prefieres
            'disability' => $this->faker->word(),
            'id_card_status' => $this->faker->boolean(),
            'disability_grade' => $this->faker->randomNumber(1),
            'diagnosis' => $this->faker->text(),
            'medical_history' => $this->faker->text(),
            // 'address_id' => \App\Models\Address::factory(), // Si tienes una relación con direcciones
            // Si tienes la columna `therapy_id`, también puedes agregarla
            // 'therapy_id' => \App\Models\Therapy::factory(),
        ];
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
