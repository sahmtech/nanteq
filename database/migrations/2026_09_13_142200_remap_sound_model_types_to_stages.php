<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Keep live sounds on the STT path.
     * Old 0 = STT and old 1 = transcribe both become Stage 3.
     */
    public function up(): void
    {
        DB::table('sounds')->whereIn('model_type', [0, 1])->update([
            'model_type' => 2,
        ]);
    }

    public function down(): void
    {
        DB::table('sounds')->where('model_type', 2)->update([
            'model_type' => 0,
        ]);
    }
};
