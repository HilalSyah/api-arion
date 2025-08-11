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
        Schema::create('workout_levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_category_id')->constrained('workout_categories')->onDelete('cascade');
            $table->string('name');
            $table->string('sets_reps');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_levels');
    }
};
