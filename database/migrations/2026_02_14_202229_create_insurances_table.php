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
        Schema::create('insurances', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('policy_number');
            $table->enum('coverage_type', ['Full', 'Partial', 'Third-Party'])->default('Full');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('premium', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('insurances');
    }
};
