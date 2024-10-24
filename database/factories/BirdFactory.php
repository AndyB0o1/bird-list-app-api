<?php

namespace Database\Factories;

use App\Models\Birder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bird>
 */
class BirdFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word(),
            'image' => $this->faker->url(),
            'location' => $this->faker->locale(),
            'lat' => $this->faker->randomFloat(),
            'lon' => $this->faker->randomFloat(),
            'birder_id' => Birder::factory()
        ];
    }
}
