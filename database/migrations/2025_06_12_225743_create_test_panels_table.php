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
        Schema::create('test_panels', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // E.g., "Lipid Panel", "Comprehensive Metabolic Panel"
            $table->text('description')->nullable(); // Optional: A brief description of what this panel includes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_panels');
    }
};