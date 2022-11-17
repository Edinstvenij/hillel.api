<?php

namespace Database\Factories;

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
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->text(rand(10, 30)),
            'author_id' => 1,
            'created_at' => $this->faker->unique()->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->unique()->date('Y-m-d H:i:s'),
        ];
    }
}
