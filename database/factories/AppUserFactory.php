<?php

namespace Database\Factories;

use App\Models\App;
use App\Models\AppUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<AppUser> */
class AppUserFactory extends Factory
{
    protected $model = AppUser::class;

    public function definition(): array
    {
        return [
            'app_id' => App::factory(),
            'external_user_id' => 'user_' . fake()->unique()->numerify('######'),
            'credit_balance' => fake()->numberBetween(0, 500),
        ];
    }
}
