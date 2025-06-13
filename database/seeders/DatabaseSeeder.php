<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\TestPanel;
use App\Models\Marker;
use App\Models\UserTestEntry;
use App\Models\UserMarkerValue;
use Carbon\Carbon; // For date manipulation

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call initial static data seeders first
        $this->call([
            TestPanelSeeder::class,
            MarkerSeeder::class,
        ]);

        // Create a few users for demo purposes
        $users = User::factory(3)->create();

        // Get all available test panels and markers
        $allTestPanels = TestPanel::all();
        $allMarkers = Marker::all();

        // Define which markers typically belong to which panels for realistic data generation
        // This is a simplified mapping for seeding purposes. In a real app, this might be managed differently.
        $panelToMarkersMap = [
            'Lipid Panel' => ['Total Cholesterol', 'HDL Cholesterol', 'LDL Cholesterol', 'Triglycerides'],
            'Comprehensive Metabolic Panel (CMP)' => [
                'Glucose', 'Sodium', 'Potassium', 'Chloride', 'Carbon Dioxide',
                'BUN (Blood Urea Nitrogen)', 'Creatinine', 'Calcium', 'Total Protein',
                'Albumin', 'Total Bilirubin', 'Alkaline Phosphatase (ALP)', 'ALT (Alanine Aminotransferase)',
                'AST (Aspartate Aminotransferase)'
            ],
            'Basic Metabolic Panel (BMP)' => [
                'Glucose', 'Sodium', 'Potassium', 'Chloride', 'Carbon Dioxide',
                'BUN (Blood Urea Nitrogen)', 'Creatinine', 'Calcium'
            ],
            'Complete Blood Count (CBC)' => [
                'White Blood Cell (WBC) Count', 'Red Blood Cell (RBC) Count',
                'Hemoglobin', 'Hematocrit', 'Platelet Count'
            ],
            'Thyroid Panel' => ['TSH (Thyroid Stimulating Hormone)', 'Free T4', 'Free T3'],
            'Vitamin D Test' => ['Vitamin D (25-Hydroxy)'],
            'HbA1c Test' => ['HbA1c'],
        ];

        // For each user, create multiple test entries over time
        foreach ($users as $user) {
            // Create a few test entries for each user
            for ($i = 0; $i < 4; $i++) { // 4 test entries per user
                $testDate = Carbon::now()->subMonths($i * 3)->subDays(rand(0, 30)); // Tests every 3 months

                // Randomly pick a test panel for this entry
                $randomTestPanel = $allTestPanels->random();

                $userTestEntry = UserTestEntry::factory()->for($user)->for($randomTestPanel)->create([
                    'test_date' => $testDate->format('Y-m-d'),
                ]);

                // Get markers associated with the selected test panel's name
                $markersForPanelNames = $panelToMarkersMap[$randomTestPanel->name] ?? [];
                $markersForPanel = $allMarkers->whereIn('name', $markersForPanelNames);

                // Create marker values for this test entry based on the panel's markers
                foreach ($markersForPanel as $marker) {
                    UserMarkerValue::factory()->for($userTestEntry)->forMarker($marker)->create();
                }
            }
        }
    }
}