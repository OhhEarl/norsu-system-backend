<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StudentValidationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Populate student_validations table with random data
        foreach (range(3, 100) as $index) {
            DB::table('student_validations')->insert([
                'user_id' => $faker->numberBetween(3, 100), // Assuming you have 100 users in the users table
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'user_name' => $faker->userName,
                'area_of_expertise' => $faker->numberBetween(1, 100), // Assuming you have 100 areas of expertise
                'norsu_id_number' => $faker->unique()->ean8, // Generate unique NORSU ID number
                'course' => $faker->randomElement(['Computer Science', 'Engineering', 'Business Administration', 'Criminology', 'Education']), // Random course
                'year_level' => $faker->numberBetween(1, 5),
                'front_id' => $faker->imageUrl(), // Generate a placeholder image URL for front ID
                'back_id' => $faker->imageUrl(), // Generate a placeholder image URL for back ID
                'user_avatar' => $faker->imageUrl(200, 200, 'people'), // Generate a placeholder user avatar
                'is_student' => true, // Assume all records are for students
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
