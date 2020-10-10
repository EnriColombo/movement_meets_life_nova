<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Services\GlobalServices;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => [
                'en' => $this->faker->sentence($nbWords = 2, $variableNbWords = true),
                'it' => $this->faker->sentence($nbWords = 2, $variableNbWords = true),
            ],
            'intro_text' => [
                'en' => $this->faker->sentence($nbWords = 15, $variableNbWords = true),
                'it' => $this->faker->sentence($nbWords = 15, $variableNbWords = true),
            ],
            'body' => [
                'en' => $this->faker->text($maxNbChars = 200),
                'it' => $this->faker->text($maxNbChars = 200),
            ],
            'is_published' => GlobalServices::getRandomWeightedElement(['1'=>85, '0'=>15 ]),
            'featured' => $this->faker->numberBetween($min = 0, $max = 1),
            'category_id' => $this->faker->numberBetween($min = 1, $max = 3),
            'created_by' => 1, //Auth user has a problem here
            'introimage' => 'placeholders/placeholder-768x768.png',
        ];
    }
}


