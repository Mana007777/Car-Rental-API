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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('make');
            $table->string('model');
            $table->year('year');
            $table->string('vin')->unique();
            $table->string('license_plate')->unique();
            $table->string('color')->nullable();
            $table->integer('mileage');
            $table->enum('status', ['Available', 'Rented', 'Maintenance', 'Reserved'])->default('Available');
            $table->decimal('rental_rate', 10, 2);
            $table->foreignId('category_id')->nullable()->constrained('vehicle_categories')->nullOnDelete();
            $table->foreignId('insurance_id')->nullable()->constrained('insurances')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
