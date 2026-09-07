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
        Schema::table('sounds', function (Blueprint $table) {
            $table->string('spelled_word')->nullable()->after('written_word');
            $table->integer('success_rate')->nullable()->after('attempts_to_success');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sounds', function (Blueprint $table) {
            $table->dropColumn('spelled_word');
            $table->dropColumn('success_rate');
        });
    }
};
