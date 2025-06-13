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
        Schema::create('markers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // E.g., "Cholesterol", "Glucose", "Hemoglobin"
            $table->string('unit'); // E.g., "mg/dL", "mmol/L", "g/dL", "%"
            $table->decimal('healthy_min', 8, 2)->nullable(); // Optimal minimum value
            $table->decimal('healthy_max', 8, 2)->nullable(); // Optimal maximum value
            $table->text('description')->nullable(); // Short explanation of what the marker is for
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markers');
    }
};