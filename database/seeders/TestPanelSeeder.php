<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TestPanel;

class TestPanelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $panels = [
            ['name' => 'Lipid Panel', 'description' => 'Measures cholesterol and fats in the blood.'],
            ['name' => 'Comprehensive Metabolic Panel (CMP)', 'description' => 'Measures glucose, electrolytes, kidney, and liver function.'],
            ['name' => 'Basic Metabolic Panel (BMP)', 'description' => 'Measures glucose, electrolytes, and kidney function.'],
            ['name' => 'Complete Blood Count (CBC)', 'description' => 'Measures components of blood, including red blood cells, white blood cells, and platelets.'],
            ['name' => 'Thyroid Panel', 'description' => 'Measures thyroid hormones to assess thyroid function.'],
            ['name' => 'Vitamin D Test', 'description' => 'Measures vitamin D levels in the blood.'],
            ['name' => 'HbA1c Test', 'description' => 'Measures average blood sugar levels over the past 2-3 months.'],
        ];

        foreach ($panels as $panel) {
            TestPanel::firstOrCreate(['name' => $panel['name']], $panel);
        }
    }
}