<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Absensi>
 */
class AbsensiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'guru_id' => \App\Models\User::factory(),
            'id_jadwal' => \App\Models\JadwalGuru::factory(),
            'foto_selfie' => $this->faker->imageUrl(),
            'jam_absen' => $this->faker->time(),
            'tgl_absen' => $this->faker->date(),
            'lokasi_absen' => $this->faker->address(),
            'status' => $this->faker->randomElement(['masuk', 'pulang']),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'report' => $this->faker->sentence(),
            'keterlambatan' => $this->faker->numberBetween(0, 60),
        ];
    }
}