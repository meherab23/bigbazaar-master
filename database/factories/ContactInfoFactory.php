<?php

namespace Database\Factories;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactInfo>
 */
class ContactInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'contact_number' => $this->faker->numerify('##########'), // Generates a 10-digit number
            'message' => $this->faker->sentence(),
            'created_at' => Carbon::now(),
        ];
    }
}
