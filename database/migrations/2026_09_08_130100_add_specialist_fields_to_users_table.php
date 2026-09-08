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
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'specialist_code')) {
                $table->string('specialist_code', 6)->nullable()->unique();
            }

            if (! Schema::hasColumn('users', 'plan_id')) {
                $table->foreignId('plan_id')
                    ->nullable()
                    ->constrained('plans')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'plan_id')) {
                $table->dropConstrainedForeignId('plan_id');
            }

            if (Schema::hasColumn('users', 'specialist_code')) {
                $table->dropUnique(['specialist_code']);
                $table->dropColumn('specialist_code');
            }
        });
    }
};
