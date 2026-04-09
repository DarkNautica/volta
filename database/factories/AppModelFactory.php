<?php

namespace Database\Factories;

use App\Models\App;
use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<AppModel> */
class AppModelFactory extends Factory
{
    protected $model = AppModel::class;

    public function definition(): array
    {
        $models = [
            ['claude-3-5-sonnet', 'Claude 3.5 Sonnet'],
            ['gpt-4o', 'GPT-4o'],
            ['gemini-pro', 'Gemini Pro'],
        ];
        $model = fake()->randomElement($models);

        return [
            'app_id' => App::factory(),
            'model_identifier' => $model[0],
            'display_name' => $model[1],
            'credits_per_call' => fake()->randomElement([1, 2, 5]),
            'active' => true,
        ];
    }
}
