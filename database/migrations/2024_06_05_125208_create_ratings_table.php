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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('letter_id')->nullable()->constrained('letters')->nullOnDelete();
            $table->foreignId('sound_id')->nullable()->constrained('sounds')->nullOnDelete();
            $table->integer('success_attempts')->nullable();
            $table->integer('failure_attempts')->nullable();
            $table->string('status')->nullable(); // completed, in_progress
            $table->json('records')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};
