<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    protected $model = Link::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'original_url' => $this->faker->url(),
            'code' => $this->faker->unique()->slug(1),
        ];
    }
}
