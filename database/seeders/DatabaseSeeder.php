<?php

namespace Database\Seeders;


use App\Models\AgeGroup;
use App\Models\Letter;
use App\Models\User;
use App\Models\Age;
use App\Models\Sound;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@admin.com'], [
            'name' => 'admin',
            'phone_number' => '999999999',
            'password' => 'admin123456',
            'phone_code' => '+966',
            'gender' => 'male',
            'profile_completion_status' => 'completed',
        ]);
        $ages = require_once base_path('data/default_ages.php');
        $letters = require_once base_path('data/default_letters.php');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        AgeGroup::truncate();
        Age::truncate();
        Letter::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        AgeGroup::create([
            'name' => 'افتراضي',
            'from_age' => 0,
            'to_age' => 10,
        ]);
        Age::insert($ages);
        Letter::insert($letters);

    }
}
