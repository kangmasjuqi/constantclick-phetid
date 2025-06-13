<?php

namespace Database\Factories;

use App\Models\UserTestEntry;
use App\Models\User;
use App\Models\TestPanel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserTestEntry>
 */
class UserTestEntryFactory extends Factory
{
    /**
     * The name of the corresponding model.
     *
     * @var string
     */
    protected $model = UserTestEntry::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(), // Creates a new user if not explicitly passed
            'test_panel_id' => TestPanel::factory(), // Creates a new test panel if not explicitly passed
            'test_date' => $this->faker->dateTimeBetween('-2 years', 'now')->format('Y-m-d'),
        ];
    }

    /**
     * Indicate that the model's test date is in the past.
     */
    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'test_date' => $this->faker->dateTimeBetween('-5 years', '-1 year')->format('Y-m-d'),
        ]);
    }

    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (UserTestEntry $userTestEntry) {
            // After creating a UserTestEntry, you might want to create associated UserMarkerValues here
            // This will be handled in the main seeder for more control over which markers to add for a given panel
        });
    }
}