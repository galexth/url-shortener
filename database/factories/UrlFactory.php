<?php

namespace Database\Factories;

use App\Models\Url;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class UrlFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Url::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'url' => $this->faker->url,
            'expires_at' => $this->faker->boolean
                ? Carbon::now()->addMinutes($this->faker->randomNumber(4))
                : null,
        ];
    }
}
