<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthcareProfessional>
 */
class HealthcareProfessionalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $specialities = [
            'General Practitioner',
            'Cardiologist',
            'Pediatrician',
            'Dermatologist',
            'Neurologist',
            'Psychiatrist',
            'Dentist',
            'Ophthalmologist',
            'Physical Therapist',
            'Nurse Practitioner',
        ];

        return [
            'name' => fake()->name(),
            'speciality' => fake()->randomElement($specialities),
        ];
    }
}
