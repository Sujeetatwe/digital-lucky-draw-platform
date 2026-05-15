<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participant>
 */
class ParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hasVoterId = $this->faker->boolean(60); // 60% chance of having voter ID

        return [
            'full_name' => $this->faker->name(),
            'age' => $this->faker->numberBetween(18, 80),
            'mobile_number' => $this->faker->unique()->numerify('9#########'), // Ensure 10 digits starting with 9 to avoid some valid checks if any
            'voter_id' => $hasVoterId,
            'voter_member_count' => $hasVoterId ? $this->faker->numberBetween(0, 5) : 0,
            'epic_voter_id_no' => $hasVoterId ? strtoupper($this->faker->bothify('???#######')) : null,
            'adharcard_no' => $this->faker->numerify('############'),
            'permanent_address' => $this->faker->address(),
            'token' => (string) $this->faker->unique()->numberBetween(1000, 9999),
        ];
    }
}
