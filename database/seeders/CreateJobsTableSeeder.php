<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CreateJobsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        foreach (range(2, 10) as $index) {
            DB::table('create_jobs')->insert([
                'student_user_id' => $faker->numberBetween(2, 10), // Ensure you have these IDs in your student_validations table
                'job_title' => $faker->sentence,
                'job_category_id' => $faker->numberBetween(1, 5), // Ensure you have these IDs in your job_categories table
                'job_description' => $faker->paragraph,
                'job_start_date' => Carbon::now()->format('Y-m-d'),
                'job_end_date' => Carbon::now()->addDays($faker->numberBetween(1, 30))->format('Y-m-d'),
                'job_budget_from' => $faker->randomFloat(2, 100, 1000),
                'job_budget_to' => $faker->randomFloat(2, 1001, 5000),
                'job_finished' => $faker->boolean,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
