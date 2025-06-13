<?php

namespace Database\Factories;

use App\Models\UserMarkerValue;
use App\Models\UserTestEntry;
use App\Models\Marker;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserMarkerValue>
 */
class UserMarkerValueFactory extends Factory
{
    /**
     * The name of the corresponding model.
     *
     * @var string
     */
    protected $model = UserMarkerValue::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Define a default state, but ideally, values will be provided when calling this factory
        return [
            'user_test_entry_id' => UserTestEntry::factory(), // Creates a test entry if not provided
            'marker_id' => Marker::factory(), // Creates a marker if not provided
            'value' => $this->faker->randomFloat(2, 50, 250), // A generic random value
        ];
    }

    /**
     * Define a state for a specific marker with realistic value range.
     *
     * @param \App\Models\Marker $marker
     * @return static
     */
    public function forMarker(Marker $marker): static
    {
        // Generates a realistic value based on the marker's healthy range
        // with some variability to simulate real-world data (within/slightly outside range)
        $min = $marker->healthy_min ?? 0;
        $max = $marker->healthy_max ?? ($min + 100); // Default if max is null

        // Add some deviation for realism (e.g., +/- 20% beyond min/max)
        $actualMin = max(0, $min - ($min * 0.2));
        $actualMax = $max + ($max * 0.2);

        // Ensure max is greater than min
        if ($actualMax <= $actualMin) {
            $actualMax = $actualMin + 10;
        }

        return $this->state(fn (array $attributes) => [
            'marker_id' => $marker->id,
            'value' => $this->faker->randomFloat(2, $actualMin, $actualMax),
        ]);
    }
}