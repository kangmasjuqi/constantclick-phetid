<?php

namespace Database\Factories;

use App\Models\Marker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Marker>
 */
class MarkerFactory extends Factory
{
    /**
     * The name of the corresponding model.
     *
     * @var string
     */
    protected $model = Marker::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // We usually seed Markers manually, but this is needed for relationships in other factories.
        // Provide a generic, unique marker name with a default unit and range.
        return [
            'name' => $this->faker->unique()->word() . ' Level',
            'unit' => $this->faker->randomElement(['mg/dL', 'U/L', '%', 'mEq/L']),
            'healthy_min' => $this->faker->randomFloat(2, 10, 50),
            'healthy_max' => $this->faker->randomFloat(2, 60, 200),
            'description' => $this->faker->sentence(),
        ];
    }
}