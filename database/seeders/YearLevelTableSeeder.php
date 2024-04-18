<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YearLevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('year_levels')->insert([
            'id' => 1,
            'year_level' => '1st Year',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('year_levels')->insert([
            'id' => 2,
            'year_level' => '2nd Year',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('year_levels')->insert([
            'id' => 3,
            'year_level' => '3rd Year',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('year_levels')->insert([
            'id' => 4,
            'year_level' => '4th Year',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        DB::table('year_levels')->insert([
            'id' => 5,
            'year_level' => '5th Year',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
