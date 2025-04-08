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
        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apartment_id')->constrained('apartments')->onDelete('cascade');
            $table->boolean('wellness')->default(false);
            $table->boolean('breakfast')->default(false);
            $table->boolean('parking')->default(false);
            $table->boolean('wifi')->default(false);
            $table->boolean('all_inclusive')->default(false);
            $table->boolean('near_the_beach')->default(false);
            $table->boolean('near_the_center')->default(false);
            $table->boolean('pet_friendly')->default(false);
            $table->boolean('smoking_allowed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filters');
    }
};
