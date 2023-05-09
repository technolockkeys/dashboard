<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $category = Category::factory()->create();
        return [
            'title' => $this->faker->name,
            'description' => $this->faker->text,
            'meta_description' => $this->faker->text,
            'meta_title' => $this->faker->name,
            'slug' => $this->faker->slug,
            'priority' => $this->faker->numberBetween(1,10),
            'category_id' => $category->id,
            'image' => $this->faker->numberBetween(1,10),
            'price' => $this->faker->numberBetween(1,100),
            'weight' => $this->faker->numberBetween(1,10),
            'sku' => $this->faker->slug,
            'quantity' => $this->faker->numberBetween(10,20),
            'summary_name' => $this->faker->name,
            'discount_type' => $this->faker->randomElement(['none', 'fixed', 'percent']),
            'discount_value' => $this->faker->numberBetween(1,100),
        ];
    }
}
