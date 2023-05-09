<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
//        $type = array_rand(['physical', 'software']);
        return [
            'slug' => $this->faker->slug,
            'parent_id' => 0,
            'type' => $this->faker->randomElement(['physical', 'software']),
            'name' => $this->faker->name,
            'description' => $this->faker->text,
            'meta_title' => $this->faker->name,
            'meta_description' => $this->faker->text,
        ];
    }
}
