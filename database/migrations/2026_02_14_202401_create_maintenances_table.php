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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars')->cascadeOnDelete();
            $table->date('maintenance_date');
            $table->date('next_due_date')->nullable();
            $table->enum('maintenance_type', ['Repair', 'Service', 'Inspection'])->default('Service');
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->foreignId('performed_by')->nullable()->constrained('employees')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
