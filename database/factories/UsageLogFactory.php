<?php

namespace Database\Factories;

use App\Models\App;
use App\Models\AppModel;
use App\Models\AppUser;
use App\Models\UsageLog;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<UsageLog> */
class UsageLogFactory extends Factory
{
    protected $model = UsageLog::class;

    public function definition(): array
    {
        return [
            'app_id' => App::factory(),
            'app_user_id' => AppUser::factory(),
            'app_model_id' => null,
            'credits_charged' => fake()->numberBetween(1, 5),
            'tokens_used' => fake()->optional()->numberBetween(100, 5000),
            'endpoint' => fake()->optional()->randomElement(['/v1/chat', '/v1/complete', '/v1/embed']),
            'success' => fake()->boolean(90),
        ];
    }
}
