<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'description' => fake()->text(),
            'num_chars' => fake()->numberBetween(1000, 9999),
            'owner_id' => User::inRandomOrder()->value('id'),
            'start_date' => Carbon::now()->addDays(rand(0, 365))
        ];
    }

    public function done(): Factory {
        return $this->state(function (array $attributes){
            return [
                'status' => 'done'
            ];
        });
    }
}
