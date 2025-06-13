<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_marker_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_test_entry_id')->constrained()->onDelete('cascade'); // Link to the specific test entry
            $table->foreignId('marker_id')->constrained()->onDelete('cascade'); // Link to the type of marker (e.g., Cholesterol)
            $table->decimal('value', 10, 2); // The actual numerical result of the test

            $table->timestamps();

            // Ensure unique combination of test entry and marker
            $table->unique(['user_test_entry_id', 'marker_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_marker_values');
    }
};