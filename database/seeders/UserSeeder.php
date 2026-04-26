<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'username' => 'admin',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'User',
                'email' => 'user@gmail.com',
                'username' => 'user',
                'password' => Hash::make('12345678'),
            ]
        ]);
    }
}