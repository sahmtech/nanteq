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
        Schema::create('sounds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_id')->constrained('letters')->cascadeOnDelete();
            $table->foreignId('level_id')->constrained('levels')->cascadeOnDelete();
            $table->foreignId('sound_id')->constrained('sounds')->cascadeOnDelete();
            $table->string('written_word');
            $table->string('audio')->nullable();
            $table->string('picture')->nullable();
            $table->string('xray_video')->nullable();
            $table->string('natural_video')->nullable();
            $table->string('type')->nullable(); // picture, video, audio
            $table->integer('attempts_to_success')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sounds');
    }
};
