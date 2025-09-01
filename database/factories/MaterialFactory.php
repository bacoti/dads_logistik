<?php

namespace Database\Factories;

use App\Models\SubProject;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true),
            'unit' => $this->faker->randomElement(['kg', 'pcs', 'm', 'liter', 'box']),
            'sub_project_id' => SubProject::factory(),
            'category_id' => Category::factory(),
        ];
    }
}
