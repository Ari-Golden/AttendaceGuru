<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JadwalGuru>
 */
class JadwalGuruFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'hari' => $this->faker->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']),
            'jam_masuk' => $this->faker->time('H:i:s', '08:00:00'),
            'jam_pulang' => $this->faker->time('H:i:s', '17:00:00'),
        ];
    }
}