<?php

namespace Database\Factories;

use App\Models\TestPanel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TestPanel>
 */
class TestPanelFactory extends Factory
{
    /**
     * The name of the corresponding model.
     *
     * @var string
     */
    protected $model = TestPanel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // We usually seed TestPanels manually, but this is needed for relationships in other factories.
        // Provide a generic, unique name.
        return [
            'name' => $this->faker->unique()->word() . ' Panel',
            'description' => $this->faker->sentence(),
        ];
    }
}