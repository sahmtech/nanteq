<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_pronunciation_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('letter_id')->nullable()->constrained('letters')->nullOnDelete();
            $table->foreignId('sound_id')->nullable()->constrained('sounds')->nullOnDelete();
            $table->string('target')->nullable();
            $table->string('heard')->nullable();
            $table->string('ai_status')->nullable();
            $table->decimal('ai_score', 8, 4)->nullable();
            $table->unsignedTinyInteger('score')->nullable();
            $table->unsignedTinyInteger('overall_score')->nullable();
            $table->text('technical_log')->nullable();
            $table->string('audio_path')->nullable();
            $table->string('audio_url')->nullable();
            $table->string('request_id')->nullable();
            $table->unsignedSmallInteger('stage')->nullable();
            $table->unsignedTinyInteger('model_type')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->unsignedSmallInteger('http_status')->nullable();
            $table->json('payload')->nullable();
            $table->timestamps();

            $table->index(['letter_id', 'created_at']);
            $table->index(['sound_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_pronunciation_attempts');
    }
};
