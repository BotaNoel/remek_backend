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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Hivatkozás a users táblára
            $table->foreignId('apartment_id')->constrained('apartments')->onDelete('cascade'); // Hivatkozás az apartments táblára
            $table->bigInteger('rating_id')->unsigned(); // Használjuk a bigInteger típust a rating_id-hoz
            $table->foreign('rating_id')->references('id')->on('ratings')->onDelete('cascade'); // Hivatkozás a ratings táblára
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
