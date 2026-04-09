<?php

namespace Database\Factories;

use App\Models\App;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/** @extends Factory<App> */
class AppFactory extends Factory
{
    protected $model = App::class;

    public function definition(): array
    {
        $name = fake()->company() . ' AI';

        return [
            'user_id' => User::factory(),
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(6),
            'app_key' => (string) Str::uuid(),
            'ai_provider' => fake()->randomElement(['anthropic', 'openai', 'gemini']),
            'active' => true,
            'rate_limit_per_hour' => 60,
        ];
    }
}
