<?php

namespace Database\Factories;

use App\Models\App;
use App\Models\AppUser;
use App\Models\CreditTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<CreditTransaction> */
class CreditTransactionFactory extends Factory
{
    protected $model = CreditTransaction::class;

    public function definition(): array
    {
        $type = fake()->randomElement(['purchase', 'deduction', 'refund', 'adjustment']);
        $amount = match ($type) {
            'purchase' => fake()->numberBetween(10, 100),
            'deduction' => -fake()->numberBetween(1, 10),
            'refund' => fake()->numberBetween(1, 50),
            'adjustment' => fake()->numberBetween(-20, 20),
        };

        return [
            'app_id' => App::factory(),
            'app_user_id' => AppUser::factory(),
            'type' => $type,
            'amount' => $amount,
            'description' => fake()->optional()->sentence(),
        ];
    }
}
