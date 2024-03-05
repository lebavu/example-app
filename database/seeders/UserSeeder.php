<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Import the DB facade
use Illuminate\Support\Facades\Hash; // Import the Hash facade for password hashing

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => '1',
            'name' => 'vu',
            'email' => 'vulb.dev@gmail.com',
            'password' => Hash::make('123456'), // Use Hash facade for clarity
            'email_verified_at' => now(), // Use now() for current timestamp or null if not verified
            // 'remember_token' => '', // Can be omitted if not setting a value
        ]);
    }
}
