<?php

namespace Database\Seeders;

use App\Models\Expertise;
use App\Models\StudentValidation;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UserSeeder::class,
            ExpertiseSeeder::class,
            JobCategorySeeder::class,
            CreateJobsTableSeeder::class,
            JobTagsTableSeeder::class,
            JobAttachmentsTableSeeder::class,
            StudentValidation::class

        ]);
    }
}
