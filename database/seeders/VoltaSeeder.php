<?php

namespace Database\Seeders;

use App\Models\App;
use App\Models\AppModel;
use App\Models\AppUser;
use App\Models\CreditTransaction;
use App\Models\UsageLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class VoltaSeeder extends Seeder
{
    public function run(): void
    {
        $developer = User::factory()->create([
            'name' => 'Volta Developer',
            'email' => 'dev@volta.test',
            'password' => bcrypt('password'),
            'plan' => 'studio',
            'trial_ends_at' => null,
        ]);

        $trialUser = User::factory()->create([
            'name' => 'Trial User',
            'email' => 'trial@volta.test',
            'password' => bcrypt('password'),
            'plan' => null,
            'trial_ends_at' => now()->addDays(7),
        ]);

        $expiredUser = User::factory()->create([
            'name' => 'Expired User',
            'email' => 'expired@volta.test',
            'password' => bcrypt('password'),
            'plan' => null,
            'trial_ends_at' => now()->subDays(1),
        ]);

        // Create 1 app for trial user
        $trialApp = App::factory()->create([
            'user_id' => $trialUser->id,
            'name' => 'Trial App',
            'ai_provider' => 'anthropic',
        ]);

        $apps = [
            App::factory()->create([
                'user_id' => $developer->id,
                'name' => 'ChatBot Pro',
                'ai_provider' => 'anthropic',
                'credit_packages' => [[100, 499], [500, 1999], [1000, 3499]],
            ]),
            App::factory()->create([
                'user_id' => $developer->id,
                'name' => 'Image Generator',
                'ai_provider' => 'openai',
                'credit_packages' => [[50, 299], [200, 999], [500, 1999]],
            ]),
        ];

        $models = [
            ['claude-3-5-sonnet', 'Claude 3.5 Sonnet', 2],
            ['gpt-4o', 'GPT-4o', 3],
            ['gemini-pro', 'Gemini Pro', 1],
        ];

        foreach ($apps as $app) {
            foreach ($models as [$identifier, $displayName, $credits]) {
                AppModel::factory()->create([
                    'app_id' => $app->id,
                    'model_identifier' => $identifier,
                    'display_name' => $displayName,
                    'credits_per_call' => $credits,
                ]);
            }

            $appUsers = AppUser::factory(3)->create([
                'app_id' => $app->id,
                'credit_balance' => fn () => fake()->numberBetween(50, 500),
            ]);

            foreach ($appUsers as $appUser) {
                // Create 30 days of transactions and usage
                for ($day = 30; $day >= 0; $day--) {
                    $date = now()->subDays($day);
                    $callsPerDay = fake()->numberBetween(2, 15);

                    for ($i = 0; $i < $callsPerDay; $i++) {
                        $creditsCharged = fake()->randomElement([1, 2, 3]);

                        UsageLog::factory()->create([
                            'app_id' => $app->id,
                            'app_user_id' => $appUser->id,
                            'app_model_id' => $app->appModels->random()->id,
                            'credits_charged' => $creditsCharged,
                            'tokens_used' => fake()->numberBetween(100, 4000),
                            'endpoint' => fake()->randomElement(['/v1/chat', '/v1/complete', '/v1/embed']),
                            'success' => fake()->boolean(95),
                            'created_at' => $date->copy()->addMinutes(fake()->numberBetween(0, 1440)),
                        ]);

                        CreditTransaction::factory()->create([
                            'app_id' => $app->id,
                            'app_user_id' => $appUser->id,
                            'type' => 'deduction',
                            'amount' => -$creditsCharged,
                            'created_at' => $date->copy()->addMinutes(fake()->numberBetween(0, 1440)),
                        ]);
                    }

                    // Occasional purchases
                    if (fake()->boolean(30)) {
                        CreditTransaction::factory()->create([
                            'app_id' => $app->id,
                            'app_user_id' => $appUser->id,
                            'type' => 'purchase',
                            'amount' => fake()->randomElement([50, 100, 200]),
                            'description' => 'Credit purchase',
                            'created_at' => $date,
                        ]);
                    }
                }
            }
        }
    }
}
