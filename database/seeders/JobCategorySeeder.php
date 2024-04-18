<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JobCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $jobCategories = [
            ['value' => 'Tutoring and Academic Support'],
            ['value' => 'Writing and Editing'],
            ['value' => 'Computer Science and Programming'],
            ['value' => 'Graphic Design and Multimedia'],
            ['value' => 'Math and Science Projects'],
            ['value' => 'Language Services'],
            ['value' => 'Research and Analysis'],
            ['value' => 'Arts and Humanities'],
            ['value' => 'STEM Projects'],
            ['value' => 'IT and Technical Support'],
            ['value' => 'Administrative Support'],
            ['value' => 'Test Preparation'],
            ['value' => 'Study Skills and Organization'],
            ['value' => 'Career Development'],
            ['value' => 'Special Education Support'],
            ['value' => 'Event Planning and Coordination'],
            ['value' => 'Web Development and Design'],
            ['value' => 'Photography and Videography'],
            ['value' => 'Copywriting and Copyediting'],
            ['value' => 'Translation and Localization Services'],
            ['value' => 'Academic Research Assistance and Literature Review'],
            ['value' => 'Resume Writing'],
            ['value' => 'Voiceover Services'],
            ['value' => 'CAD Drafting and Design'],
            ['value' => 'Circuit Design and PCB Layout'],
            ['value' => 'Finite Element Analysis (FEA) and Simulation'],
            ['value' => '3D Printing and Rapid Prototyping'],
            ['value' => 'Structural Engineering Analysis'],
            ['value' => 'Renewable Energy System Design'],
            ['value' => 'Transportation Systems Engineering'],
            ['value' => 'Aerospace Engineering and Design'],
            ['value' => 'Juvenile Justice Programs and Services'],
        ];

        // Insert data into the database
        DB::table('job_categories')->insert($jobCategories);
    }
}
