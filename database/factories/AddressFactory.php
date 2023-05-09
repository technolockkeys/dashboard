<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $country = Country::factory()->create();
        return [
            'country_id' => $country->id,
//            'city_id' => 1,
            'user_id' =>1,
            'phone' => 123123123,
        ];
    }
}
