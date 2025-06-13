<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Marker;

class MarkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $markers = [
            // Lipid Panel
            ['name' => 'Total Cholesterol', 'unit' => 'mg/dL', 'healthy_min' => 125.00, 'healthy_max' => 200.00, 'description' => 'Measures all the cholesterol in your blood.'],
            ['name' => 'HDL Cholesterol', 'unit' => 'mg/dL', 'healthy_min' => 40.00, 'healthy_max' => 60.00, 'description' => 'High-density lipoprotein, or "good" cholesterol.'],
            ['name' => 'LDL Cholesterol', 'unit' => 'mg/dL', 'healthy_min' => null, 'healthy_max' => 100.00, 'description' => 'Low-density lipoprotein, or "bad" cholesterol.'],
            ['name' => 'Triglycerides', 'unit' => 'mg/dL', 'healthy_min' => null, 'healthy_max' => 150.00, 'description' => 'A type of fat found in your blood.'],

            // Comprehensive Metabolic Panel (CMP) / Basic Metabolic Panel (BMP)
            ['name' => 'Glucose', 'unit' => 'mg/dL', 'healthy_min' => 70.00, 'healthy_max' => 99.00, 'description' => 'Blood sugar level.'],
            ['name' => 'Sodium', 'unit' => 'mEq/L', 'healthy_min' => 135.00, 'healthy_max' => 145.00, 'description' => 'An electrolyte vital for normal body function.'],
            ['name' => 'Potassium', 'unit' => 'mEq/L', 'healthy_min' => 3.50, 'healthy_max' => 5.00, 'description' => 'An electrolyte important for muscle and nerve function.'],
            ['name' => 'Chloride', 'unit' => 'mEq/L', 'healthy_min' => 96.00, 'healthy_max' => 106.00, 'description' => 'An electrolyte that helps maintain proper blood volume and pressure.'],
            ['name' => 'Carbon Dioxide', 'unit' => 'mEq/L', 'healthy_min' => 23.00, 'healthy_max' => 29.00, 'description' => 'Indicates acid-base balance in the blood.'],
            ['name' => 'BUN (Blood Urea Nitrogen)', 'unit' => 'mg/dL', 'healthy_min' => 7.00, 'healthy_max' => 20.00, 'description' => 'Indicates kidney function.'],
            ['name' => 'Creatinine', 'unit' => 'mg/dL', 'healthy_min' => 0.60, 'healthy_max' => 1.30, 'description' => 'Indicates kidney function.'],
            ['name' => 'Calcium', 'unit' => 'mg/dL', 'healthy_min' => 8.50, 'healthy_max' => 10.20, 'description' => 'Mineral important for bones, muscles, and nerves.'],
            // CMP only
            ['name' => 'Total Protein', 'unit' => 'g/dL', 'healthy_min' => 6.00, 'healthy_max' => 8.30, 'description' => 'Measures albumins and globulins in the blood.'],
            ['name' => 'Albumin', 'unit' => 'g/dL', 'healthy_min' => 3.40, 'healthy_max' => 5.40, 'description' => 'A protein made by the liver.'],
            ['name' => 'Total Bilirubin', 'unit' => 'mg/dL', 'healthy_min' => 0.20, 'healthy_max' => 1.20, 'description' => 'Byproduct of red blood cell breakdown; indicates liver health.'],
            ['name' => 'Alkaline Phosphatase (ALP)', 'unit' => 'U/L', 'healthy_min' => 44.00, 'healthy_max' => 147.00, 'description' => 'Enzyme found in the liver, bones, and other tissues.'],
            ['name' => 'ALT (Alanine Aminotransferase)', 'unit' => 'U/L', 'healthy_min' => 7.00, 'healthy_max' => 56.00, 'description' => 'Liver enzyme.'],
            ['name' => 'AST (Aspartate Aminotransferase)', 'unit' => 'U/L', 'healthy_min' => 8.00, 'healthy_max' => 48.00, 'description' => 'Liver enzyme.'],

            // Complete Blood Count (CBC)
            ['name' => 'White Blood Cell (WBC) Count', 'unit' => 'x10^9/L', 'healthy_min' => 4.50, 'healthy_max' => 11.00, 'description' => 'Indicates immune system health and infection.'],
            ['name' => 'Red Blood Cell (RBC) Count', 'unit' => 'x10^12/L', 'healthy_min' => 4.50, 'healthy_max' => 5.50, 'description' => 'Measures oxygen-carrying cells.'],
            ['name' => 'Hemoglobin', 'unit' => 'g/dL', 'healthy_min' => 12.00, 'healthy_max' => 17.00, 'description' => 'Protein in red blood cells that carries oxygen.'],
            ['name' => 'Hematocrit', 'unit' => '%', 'healthy_min' => 37.00, 'healthy_max' => 50.00, 'description' => 'Percentage of blood volume occupied by red blood cells.'],
            ['name' => 'Platelet Count', 'unit' => 'x10^9/L', 'healthy_min' => 150.00, 'healthy_max' => 450.00, 'description' => 'Cells involved in blood clotting.'],

            // Thyroid Panel
            ['name' => 'TSH (Thyroid Stimulating Hormone)', 'unit' => 'mIU/L', 'healthy_min' => 0.40, 'healthy_max' => 4.00, 'description' => 'Hormone that stimulates the thyroid gland.'],
            ['name' => 'Free T4', 'unit' => 'ng/dL', 'healthy_min' => 0.80, 'healthy_max' => 1.80, 'description' => 'Free form of thyroxine hormone.'],
            ['name' => 'Free T3', 'unit' => 'pg/dL', 'healthy_min' => 2.30, 'healthy_max' => 4.20, 'description' => 'Free form of triiodothyronine hormone.'],

            // Other Important Markers
            ['name' => 'Vitamin D (25-Hydroxy)', 'unit' => 'ng/mL', 'healthy_min' => 30.00, 'healthy_max' => 100.00, 'description' => 'Vitamin D levels for bone health and immune function.'],
            ['name' => 'HbA1c', 'unit' => '%', 'healthy_min' => null, 'healthy_max' => 5.60, 'description' => 'Average blood glucose over the past 2-3 months.'],
        ];

        foreach ($markers as $marker) {
            Marker::firstOrCreate(['name' => $marker['name']], $marker);
        }
    }
}