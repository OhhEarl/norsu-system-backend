<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{

    public function run()
    {


        DB::table('users')->insert([
            'id' => 1,
            'name' => 'Earl Jason Cordero',
            'email' => 'earljasoncordero@gmail.com',
            'password' => Hash::make('earljason123123'),
            'created_at' => now(),
            'updated_at' => now()
        ]);
        $user = \App\Models\User::find(1);  // Assuming User model is in the App\Models namespace
        $token = $user->createToken('Personal Access Token')->accessToken;


        DB::table('users')->insert([
            'id' => 2,
            'name' => 'admin',
            'email' => 'admin@softui.com',
            'password' => Hash::make('secret'),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $faker = Faker::create();

        foreach (range(3, 100) as $index) {
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'remember_token' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
