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
        Schema::create('level_progresses', function (Blueprint $table) {
            $table->id();
            $table->integer('progress')->nullable();
            $table->integer('sounds_count')->nullable();
            $table->integer('completed_sounds_count')->nullable();
            $table->date('last_completed_sound_date')->nullable();
            $table->string('status')->nullable(); // completed, in_progress
            $table->foreignId('previous_level_id')->nullable()->constrained('levels')->nullOnDelete();
            $table->foreignId('level_id')->nullable()->constrained('levels')->nullOnDelete();
            $table->foreignId('letter_id')->nullable()->constrained('letters')->nullOnDelete();
            $table->foreignId('trainee_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('level_progresses');
    }
};
