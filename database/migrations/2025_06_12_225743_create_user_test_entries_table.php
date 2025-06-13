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
        Schema::create('user_test_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Link to the user who performed the test
            $table->foreignId('test_panel_id')->constrained()->onDelete('cascade'); // Link to the broad test panel (e.g., Lipid Panel)
            $table->date('test_date'); // The date the blood test was taken

            $table->timestamps();

            // Add an index for faster lookups
            $table->index(['user_id', 'test_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_test_entries');
    }
};