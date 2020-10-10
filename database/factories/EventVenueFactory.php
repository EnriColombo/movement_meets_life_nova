<?php

namespace Database\Factories;

use App\Models\EventVenue;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventVenueFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EventVenue::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->sentence($nbWords = 2, $variableNbWords = true),
            'description' => $this->faker->paragraph($nbSentences = 2, $variableNbSentences = true),
            'website' => $this->faker->url,
            'address' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state_province' => $this->faker->state,
            'country' => $this->faker->country,
            'zip_code' => $this->faker->postcode,
            'lng' => $this->faker->longitude($min = -180, $max = 180),
            'lat' => $this->faker->latitude($min = -90, $max = 90),
        ];
    }
}